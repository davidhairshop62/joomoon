/**
 * assets/app.js
 * Shared Bilingual (EN-AU / KO-KR) Dictionary, Role Menu Config, and API Helpers for Joomoon OMS
 */

window.JOOMOON_I18N = {
  'en-AU': {
    // Login Screen
    brandSubtitle: 'Global Hair & Order Management System',
    brandTagline: 'Integrated Order, Shipping & Accounting Platform',
    accessTiersTitle: 'ENTERPRISE ACCESS TIERS',
    tierManagerTitle: 'Manager · HQ Administrator',
    tierManagerDesc: 'Full directory & user administration, global order routing, wholesale tier control, and multi-currency reconciliation.',
    tierBuyerTitle: 'Buyer · Wholesale Salon Partner',
    tierBuyerDesc: 'Bulk salon ordering, tiered wholesale pricing, ABN/BRN tax invoice management, and self-service company profile updates.',
    tierCustomerTitle: 'Customer · Direct Client',
    tierCustomerDesc: 'Custom hair specifications, express parcel tracking, personal address book, and direct profile management.',
    globalNodesTitle: 'GLOBAL NODES & COMPLIANCE',
    statusOperational: 'Operational — AU & KR Gateways Active',
    signInTitle: 'Sign In to Portal',
    signInSub: 'Enter your enterprise or client credentials to access your role workspace.',
    labelLoginId: 'Login ID or User Code',
    placeholderLoginId: 'e.g. admin, buyer_sydney, cust_oliver',
    labelPassword: 'Password',
    placeholderPassword: 'Enter password (sample default: 12345678)',
    rememberTerminal: 'Keep me signed in on this device',
    btnSignIn: 'Sign In to Portal →',
    demoSectionTitle: 'QUICK SAMPLE ACCOUNT SWITCHER (1 MANAGER · 3 BUYERS · 5 CUSTOMERS)',
    demoSectionSub: 'Click any sample account below to populate credentials, or double-click to sign in immediately.',
    btnQuickLogin: 'Instant Login',

    // Roles
    role_Manager: 'Manager (HQ Admin)',
    role_Buyer: 'Buyer (Wholesale Partner)',
    role_Customer: 'Customer (Direct Client)',

    // Navigation Menus by Role
    menuHeader_Manager: 'HQ ADMINISTRATION',
    menu_mgr_dashboard: 'Executive Overview',
    menu_mgr_users: 'Users & Partners Management',
    menu_mgr_orders: 'Global Orders & Specs',
    menu_mgr_shipping: 'Shipping & Carrier Accounts',
    menu_mgr_accounting: 'Accounting & Receivables',

    menuHeader_Buyer: 'WHOLESALE PARTNER PORTAL',
    menu_buy_dashboard: 'Partner Landing Overview',
    menu_buy_profile: 'My Company & Profile (Edit)',
    menu_buy_orders: 'Wholesale Bulk Orders',
    menu_buy_shipping: 'Dispatch & Carrier Account',
    menu_buy_accounting: 'Tax Invoices & Credit Ledger',

    menuHeader_Customer: 'DIRECT CLIENT PORTAL',
    menu_cst_dashboard: 'My Landing Overview',
    menu_cst_profile: 'My Profile & Address (Edit)',
    menu_cst_orders: 'My Custom Hair Orders',
    menu_cst_shipping: 'Delivery Tracking',
    menu_cst_accounting: 'Payment & Receipts',

    // Common Header & Actions
    btnSwitchRolePreview: 'Switch Sample Account',
    btnSignOut: 'Sign Out',
    btnGoUsers: 'Manage All Users & Partners',
    btnGoMyProfile: 'Edit My Profile & Address',
    btnNewUser: '+ Add New User',
    btnSaveChanges: 'Save Changes',
    btnDeleteUser: 'Delete User',
    btnResetForm: 'Reset',
    searchPlaceholder: 'Search by code, login ID, company, ABN/BRN, contact, city...',

    // Landing Page (portal.html)
    welcomePrefix: 'Welcome back,',
    kpiTotalUsers: 'Registered Accounts',
    kpiActiveBuyers: 'Wholesale Buyers',
    kpiDirectCustomers: 'Direct Customers',
    kpiAccountBalance: 'Account Credit / Balance',
    kpiPricingTier: 'Assigned Pricing Tier',
    kpiPreferredCarrier: 'Preferred Carrier',
    landingQuickActions: 'ROLE WORKSPACE MODULES',
    landingDirectoryPreview: 'LIVE DATABASE RECORDS (joomoon.users)',
    landingMySummaryTitle: 'MY REGISTERED BUSINESS, SHIPPING & ACCOUNTING SUMMARY',

    // Users & Profile Editor Sections
    pageTitle_Manager: 'Users & Partners Directory Management',
    pageSub_Manager: 'Create, inspect, update, and manage all Manager, Buyer, and Customer accounts in joomoon.users.',
    pageTitle_Self: 'My Profile, Shipping & Accounting Settings',
    pageSub_Self: 'Review your account tier and update your company registration, contact details, delivery address, and banking preferences.',

    tabAll: 'All Users',
    tabManagers: 'Managers',
    tabBuyers: 'Buyers',
    tabCustomers: 'Customers',

    sec1Title: '1. Account & Role Identification',
    sec2Title: '2. Company & Business Registration (ABN / BRN)',
    sec3Title: '3. Contact Person & Representative',
    sec4Title: '4. Shipping Address (Address 1, Address 2, City/District, State, Country, Postcode)',
    sec5Title: '5. Order & Shipping Preferences',
    sec6Title: '6. Accounting, Tax Invoice & Bank Details',
    sec7Title: '7. Internal Admin Memo (Manager Only)',

    // Field Labels
    f_user_code: 'User Code',
    f_user_type: 'User Type (Role)',
    f_login_id: 'Login ID',
    f_password: 'New Password (leave blank to keep current)',
    f_status: 'Account Status',
    f_company_name: 'Company / Business Name',
    f_business_number: 'Business Number (ABN / BRN / Tax ID)',
    f_corporate_number: 'Corporate Number (ACN / Corp ID)',
    f_business_type: 'Business Type (Sector)',
    f_business_item: 'Business Item (Category)',
    f_ceo_name: 'CEO / Representative Name',
    f_manager_name: 'Contact Person / Customer Name',
    f_manager_department: 'Department',
    f_manager_position: 'Position / Job Title',
    f_phone: 'Main Phone',
    f_mobile: 'Mobile Phone',
    f_fax: 'Fax Number',
    f_email: 'Primary Email',
    f_recipient_name: 'Default Recipient Name',
    f_recipient_phone: 'Recipient Phone',
    f_address1: 'Address Line 1 (Street Address)',
    f_address2: 'Address Line 2 (Suite / Unit / Building)',
    f_city_district: 'City / Suburb / District (구/군/시)',
    f_state_province: 'State / Province (도/주)',
    f_country: 'Country (국가)',
    f_postal_code: 'Postal / ZIP Code (우편번호)',
    f_price_tier: 'Price Tier',
    f_discount_rate: 'Default Discount Rate (%)',
    f_preferred_carrier: 'Preferred Shipping Carrier',
    f_shipping_account_no: 'Carrier Account No. (DHL/FedEx Collect)',
    f_shipping_fee_type: 'Shipping Fee Type',
    f_shipping_memo: 'Shipping Instructions / Memo',
    f_order_memo: 'Custom Order Notes / Spec Memo',
    f_tax_invoice_email: 'Tax Invoice Email',
    f_accounting_manager_name: 'Accounting Contact Name',
    f_accounting_manager_phone: 'Accounting Contact Phone',
    f_payment_terms: 'Payment Terms',
    f_payment_method: 'Primary Payment Method',
    f_currency: 'Default Currency',
    f_tax_type: 'Tax Type',
    f_credit_limit: 'Credit Limit',
    f_outstanding_balance: 'Outstanding Balance (Receivable)',
    f_deposit_balance: 'Prepayment / Deposit Balance',
    f_bank_name: 'Bank Name',
    f_bank_account_number: 'Bank Account Number',
    f_bank_account_holder: 'Account Holder Name',
    f_swift_code: 'SWIFT / BIC / BSB Code',
    f_admin_memo: 'Internal Admin Memo',
    readonlyNotice: 'Note: Tier, discount rate, credit limit, and ledger balances are managed by HQ Managers.'
  },

  'ko-KR': {
    // Login Screen
    brandSubtitle: '글로벌 헤어 & 주문·배송·회계 통합 시스템',
    brandTagline: '주문 · 배송 · 회계 통합 비즈니스 플랫폼',
    accessTiersTitle: '사용자 구분별 전용 워크스페이스',
    tierManagerTitle: 'Manager · 총괄 관리자',
    tierManagerDesc: '전체 회원(관리자/거래처/고객) 통합 관리, 글로벌 주문/출고 제어, 단가 등급 및 미수금/예치금 회계 관리',
    tierBuyerTitle: 'Buyer · 거래처 / 바이어',
    tierBuyerDesc: '도매 발주 등록, 약정 할인율 및 여신 한도 조회, 세금계산서/인보이스 관리 및 자사 정보·배송지 직접 수정',
    tierCustomerTitle: 'Customer · 일반/개인 고객',
    tierCustomerDesc: '맞춤 가발 사양 관리, 주문/배송 실시간 조회, 기본 배송지 및 내 프로필 정보 직접 수정',
    globalNodesTitle: '글로벌 거점 및 비즈니스 네트워크',
    statusOperational: '정상 가동 중 — 한국(KR) · 호주(AU) · 미국(US) 연동',
    signInTitle: '포털 로그인',
    signInSub: '부여받은 아이디(Login ID) 또는 회원코드로 로그인하시면 권한별 맞춤 메뉴로 이동합니다.',
    labelLoginId: '로그인 아이디 또는 회원코드',
    placeholderLoginId: '예: admin, buyer_seoul, cust_minsoo',
    labelPassword: '비밀번호',
    placeholderPassword: '비밀번호 입력 (샘플 초기값: 12345678)',
    rememberTerminal: '이 기기에서 로그인 상태 유지',
    btnSignIn: '로그인하여 시작하기 →',
    demoSectionTitle: '샘플 계정 빠른 전환 (Manager 1 · Buyer 3 · Customer 5)',
    demoSectionSub: '아래 샘플 계정을 클릭하면 로그인 정보가 자동 입력되며, 즉시 로그인 버튼으로 바로 체험할 수 있습니다.',
    btnQuickLogin: '즉시 로그인',

    // Roles
    role_Manager: 'Manager (관리자)',
    role_Buyer: 'Buyer (거래처)',
    role_Customer: 'Customer (고객)',

    // Navigation Menus by Role
    menuHeader_Manager: '관리자 전용 메뉴 (MANAGER)',
    menu_mgr_dashboard: '통합 운영 대시보드',
    menu_mgr_users: '회원 및 거래처 관리 (Users)',
    menu_mgr_orders: '전체 주문 및 제작사양 관리',
    menu_mgr_shipping: '배송 및 특송사 계정 관리',
    menu_mgr_accounting: '회계 · 미수금 · 정산 관리',

    menuHeader_Buyer: '거래처 전용 메뉴 (BUYER)',
    menu_buy_dashboard: '거래처 랜딩 대시보드',
    menu_buy_profile: '내 거래처 및 배송정보 수정',
    menu_buy_orders: '도매 주문 등록 및 내역',
    menu_buy_shipping: '출고 조회 및 택배 계정 설정',
    menu_buy_accounting: '세금계산서 · 여신 · 예치금',

    menuHeader_Customer: '고객 전용 메뉴 (CUSTOMER)',
    menu_cst_dashboard: '나의 랜딩 대시보드',
    menu_cst_profile: '내 정보 및 배송지 수정',
    menu_cst_orders: '맞춤 주문 신청 및 내역',
    menu_cst_shipping: '배송 조회 및 수령 요청사항',
    menu_cst_accounting: '결제 내역 및 환불 계좌',

    // Common Header & Actions
    btnSwitchRolePreview: '샘플 계정 빠른 전환',
    btnSignOut: '로그아웃',
    btnGoUsers: '전체 회원/거래처 관리 이동',
    btnGoMyProfile: '내 정보 및 배송지 수정하기',
    btnNewUser: '+ 신규 회원/거래처 등록',
    btnSaveChanges: '변경사항 저장',
    btnDeleteUser: '계정 삭제',
    btnResetForm: '초기화',
    searchPlaceholder: '회원코드, 아이디, 상호명, 사업자번호, 담당자, 시/구/군 검색...',

    // Landing Page (portal.html)
    welcomePrefix: '반갑습니다,',
    kpiTotalUsers: '전체 등록 계정 수',
    kpiActiveBuyers: '등록 거래처 (Buyer)',
    kpiDirectCustomers: '등록 개인고객 (Customer)',
    kpiAccountBalance: '미수금 / 예치금 현황',
    kpiPricingTier: '적용 단가 등급 / 할인율',
    kpiPreferredCarrier: '선호 배송업체 / 조건',
    landingQuickActions: '권한별 주요 업무 바로가기',
    landingDirectoryPreview: '실시간 회원 및 거래처 현황 (joomoon.users)',
    landingMySummaryTitle: '나의 사업자 · 기본 배송지 · 회계 정산 요약 정보',

    // Users & Profile Editor Sections
    pageTitle_Manager: '전체 회원 및 거래처 통합 관리 (Users Management)',
    pageSub_Manager: 'Manager 권한으로 모든 관리자, 거래처(Buyer), 고객(Customer) 계정을 등록·수정·삭제합니다.',
    pageTitle_Self: '내 정보 및 배송·정산 정보 수정 (My Profile)',
    pageSub_Self: '본인의 상호/사업자 정보, 담당자 연락처, 기본 배송지(주소1·주소2·구군·도·국가·우편번호) 및 정산 계좌를 수정할 수 있습니다.',

    tabAll: '전체 보기',
    tabManagers: '관리자 (Manager)',
    tabBuyers: '거래처 (Buyer)',
    tabCustomers: '고객 (Customer)',

    sec1Title: '1. 기본 계정 및 구분 정보',
    sec2Title: '2. 상호 및 사업자등록 정보',
    sec3Title: '3. 대표자 및 담당자 연락처',
    sec4Title: '4. 배송 주소 (주소1, 주소2, 구/군, 도/주, 국가, 우편번호)',
    sec5Title: '5. 주문 및 배송 설정',
    sec6Title: '6. 회계 · 세금계산서 · 정산 계좌 정보',
    sec7Title: '7. 관리자 전용 내부 메모 (Manager 전용)',

    // Field Labels
    f_user_code: '회원/거래처 코드',
    f_user_type: '구분 (Manager / Buyer / Customer)',
    f_login_id: '로그인 아이디',
    f_password: '비밀번호 변경 (미입력 시 기존 유지)',
    f_status: '계정 상태',
    f_company_name: '상호명 (법인명/상호)',
    f_business_number: '사업자등록번호 (또는 ABN/Tax ID)',
    f_corporate_number: '법인등록번호 (또는 ACN)',
    f_business_type: '업태',
    f_business_item: '종목',
    f_ceo_name: '대표자명',
    f_manager_name: '담당자명 (또는 고객 성명)',
    f_manager_department: '담당자 부서',
    f_manager_position: '담당자 직책',
    f_phone: '대표 전화번호',
    f_mobile: '담당자 휴대폰 번호',
    f_fax: '팩스 번호',
    f_email: '기본 이메일',
    f_recipient_name: '기본 수령인명',
    f_recipient_phone: '수령인 연락처',
    f_address1: '배송 주소1 (기본 도로명/지번 주소)',
    f_address2: '배송 주소2 (상세주소: 동/호수/건물명)',
    f_city_district: '시/구/군 (City / Suburb / District)',
    f_state_province: '도/주 (State / Province)',
    f_country: '국가 (Country: KR, AU, US 등)',
    f_postal_code: '우편번호 (Postal / ZIP Code)',
    f_price_tier: '단가 등급',
    f_discount_rate: '기본 할인율 (%)',
    f_preferred_carrier: '선호 배송업체 / 택배사',
    f_shipping_account_no: '고객 택배 계정번호 (착불/DHL 등)',
    f_shipping_fee_type: '배송비 부담 구분',
    f_shipping_memo: '기본 배송 요청사항 메모',
    f_order_memo: '주문 사양 및 특이사항 메모',
    f_tax_invoice_email: '세금계산서/인보이스 이메일',
    f_accounting_manager_name: '회계/정산 담당자명',
    f_accounting_manager_phone: '회계/정산 담당자 연락처',
    f_payment_terms: '결제 조건',
    f_payment_method: '주 결제수단',
    f_currency: '기본 거래 통화',
    f_tax_type: '과세 구분',
    f_credit_limit: '여신 한도액',
    f_outstanding_balance: '현재 미수금 잔액',
    f_deposit_balance: '예치금 / 선입금 잔액',
    f_bank_name: '거래 은행명',
    f_bank_account_number: '계좌번호',
    f_bank_account_holder: '예금주명',
    f_swift_code: '해외송금 SWIFT/BIC 또는 호주 BSB',
    f_admin_memo: '관리자 내부 메모',
    readonlyNotice: '안내: 단가 등급, 할인율, 여신 한도 및 미수금/예치금 잔액은 본사 관리자(Manager)만 변경할 수 있습니다.'
  }
};

