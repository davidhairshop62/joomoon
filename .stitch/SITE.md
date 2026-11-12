# Joomoon B2B & Hair Order Portal — SITE.md

## 1. Core Identity
- **Project Name**: Joomoon Integrated Order, Shipping & Accounting Portal
- **Stitch Project ID**: `12340825119876237315`
- **Design System ID**: `assets/bbdd21be31994d8da01b7b0cede2c20e` ("Executive Slate & Navy")
- **Mission**: A calm, executive multi-screen web application connecting hair system headquarters (`Manager`), wholesale salon partners (`Buyer`), and individual clients (`Customer`) with bilingual English (Australia) and Korean support.
- **Target Audience**: HQ administrators (`Manager`), Australian/Korean/US salon wholesale buyers (`Buyer`), and direct custom hairpiece customers (`Customer`).
- **Voice**: Calm, trustworthy, structured, executive, internationally accessible (`en-AU` & `ko-KR`).

## 2. Visual Language
- **Vibe**: Calm Corporate, Executive Slate & Navy, Structured Enterprise Clarity.

## 3. Architecture & File Structure
- **Root**: `/Users/davidheonchang/joomoon/` (deployed directly to `/var/www/joomoon/`)
- **Stitch Staging**: `.stitch/designs/{page}.html`
- **Backend API**:
  - `api_auth.php` — Session login, logout, current user verification, demo quick-login
  - `api_users.php` — Role-aware CRUD API (`Manager` can list/search/filter/create/edit/delete all users; `Buyer` & `Customer` can view and update their own full profile, shipping address, order preferences, and accounting info)
- **Frontend Pages**:
  - `index.html` — Executive Login Page with `EN (AU) / 한국어` language switcher & quick sample account login
  - `portal.html` — Role-Based Landing Dashboard & Workspace (`Manager` / `Buyer` / `Customer` dynamic menus)
  - `users.html` — Dedicated User & Partner Management (`Manager`) / Self-Profile Editor (`Buyer` & `Customer`)

## 4. Live Sitemap
- [x] `index` (`index.html`) — Bilingual Executive Login Screen (`en-AU` / `ko-KR`)
- [x] `portal` (`portal.html`) — Role-Based Landing Page & Workspace (Role-specific menus for `Manager`, `Buyer`, `Customer`)
- [x] `users` (`users.html`) — Manager User Directory & Full 53-Column Editor / Buyer & Customer My Profile Editor

## 5. The Roadmap (Backlog)
### Completed
- [x] Generate `index` (Login Screen) via Stitch Loop and wire to `api_auth.php` with `EN (AU) / 한국어` switcher.
- [x] Generate `portal` (Role-Based Landing Dashboard) via Stitch Loop with distinct menus for `Manager`, `Buyer`, and `Customer`.
- [x] Generate `users` (Manager User Management & Buyer/Customer Self-Profile Update) via Stitch Loop and wire to `api_users.php` (`joomoon.users` DB table).

### Next Iteration Backlog
- [ ] `orders` (`orders.html`) — Custom Hair & Wholesale Order Creation & Specification Module
- [ ] `shipping` (`shipping.html`) — Shipment Tracking & Carrier Account Integration (`DHL`, `FedEx`, `CJ`, `Australia Post`)
- [ ] `accounting` (`accounting.html`) — Tax Invoices, Receivables, and Deposit Ledger Viewer

