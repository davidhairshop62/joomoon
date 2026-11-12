<?php
/**
 * setup_users_table.php
 * config.php를 이용하여 joomoon 데이터베이스에 users 테이블을 생성/갱신합니다.
 * 구분 (User Type): Manager (관리자), Buyer (거래처/바이어), Customer (고객) - 기본값: 'Buyer'
 * 포함 항목: 기본 계정, 사업자/상호 정보, 배송지 주소(주소1, 주소2, 구군, 도, 국가, 우편번호),
 *          담당자/대표자 정보, 주문/배송 설정, 회계/정산/세금계산서 관련 필드 (한/영 설명 포함)
 */

require_once __DIR__ . '/config.php';

try {
    // 기존에 생성된 빈 테이블 또는 구 스키마를 최신 정의로 반영하기 위해 재생성/수정 처리
    $countStmt = $conn->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'users'");
    $exists = (int)$countStmt->fetchColumn() > 0;

    if ($exists) {
        $rowCount = (int)$conn->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
        if ($rowCount === 0) {
            $conn->exec("DROP TABLE `users`");
        } else {
            // 기존 데이터가 있는 경우 안전하게 값 매핑 후 ALTER 수행
            $conn->exec("ALTER TABLE `users` MODIFY COLUMN `user_type` VARCHAR(30) NOT NULL DEFAULT 'Buyer'");
            $conn->exec("UPDATE `users` SET `user_type` = 'Manager' WHERE `user_type` = '관리자'");
            $conn->exec("UPDATE `users` SET `user_type` = 'Buyer' WHERE `user_type` = '거래처'");
            $conn->exec("UPDATE `users` SET `user_type` = 'Customer' WHERE `user_type` = '고객'");
        }
    }

    $sql = "
    CREATE TABLE IF NOT EXISTS `users` (
        -- 1. 기본 식별 및 계정 정보 (Identification & Account Info)
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '고유 ID / Unique ID (PK)',
        `user_code` VARCHAR(30) DEFAULT NULL COMMENT '회원/거래처 코드 / User or Partner Code (e.g., MGR-001, BUY-001, CST-001)',
        `user_type` ENUM('Manager', 'Buyer', 'Customer') NOT NULL DEFAULT 'Buyer' COMMENT '사용자 구분 (관리자, 거래처, 고객) / User Role (Manager, Buyer, Customer)',
        `login_id` VARCHAR(100) NOT NULL COMMENT '로그인 아이디 / Login ID',
        `password_hash` VARCHAR(255) NOT NULL COMMENT '비밀번호 해시 / Password Hash',
        `status` ENUM('active', 'inactive', 'suspended', 'pending') NOT NULL DEFAULT 'active' COMMENT '계정 상태 (정상, 비활성, 정지, 승인대기) / Account Status (Active, Inactive, Suspended, Pending)',

        -- 2. 상호 및 사업자 정보 (Company & Business Registration Info)
        `company_name` VARCHAR(150) DEFAULT NULL COMMENT '상호명 (법인명/상호) / Company or Business Name',
        `business_number` VARCHAR(30) DEFAULT NULL COMMENT '사업자등록번호 / Business Registration Number (Tax ID / ABN)',
        `corporate_number` VARCHAR(30) DEFAULT NULL COMMENT '법인등록번호 / Corporate Registration Number',
        `business_type` VARCHAR(100) DEFAULT NULL COMMENT '업태 / Business Type (Sector)',
        `business_item` VARCHAR(100) DEFAULT NULL COMMENT '종목 / Business Item (Category)',
        `ceo_name` VARCHAR(100) DEFAULT NULL COMMENT '대표자명 / CEO or Representative Name',

        -- 3. 담당자 및 연락처 정보 (Contact Person & Communication Info)
        `manager_name` VARCHAR(100) DEFAULT NULL COMMENT '담당자명 (또는 고객 성명) / Contact Person or Customer Name',
        `manager_department` VARCHAR(100) DEFAULT NULL COMMENT '담당자 부서 / Contact Department',
        `manager_position` VARCHAR(100) DEFAULT NULL COMMENT '담당자 직책 / Contact Job Title or Position',
        `phone` VARCHAR(30) DEFAULT NULL COMMENT '대표 전화번호 / Main Phone Number',
        `mobile` VARCHAR(30) DEFAULT NULL COMMENT '담당자 휴대폰 번호 / Mobile Phone Number',
        `fax` VARCHAR(30) DEFAULT NULL COMMENT '팩스 번호 / Fax Number',
        `email` VARCHAR(150) DEFAULT NULL COMMENT '기본 이메일 / Primary Email Address',

        -- 4. 배송 주소 정보 (Shipping Address Info: Address 1, Address 2, City/District, State/Province, Country, Postal Code)
        `recipient_name` VARCHAR(100) DEFAULT NULL COMMENT '기본 수령인명 / Default Recipient Name',
        `recipient_phone` VARCHAR(30) DEFAULT NULL COMMENT '수령인 연락처 / Recipient Phone Number',
        `address1` VARCHAR(255) DEFAULT NULL COMMENT '배송 주소1 (기본 도로명/지번 주소) / Address Line 1 (Street Address)',
        `address2` VARCHAR(255) DEFAULT NULL COMMENT '배송 주소2 (상세주소: 동/호수/건물명 등) / Address Line 2 (Apt, Suite, Unit, Building)',
        `city_district` VARCHAR(100) DEFAULT NULL COMMENT '시/구/군 / City, Suburb, or District',
        `state_province` VARCHAR(100) DEFAULT NULL COMMENT '도/주 / State, Province, or Region',
        `country` VARCHAR(100) NOT NULL DEFAULT 'KR' COMMENT '국가 / Country (ISO Code or Name, e.g., KR, AU, US)',
        `postal_code` VARCHAR(20) DEFAULT NULL COMMENT '우편번호 / Postal or ZIP Code',

        -- 5. 주문 및 배송 관리 정보 (Order & Shipping Preferences)
        `price_tier` VARCHAR(50) NOT NULL DEFAULT 'retail' COMMENT '단가 등급 / Pricing Tier (e.g., retail, wholesale, vip, partner_a)',
        `discount_rate` DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT '기본 할인율 (%) / Default Discount Rate (%)',
        `preferred_carrier` VARCHAR(100) DEFAULT NULL COMMENT '선호 배송업체/택배사 / Preferred Shipping Carrier (e.g., CJ, DHL, EMS, FedEx)',
        `shipping_account_no` VARCHAR(100) DEFAULT NULL COMMENT '고객 택배 계정번호 (착불/제3자 청구용) / Carrier Account Number (for Collect/Third-Party Billing)',
        `shipping_fee_type` ENUM('prepaid', 'collect', 'free', 'conditional') NOT NULL DEFAULT 'prepaid' COMMENT '배송비 구분 (선불, 착불, 무료, 조건부무료) / Shipping Fee Type (Prepaid, Collect, Free, Conditional)',
        `shipping_memo` TEXT DEFAULT NULL COMMENT '기본 배송 요청사항 / Default Shipping Instructions & Memo',
        `order_memo` TEXT DEFAULT NULL COMMENT '주문 관련 특이사항 메모 / Default Order Special Notes',

        -- 6. 회계, 결제 및 세무 정보 (Accounting, Billing & Tax Info)
        `tax_invoice_email` VARCHAR(150) DEFAULT NULL COMMENT '세금계산서/인보이스 수신 전용 이메일 / Tax Invoice & Billing Email Address',
        `accounting_manager_name` VARCHAR(100) DEFAULT NULL COMMENT '회계/정산 담당자명 / Accounting Contact Name',
        `accounting_manager_phone` VARCHAR(30) DEFAULT NULL COMMENT '회계/정산 담당자 연락처 / Accounting Contact Phone',
        `payment_terms` VARCHAR(50) NOT NULL DEFAULT 'prepaid' COMMENT '결제 조건 / Payment Terms (prepaid: Prepayment, cod: Cash on Delivery, net15: Net 15, net30: Net 30)',
        `payment_method` VARCHAR(50) DEFAULT 'bank_transfer' COMMENT '주 결제수단 / Primary Payment Method (bank_transfer, card, cash, paypal)',
        `currency` VARCHAR(10) NOT NULL DEFAULT 'KRW' COMMENT '기본 거래 통화 / Default Currency (KRW, USD, AUD, IDR)',
        `tax_type` ENUM('taxable', 'zero_rated', 'exempt') NOT NULL DEFAULT 'taxable' COMMENT '과세 구분 (과세, 영세율/수출, 면세) / Tax Type (Taxable, Zero-Rated/Export, Tax-Exempt)',
        `credit_limit` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT '여신 한도액 (외상 가능 한도) / Credit Limit Amount',
        `outstanding_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT '현재 미수금/미지급 잔액 / Current Outstanding Receivable Balance',
        `deposit_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT '예치금/선입금 잔액 / Current Deposit or Prepayment Balance',
        `bank_name` VARCHAR(100) DEFAULT NULL COMMENT '거래 은행명 (환불/정산용) / Bank Name (for Settlement/Refund)',
        `bank_account_number` VARCHAR(100) DEFAULT NULL COMMENT '계좌번호 (환불/정산용) / Bank Account Number',
        `bank_account_holder` VARCHAR(100) DEFAULT NULL COMMENT '예금주명 / Bank Account Holder Name',
        `swift_code` VARCHAR(50) DEFAULT NULL COMMENT '해외송금용 SWIFT/BIC 또는 BSB 코드 / Bank SWIFT/BIC or BSB Code',

        -- 7. 시스템 및 관리 메타데이터 (System & Admin Metadata)
        `admin_memo` TEXT DEFAULT NULL COMMENT '관리자 전용 내부 메모 / Internal Admin Memo',
        `last_login_at` DATETIME DEFAULT NULL COMMENT '최근 로그인 일시 / Last Login Timestamp',
        `last_order_at` DATETIME DEFAULT NULL COMMENT '최근 주문 일시 / Last Order Timestamp',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시 / Created Timestamp',
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시 / Last Updated Timestamp',

        PRIMARY KEY (`id`),
        UNIQUE KEY `uk_users_login_id` (`login_id`),
        UNIQUE KEY `uk_users_user_code` (`user_code`),
        KEY `idx_users_user_type` (`user_type`),
        KEY `idx_users_company_name` (`company_name`),
        KEY `idx_users_business_number` (`business_number`),
        KEY `idx_users_manager_name` (`manager_name`),
        KEY `idx_users_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='통합 사용자 테이블 (관리자, 거래처, 고객 - 주문/배송/회계 통합) / Integrated Users Table (Manager, Buyer, Customer for Order, Shipping & Accounting)';
    ";

    $conn->exec($sql);
    echo "✅ [성공] 'joomoon.users' 테이블이 영문/한글 설명 및 user_type ENUM('Manager','Buyer','Customer') DEFAULT 'Buyer'로 업데이트되었습니다.\n";

    $stmt = $conn->query("SHOW FULL COLUMNS FROM `users`");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "총 컬럼 수: " . count($columns) . "개\n";
} catch (PDOException $e) {
    echo "❌ [오류] 테이블 업데이트 실패: " . $e->getMessage() . "\n";
    exit(1);
}
?>