window.JoomoonApp = {
  getLang() {
    return localStorage.getItem('joomoon_lang') || 'ko-KR';
  },
  setLang(lang) {
    localStorage.setItem('joomoon_lang', lang);
    document.documentElement.lang = lang;
    window.dispatchEvent(new CustomEvent('joomoon:langchange', { detail: { lang } }));
  },
  t(key) {
    const lang = this.getLang();
    const dict = window.JOOMOON_I18N[lang] || window.JOOMOON_I18N['en-AU'];
    return dict[key] !== undefined ? dict[key] : (window.JOOMOON_I18N['en-AU'][key] || key);
  },
  formatMoney(amount, currency = 'KRW') {
    const num = Number(amount || 0);
    if (currency === 'KRW') {
      return '₩' + num.toLocaleString('en-AU', { maximumFractionDigits: 0 }) + ' KRW';
    }
    if (currency === 'AUD') {
      return 'A$' + num.toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' AUD';
    }
    return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + currency;
  },
  countryFlag(code) {
    const map = { KR: '🇰🇷 KR', AU: '🇦🇺 AU', US: '🇺🇸 US' };
    return map[(code || '').toUpperCase()] || code || '-';
  },
  roleMenus(userType, activePage, activeModule) {
    if (userType === 'Manager') {
      return [
        { id: 'dashboard', href: 'portal.html', icon: 'dashboard', labelKey: 'menu_mgr_dashboard', badge: 'HQ' },
        { id: 'users', href: 'users.html', icon: 'group', labelKey: 'menu_mgr_users', badge: 'CRUD' },
        { id: 'orders', href: 'portal.html?module=orders', icon: 'inventory_2', labelKey: 'menu_mgr_orders', badge: null },
        { id: 'shipping', href: 'portal.html?module=shipping', icon: 'local_shipping', labelKey: 'menu_mgr_shipping', badge: null },
        { id: 'accounting', href: 'portal.html?module=accounting', icon: 'account_balance', labelKey: 'menu_mgr_accounting', badge: null },
      ];
    }
    if (userType === 'Buyer') {
      return [
        { id: 'dashboard', href: 'portal.html', icon: 'space_dashboard', labelKey: 'menu_buy_dashboard', badge: 'B2B' },
        { id: 'users', href: 'users.html', icon: 'business', labelKey: 'menu_buy_profile', badge: 'EDIT' },
        { id: 'orders', href: 'portal.html?module=orders', icon: 'shopping_bag', labelKey: 'menu_buy_orders', badge: null },
        { id: 'shipping', href: 'portal.html?module=shipping', icon: 'flight_takeoff', labelKey: 'menu_buy_shipping', badge: null },
        { id: 'accounting', href: 'portal.html?module=accounting', icon: 'receipt_long', labelKey: 'menu_buy_accounting', badge: null },
      ];
    }
    return [
      { id: 'dashboard', href: 'portal.html', icon: 'home', labelKey: 'menu_cst_dashboard', badge: null },
      { id: 'users', href: 'users.html', icon: 'manage_accounts', labelKey: 'menu_cst_profile', badge: 'EDIT' },
      { id: 'orders', href: 'portal.html?module=orders', icon: 'content_cut', labelKey: 'menu_cst_orders', badge: null },
      { id: 'shipping', href: 'portal.html?module=shipping', icon: 'package_2', labelKey: 'menu_cst_shipping', badge: null },
      { id: 'accounting', href: 'portal.html?module=accounting', icon: 'payments', labelKey: 'menu_cst_accounting', badge: null },
    ];
  }
};
