<?php
/**
 * setup_users_table.php
 * config.php를 이용하여 joomoon 데이터베이스에 users 테이블을 생성합니다.
 * 구분: 관리자(admin), 거래처(partner), 고객(customer)
 * 포함 항목: 기본 계정, 사업자/상호 정보, 배송지 주소(주소1, 주소2, 구군, 도, 국가, 우편번호),
 *          담당자/대표자 정보, 주문/배송 설정, 회계/정산/세금계산서 관련 필드
 */

require_once __DIR__ . '/config.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `users` (
        -- 1. 기본 식별 및 계정 정보
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '고유 ID (PK)',
        `user_code` VARCHAR(30) DEFAULT NULL COMMENT '회원/거래처 코드 (예: ADM-001, PTN-001, CST-001)',
        `user_type` ENUM('관리자', '거래처', '고객') NOT NULL DEFAULT '고객' COMMENT '사용자 구분 (관리자, 거래처, 고객)',
        `login_id` VARCHAR(100) NOT NULL COMMENT '로그인 아이디',
        `password_hash` VARCHAR(255) NOT NULL COMMENT '비밀번호 해시',
        `status` ENUM('active', 'inactive', 'suspended', 'pending') NOT NULL DEFAULT 'active' COMMENT '계정 상태 (정상, 비활성, 정지, 승인대기)',

        -- 2. 상호 및 사업자 정보 (거래처/사업자 고객용)
        `company_name` VARCHAR(150) DEFAULT NULL COMMENT '상호명 (법인명/상호)',
        `business_number` VARCHAR(30) DEFAULT NULL COMMENT '사업자등록번호 (또는 ABN/Tax ID)',
        `corporate_number` VARCHAR(30) DEFAULT NULL COMMENT '법인등록번호',
        `business_type` VARCHAR(100) DEFAULT NULL COMMENT '업태',
        `business_item` VARCHAR(100) DEFAULT NULL COMMENT '종목',
        `ceo_name` VARCHAR(100) DEFAULT NULL COMMENT '대표자명',

        -- 3. 담당자 및 연락처 정보
        `manager_name` VARCHAR(100) DEFAULT NULL COMMENT '담당자명 (또는 고객 성명)',
        `manager_department` VARCHAR(100) DEFAULT NULL COMMENT '담당자 부서',
        `manager_position` VARCHAR(100) DEFAULT NULL COMMENT '담당자 직책',
        `phone` VARCHAR(30) DEFAULT NULL COMMENT '대표 전화번호',
        `mobile` VARCHAR(30) DEFAULT NULL COMMENT '담당자 휴대폰 번호',
        `fax` VARCHAR(30) DEFAULT NULL COMMENT '팩스 번호',
        `email` VARCHAR(150) DEFAULT NULL COMMENT '기본 이메일',

        -- 4. 배송 주소 정보 (주소1, 주소2, 구군, 도, 국가, 우편번호)
        `recipient_name` VARCHAR(100) DEFAULT NULL COMMENT '기본 수령인명',
        `recipient_phone` VARCHAR(30) DEFAULT NULL COMMENT '수령인 연락처',
        `address1` VARCHAR(255) DEFAULT NULL COMMENT '배송 주소1 (기본 도로명/지번 주소)',
        `address2` VARCHAR(255) DEFAULT NULL COMMENT '배송 주소2 (상세주소: 동/호수/건물명 등)',
        `city_district` VARCHAR(100) DEFAULT NULL COMMENT '시/구/군 (City / Suburb / District)',
        `state_province` VARCHAR(100) DEFAULT NULL COMMENT '도/주 (State / Province)',
        `country` VARCHAR(100) NOT NULL DEFAULT 'KR' COMMENT '국가 (Country, 예: KR, AU, US 등)',
        `postal_code` VARCHAR(20) DEFAULT NULL COMMENT '우편번호 (Zip / Postal Code)',

        -- 5. 주문 및 배송 관리 정보 (Order & Shipping)
        `price_tier` VARCHAR(50) NOT NULL DEFAULT 'retail' COMMENT '단가 등급 (예: retail, wholesale, vip, partner_a)',
        `discount_rate` DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT '기본 할인율 (%)',
        `preferred_carrier` VARCHAR(100) DEFAULT NULL COMMENT '선호 배송업체/택배사 (예: CJ대한통운, DHL, EMS, 우체국 등)',
        `shipping_account_no` VARCHAR(100) DEFAULT NULL COMMENT '배송비 착불/제3자 청구용 고객 택배 계정번호 (DHL/FedEx 등)',
        `shipping_fee_type` ENUM('prepaid', 'collect', 'free', 'conditional') NOT NULL DEFAULT 'prepaid' COMMENT '배송비 구분 (선불, 착불, 무료, 조건부무료)',
        `shipping_memo` TEXT DEFAULT NULL COMMENT '기본 배송 요청사항 / 배송 메모',
        `order_memo` TEXT DEFAULT NULL COMMENT '주문 관련 특이사항 메모',

        -- 6. 회계, 결제 및 세무 정보 (Accounting & Billing)
        `tax_invoice_email` VARCHAR(150) DEFAULT NULL COMMENT '세금계산서/인보이스 수신 전용 이메일',
        `accounting_manager_name` VARCHAR(100) DEFAULT NULL COMMENT '회계/정산 담당자명',
        `accounting_manager_phone` VARCHAR(30) DEFAULT NULL COMMENT '회계/정산 담당자 연락처',
        `payment_terms` VARCHAR(50) NOT NULL DEFAULT 'prepaid' COMMENT '결제 조건 (prepaid: 선결제, cod: 착불/대금상환, net15: 15일후, net30: 익월말 등)',
        `payment_method` VARCHAR(50) DEFAULT 'bank_transfer' COMMENT '주 결제수단 (bank_transfer, card, cash, paypal 등)',
        `currency` VARCHAR(10) NOT NULL DEFAULT 'KRW' COMMENT '기본 거래 통화 (KRW, USD, AUD, IDR 등)',
        `tax_type` ENUM('taxable', 'zero_rated', 'exempt') NOT NULL DEFAULT 'taxable' COMMENT '과세 구분 (과세/부가세별도, 영세율/수출, 면세)',
        `credit_limit` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT '여신 한도액 (외상 가능 한도)',
        `outstanding_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT '현재 미수금 / 미지급 잔액',
        `deposit_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT '예치금 / 선입금 잔액',
        `bank_name` VARCHAR(100) DEFAULT NULL COMMENT '거래 은행명 (환불/정산용)',
        `bank_account_number` VARCHAR(100) DEFAULT NULL COMMENT '계좌번호 (환불/정산용)',
        `bank_account_holder` VARCHAR(100) DEFAULT NULL COMMENT '예금주명',
        `swift_code` VARCHAR(50) DEFAULT NULL COMMENT '해외송금용 SWIFT/BIC 또는 BSB 코드',

        -- 7. 시스템 및 관리 메타데이터
        `admin_memo` TEXT DEFAULT NULL COMMENT '관리자 전용 내부 메모',
        `last_login_at` DATETIME DEFAULT NULL COMMENT '최근 로그인 일시',
        `last_order_at` DATETIME DEFAULT NULL COMMENT '최근 주문 일시',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',

        PRIMARY KEY (`id`),
        UNIQUE KEY `uk_users_login_id` (`login_id`),
        UNIQUE KEY `uk_users_user_code` (`user_code`),
        KEY `idx_users_user_type` (`user_type`),
        KEY `idx_users_company_name` (`company_name`),
        KEY `idx_users_business_number` (`business_number`),
        KEY `idx_users_manager_name` (`manager_name`),
        KEY `idx_users_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='통합 사용자 테이블 (관리자, 거래처, 고객 - 주문/배송/회계 통합)';
    ";

    $conn->exec($sql);
    echo "✅ [성공] 'joomoon' 데이터베이스에 'users' 테이블이 정상적으로 생성되었습니다.\n";

    // 생성된 컬럼 목록 확인 출력
    $stmt = $conn->query("SHOW FULL COLUMNS FROM `users`");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "총 컬럼 수: " . count($columns) . "개\n";
} catch (PDOException $e) {
    echo "❌ [오류] 테이블 생성 실패: " . $e->getMessage() . "\n";
    exit(1);
}
?>
