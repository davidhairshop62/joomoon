<?php
/**
 * api_users.php
 * Role-Aware Users & Self-Profile CRUD API for joomoon.users
 *
 * Permissions:
 * - Manager:
 *   * Full list, search, filter by user_type/status/country
 *   * Get any user detail
 *   * Create new user (Manager, Buyer, Customer)
 *   * Update any user (all 53 fields including user_type, status, credit_limit, price_tier, admin_memo, etc.)
 *   * Delete user (except self)
 * - Buyer & Customer:
 *   * View own profile only
 *   * Update own profile (company, business registration, CEO/contact, shipping address, carrier preferences, bank/tax invoice info, password)
 *   * Cannot modify administrative fields (user_type, status, price_tier, discount_rate, credit_limit, outstanding_balance, deposit_balance, admin_memo)
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorised. Please sign in first. / 로그인이 필요합니다.']);
    exit;
}

$currentUserId = (int)$_SESSION['user_id'];
$meStmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
$meStmt->execute([':id' => $currentUserId]);
$currentUser = $meStmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Session expired. / 세션이 만료되었습니다.']);
    exit;
}

$isManager = ($currentUser['user_type'] === 'Manager');
$action = $_REQUEST['action'] ?? 'list';

// Editable fields for Buyer / Customer (Self-Service Profile)
$selfEditableFields = [
    'company_name', 'business_number', 'corporate_number', 'business_type', 'business_item', 'ceo_name',
    'manager_name', 'manager_department', 'manager_position', 'phone', 'mobile', 'fax', 'email',
    'recipient_name', 'recipient_phone', 'address1', 'address2', 'city_district', 'state_province', 'country', 'postal_code',
    'preferred_carrier', 'shipping_account_no', 'shipping_fee_type', 'shipping_memo', 'order_memo',
    'tax_invoice_email', 'accounting_manager_name', 'accounting_manager_phone', 'payment_method', 'currency',
    'bank_name', 'bank_account_number', 'bank_account_holder', 'swift_code'
];

// Additional fields only Manager can edit
$managerOnlyFields = [
    'user_code', 'user_type', 'login_id', 'status',
    'price_tier', 'discount_rate', 'payment_terms', 'tax_type',
    'credit_limit', 'outstanding_balance', 'deposit_balance', 'admin_memo'
];

$numericFields = ['discount_rate', 'credit_limit', 'outstanding_balance', 'deposit_balance'];

function stripPassword(array $row): array {
    unset($row['password_hash']);
    return $row;
}

function generateNextUserCode(PDO $conn, string $userType): string {
    $prefix = match ($userType) {
        'Manager' => 'MGR',
        'Buyer'   => 'BUY',
        default   => 'CST',
    };
    $stmt = $conn->prepare("SELECT `user_code` FROM `users` WHERE `user_code` LIKE :prefix ORDER BY `id` DESC LIMIT 1");
    $stmt->execute([':prefix' => $prefix . '-%']);
    $last = $stmt->fetchColumn();
    $num = 1;
    if ($last && preg_match('/-(\d+)$/', $last, $m)) {
        $num = (int)$m[1] + 1;
    }
    return sprintf('%s-%03d', $prefix, $num);
}

try {
    if ($action === 'list') {
        if (!$isManager) {
            // Non-managers only receive their own record
            echo json_encode([
                'ok' => true,
                'is_manager' => false,
                'counts' => [
                    'All' => 1,
                    'Manager' => 0,
                    'Buyer' => $currentUser['user_type'] === 'Buyer' ? 1 : 0,
                    'Customer' => $currentUser['user_type'] === 'Customer' ? 1 : 0,
                ],
                'users' => [stripPassword($currentUser)]
            ]);
            exit;
        }

        $userTypeFilter = trim($_GET['user_type'] ?? '');
        $statusFilter   = trim($_GET['status'] ?? '');
        $countryFilter  = trim($_GET['country'] ?? '');
        $search         = trim($_GET['q'] ?? '');

        $where = [];
        $params = [];

        if ($userTypeFilter !== '' && in_array($userTypeFilter, ['Manager', 'Buyer', 'Customer'], true)) {
            $where[] = "`user_type` = :user_type";
            $params[':user_type'] = $userTypeFilter;
        }
        if ($statusFilter !== '') {
            $where[] = "`status` = :status";
            $params[':status'] = $statusFilter;
        }
        if ($countryFilter !== '') {
            $where[] = "`country` = :country";
            $params[':country'] = $countryFilter;
        }
        if ($search !== '') {
            $where[] = "(
                `user_code` LIKE :q OR
                `login_id` LIKE :q OR
                `company_name` LIKE :q OR
                `business_number` LIKE :q OR
                `ceo_name` LIKE :q OR
                `manager_name` LIKE :q OR
                `email` LIKE :q OR
                `phone` LIKE :q OR
                `mobile` LIKE :q OR
                `city_district` LIKE :q OR
                `state_province` LIKE :q
            )";
            $params[':q'] = '%' . $search . '%';
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT * FROM `users` $whereSql ORDER BY FIELD(`user_type`, 'Manager', 'Buyer', 'Customer'), `id` ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $rows = array_map('stripPassword', $stmt->fetchAll(PDO::FETCH_ASSOC));

        // Role counts
        $countStmt = $conn->query("SELECT `user_type`, COUNT(*) AS cnt FROM `users` GROUP BY `user_type`");
        $counts = ['All' => 0, 'Manager' => 0, 'Buyer' => 0, 'Customer' => 0];
        foreach ($countStmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
            $counts[$c['user_type']] = (int)$c['cnt'];
            $counts['All'] += (int)$c['cnt'];
        }

        echo json_encode([
            'ok' => true,
            'is_manager' => true,
            'counts' => $counts,
            'users' => $rows
        ]);
        exit;
    }

    if ($action === 'get') {
        $targetId = isset($_GET['id']) ? (int)$_GET['id'] : $currentUserId;
        if (!$isManager && $targetId !== $currentUserId) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Access denied. You can only view your own profile.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $targetId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'User not found.']);
            exit;
        }

        echo json_encode(['ok' => true, 'user' => stripPassword($row)]);
        exit;
    }

    if ($action === 'save') {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $targetId = isset($input['id']) && $input['id'] !== '' ? (int)$input['id'] : 0;

        // Non-managers can ONLY update their own profile
        if (!$isManager) {
            if ($targetId === 0 || $targetId !== $currentUserId) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'Buyers and Customers can only edit their own profile information. / 본인의 정보만 수정할 수 있습니다.']);
                exit;
            }
        }

        $allowedFields = $isManager ? array_merge($selfEditableFields, $managerOnlyFields) : $selfEditableFields;

        if ($targetId === 0) {
            // Creating a new user (Manager only)
            $loginId = trim($input['login_id'] ?? '');
            if ($loginId === '') {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Login ID is required. / 로그인 아이디는 필수입니다.']);
                exit;
            }

            $userType = $input['user_type'] ?? 'Buyer';
            if (!in_array($userType, ['Manager', 'Buyer', 'Customer'], true)) {
                $userType = 'Buyer';
            }

            $userCode = trim($input['user_code'] ?? '');
            if ($userCode === '') {
                $userCode = generateNextUserCode($conn, $userType);
            }

            $rawPassword = trim($input['password'] ?? '');
            if ($rawPassword === '') {
                $rawPassword = '12345678';
            }

            $insertData = [
                'user_code' => $userCode,
                'user_type' => $userType,
                'login_id'  => $loginId,
                'password_hash' => password_hash($rawPassword, PASSWORD_DEFAULT),
            ];

            foreach ($allowedFields as $field) {
                if (in_array($field, ['user_code', 'user_type', 'login_id'], true)) {
                    continue;
                }
                if (array_key_exists($field, $input)) {
                    $val = $input[$field];
                    if (in_array($field, $numericFields, true)) {
                        $insertData[$field] = ($val === '' || $val === null) ? 0 : (float)$val;
                    } else {
                        $insertData[$field] = ($val === '') ? null : $val;
                    }
                }
            }

            $cols = array_keys($insertData);
            $colSql = implode(', ', array_map(fn($c) => "`$c`", $cols));
            $valSql = implode(', ', array_map(fn($c) => ":$c", $cols));

            $stmt = $conn->prepare("INSERT INTO `users` ($colSql) VALUES ($valSql)");
            foreach ($insertData as $k => $v) {
                $stmt->bindValue(":$k", $v);
            }
            $stmt->execute();
            $newId = (int)$conn->lastInsertId();

            $getStmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id");
            $getStmt->execute([':id' => $newId]);

            echo json_encode([
                'ok' => true,
                'mode' => 'created',
                'user' => stripPassword($getStmt->fetch(PDO::FETCH_ASSOC))
            ]);
            exit;
        } else {
            // Updating existing user
            $updates = [];
            $params = [':id' => $targetId];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $input)) {
                    $val = $input[$field];
                    if (in_array($field, $numericFields, true)) {
                        $updates[] = "`$field` = :$field";
                        $params[":$field"] = ($val === '' || $val === null) ? 0 : (float)$val;
                    } else {
                        $updates[] = "`$field` = :$field";
                        $params[":$field"] = ($val === '') ? null : $val;
                    }
                }
            }

            if (!empty($input['password']) && trim($input['password']) !== '') {
                $updates[] = "`password_hash` = :password_hash";
                $params[':password_hash'] = password_hash(trim($input['password']), PASSWORD_DEFAULT);
            }

            if (empty($updates)) {
                echo json_encode(['ok' => true, 'mode' => 'unchanged']);
                exit;
            }

            $sql = "UPDATE `users` SET " . implode(', ', $updates) . " WHERE `id` = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            $getStmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id");
            $getStmt->execute([':id' => $targetId]);
            $updatedUser = stripPassword($getStmt->fetch(PDO::FETCH_ASSOC));

            echo json_encode([
                'ok' => true,
                'mode' => 'updated',
                'user' => $updatedUser
            ]);
            exit;
        }
    }

    if ($action === 'delete') {
        if (!$isManager) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Only Managers can delete user accounts. / 관리자만 삭제할 수 있습니다.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $targetId = (int)($input['id'] ?? $_GET['id'] ?? 0);

        if ($targetId <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Invalid user ID.']);
            exit;
        }
        if ($targetId === $currentUserId) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'You cannot delete your own active Manager account. / 현재 로그인된 본인의 관리자 계정은 삭제할 수 없습니다.']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM `users` WHERE `id` = :id");
        $stmt->execute([':id' => $targetId]);

        echo json_encode(['ok' => true, 'deleted_id' => $targetId]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Unknown action: ' . $action]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
