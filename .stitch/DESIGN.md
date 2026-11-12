# Design System: Joomoon B2B & Hair Order Portal

## 1. Visual Theme & Atmosphere
A calm, authoritative, executive B2B portal aesthetic ("Executive Slate & Navy"). Designed for cross-border hair system manufacturers, wholesale salon buyers, and private clients across Australia, Korea, and the US. The layout balances clean enterprise density with warm, tactile surface clarity. Bilingual support (English AU `en-AU` and Korean `ko-KR`) is a first-class citizen, with a prominent language switcher in the header.

## 2. Color Palette & Roles
- **Alabaster Mist** (`#F8FAFC`) — Primary application background surface
- **Pure Surface** (`#FFFFFF`) — Elevated card, table, and modal container fill
- **Deep Executive Navy** (`#0F172A`) — Sidebar surface, primary headings, and structural anchor
- **Calm Corporate Blue** (`#1E3A5F`) — Primary brand accent for CTAs, active tabs, and focus rings
- **Charcoal Slate** (`#1E293B`) — Primary body text
- **Muted Steel** (`#64748B`) — Secondary labels, bilingual captions, metadata
- **Hairline Border** (`#E2E8F0`) — 1px structural dividers, table borders, and form input outlines
- **Status Emerald** (`#059669`) — Active status badges, positive balance indicators
- **Status Amber** (`#D97706`) — Pending/Collect shipping indicators, credit warnings

## 3. Typography Rules
- **Display & Headings:** `Plus Jakarta Sans` / `Pretendard` — Clean, composed geometric sans-serif with tight tracking (`-0.02em`) and weight-driven hierarchy (`600`–`700`).
- **Body & UI Controls:** `Plus Jakarta Sans` / `Pretendard` — High legibility for both English (AU) and Korean scripts (`14px`–`15px` base).
- **Monospace:** `JetBrains Mono` — Used for User Codes (`MGR-001`, `BUY-001`, `CST-001`), ABN/Business Numbers, Postal Codes, and Currency Amounts (`AUD`, `KRW`, `USD`).

## 4. Component Stylings
- **Buttons:** Refined 8px border-radius (`0.5rem`), solid Deep Corporate Blue (`#1E3A5F`) for primary actions, subtle slate outline (`#CBD5E1`) for secondary actions. Tactile `-1px` press feedback.
- **Cards & Panels:** 12px border-radius (`0.75rem`), crisp `1px solid #E2E8F0` border, subtle ambient shadow (`0 1px 3px rgba(15,23,42,0.04)`).
- **Inputs & Forms:** Structured business forms grouped into clear sections (Account, Business/ABN, Contact, Shipping Address, Order/Shipping Preferences, Accounting & Bank). Labels sit cleanly above inputs with clear focus rings.
- **Role Badges:** Distinct semantic pills for `Manager` (Navy `#1E3A5F`), `Buyer` (Teal `#0F766E`), and `Customer` (Slate `#475569`).

## 5. Layout Principles
- **Login Screen (`index.html`):** Split-screen executive layout on desktop (Left: brand identity, global operations overview, quick demo account selector; Right: clean login card with top-right `EN (AU) / 한국어` language toggle).
- **Portal Shell (`portal.html`):** Left fixed executive sidebar (`260px` width in `#0F172A`) with role-specific navigation menus + top utility bar showing current user badge, role, currency/country badge, and `EN (AU) | 한국어` language toggle + main workspace container.

## 6. Design System Notes for Stitch Generation
**DESIGN SYSTEM (REQUIRED):**
- **Theme**: Calm, composed Executive B2B Portal ("Executive Slate & Navy"). No neon gradients, no purple glows.
- **Colors**: Background `#F8FAFC`, Cards `#FFFFFF`, Sidebar `#0F172A`, Primary Accent `#1E3A5F` (Calm Corporate Blue), Text `#1E293B`, Muted `#64748B`, Border `#E2E8F0`.
- **Typography**: Plus Jakarta Sans & Pretendard for clean English (AU) and Korean bilingual clarity; JetBrains Mono for codes (`MGR-001`, `BUY-002`), ABN/Tax IDs, and currency figures (`AUD`, `KRW`, `USD`).
- **Language Support**: Prominent `EN (AU) | 한국어` language switcher in the top header bar.
- **Components**: 8px rounded buttons, 12px rounded cards with `1px solid #E2E8F0` borders, structured enterprise data tables and multi-section business profile forms.

