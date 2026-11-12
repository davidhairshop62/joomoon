<?php
/**
 * api_auth.php
 * Joomoon Portal Authentication & Session API
 * Supports:
 * - POST action=login (login_id, password)
 * - POST action=quick_login (login_id for demo account switching)
 * - POST action=logout
 * - GET  action=me (returns current logged-in user profile from joomoon.users)
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

$action = $_REQUEST['action'] ?? 'me';

function sanitizeUserRow(array $row): array {
    unset($row['password_hash']);
    return $row;
}

try {
    if ($action === 'login') {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $loginId = trim($input['login_id'] ?? '');
        $password = (string)($input['password'] ?? '');

        if ($loginId === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Please enter both Login ID and Password. / 아이디와 비밀번호를 모두 입력해 주세요.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `login_id` = :login_id OR `user_code` = :user_code LIMIT 1");
        $stmt->execute([':login_id' => $loginId, ':user_code' => $loginId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Invalid Login ID or Password. / 아이디 또는 비밀번호가 일치하지 않습니다.']);
            exit;
        }

        if ($user['status'] !== 'active') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'This account is currently ' . $user['status'] . '. / 현재 계정이 활성 상태가 아닙니다.']);
            exit;
        }

        $conn->prepare("UPDATE `users` SET `last_login_at` = NOW() WHERE `id` = :id")->execute([':id' => $user['id']]);
        $user['last_login_at'] = date('Y-m-d H:i:s');

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['login_id'] = $user['login_id'];

        echo json_encode([
            'ok' => true,
            'user' => sanitizeUserRow($user)
        ]);
        exit;
    }

    if ($action === 'quick_login') {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $loginId = trim($input['login_id'] ?? '');

        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `login_id` = :login_id OR `user_code` = :user_code LIMIT 1");
        $stmt->execute([':login_id' => $loginId, ':user_code' => $loginId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'Sample account not found.']);
            exit;
        }

        $conn->prepare("UPDATE `users` SET `last_login_at` = NOW() WHERE `id` = :id")->execute([':id' => $user['id']]);
        $user['last_login_at'] = date('Y-m-d H:i:s');

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['login_id'] = $user['login_id'];

        echo json_encode([
            'ok' => true,
            'user' => sanitizeUserRow($user)
        ]);
        exit;
    }

    if ($action === 'logout') {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        echo json_encode(['ok' => true]);
        exit;
    }

    if ($action === 'demo_accounts') {
        $stmt = $conn->query("
            SELECT id, user_code, user_type, login_id, company_name, manager_name, city_district, state_province, country, currency, price_tier
            FROM `users`
            WHERE `status` = 'active'
            ORDER BY FIELD(user_type, 'Manager', 'Buyer', 'Customer'), id ASC
        ");
        echo json_encode([
            'ok' => true,
            'accounts' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
        exit;
    }

    // Default: action === 'me'
    if (empty($_SESSION['user_id'])) {
        echo json_encode(['ok' => false, 'authenticated' => false]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
    $stmt->execute([':id' => (int)$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION = [];
        echo json_encode(['ok' => false, 'authenticated' => false]);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'authenticated' => true,
        'user' => sanitizeUserRow($user)
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>
