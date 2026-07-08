# PRD: Point of Sale (POS) — Core Features

## 1. Overview

**Feature / Project Name:** Point of Sale Core Module

**Problem Statement:**
Small-to-medium retail and F&B businesses need a fast, reliable way to record sales transactions, manage inventory, and track daily revenue. Manual recording (paper or spreadsheets) is slow, error-prone, and gives owners no real-time visibility into stock levels or sales performance. Cashiers need a checkout flow that is fast enough to use during rush hours without mistakes.

**Proposed Solution:**
A web-based POS application (Laravel 13) that lets cashiers process sales at a register-style checkout screen, automatically deducts stock, records payments, and gives owners/admins tools to manage products, categories, inventory, customers, and view sales reports.

**AI Build Summary:**
> Build a Laravel 13 + Blade/Livewire (or Inertia, per existing stack) web app implementing core POS functionality: product & category management, inventory tracking, a cashier checkout screen (cart, discounts, payment, receipt), transaction history, and basic sales reporting. Follow the project's existing Repository Pattern (`docs/REPOSITORY_PATTERN.md`) and CRUD generator conventions (`docs/CRUD_GENERATOR.md`). Multi-user with roles (Admin, Cashier). No offline support required for MVP. Single-store/single-outlet scope for MVP; multi-outlet is Phase 2. All implementation must follow the project's [CLAUDE.md](../CLAUDE.md) Laravel Boost guidelines (see Section 3, Technical Constraints).

---

## 2. Goals & Success Metrics

**Primary Goal:** Enable a cashier to complete a full sale (add items → apply discount → take payment → print/view receipt) in under 30 seconds per transaction.

**Success Metrics:**
- Average checkout time per transaction < 30s
- Stock levels stay accurate (0 manual reconciliation discrepancies per week in testing)
- Daily sales report reflects 100% of completed transactions in real time

**Anti-goals:**
- Not building multi-outlet/warehouse transfer support in MVP
- Not building full accounting/bookkeeping (ledgers, tax filing) — only sales-level reporting
- Not building a native mobile or offline-first app in MVP
- Not integrating real payment gateways/card processors in MVP (cash & manual "mark as paid" only)

---

## 3. Scope & Constraints

**In scope:**
- Product & category CRUD (with SKU, price, stock, image)
- Inventory tracking (stock in/out, adjustment, low-stock alerts)
- Customer management (optional customer on a sale)
- Cashier checkout (cart, quantity adjust, discount, tax, payment, change calculation)
- Transaction history & receipt view/print
- Basic sales reports (daily/weekly/monthly, best-selling products)
- User roles: Admin, Cashier (leveraging existing `User` model + Fortify auth)

**Out of scope:**
- Multi-outlet / multi-warehouse
- Purchase orders / supplier management
- Loyalty points / promotions engine
- Real payment gateway integrations (Midtrans, Stripe, etc.)
- Barcode hardware/scanner-specific integration (keyboard-wedge input assumed compatible)

**Technical constraints:**
- Platform: Web (desktop-first, responsive for tablet use at register)
- Auth: Laravel Fortify (already installed) — session-based auth, role-gated via policies/middleware
- Accessibility: WCAG AA for admin screens; checkout screen prioritizes speed but must be keyboard-operable
- Offline support: Not required for MVP
- Performance: Checkout screen actions (add to cart, calculate total) must respond in <200ms
- Must follow existing Repository Pattern / CRUD generator (`docs/CRUD_GENERATOR.md`, `docs/REPOSITORY_PATTERN.md`)
- Must follow all [CLAUDE.md](../CLAUDE.md) Laravel Boost guidelines, in particular:
  - Stack versions: PHP 8.4, Laravel 13, Fortify v1, Pest v4, Tailwind v4 (do not introduce other package versions)
  - Prefer `php artisan make:rsc {ModelName}` (CRUD generator) for new entities; only hand-roll Repository/Service/Controller/Requests per `docs/REPOSITORY_PATTERN.md` when the generator doesn't fit
  - No new base folders or dependencies without explicit approval
  - Run `vendor/bin/pint --dirty --format agent` after any PHP changes
  - Tests written with Pest (`php artisan make:test --pest`), factories over manual model setup
  - Commit messages follow `docs/CONVENTIONAL_COMMITS.md`
  - Do not create additional documentation files beyond what's explicitly requested

---

## 4. Jobs to Be Done (JTBD)

| Priority | Job Statement |
|----------|---------------|
| 1 | When a customer is checking out, I want to quickly scan/search items and take payment, so I can serve the next customer without delay. |
| 2 | When stock runs low, I want to be notified, so I can reorder before I run out and lose sales. |
| 3 | When the day ends, I want to see total sales and what sold best, so I can make informed restocking and staffing decisions. |
| 4 | When I add a new product, I want a simple form to set its price, category, and stock, so I can start selling it immediately. |

---

## 5. User Stories

| ID  | Role | Action | Benefit | JTBD Ref |
|-----|------|--------|---------|----------|
| US1 | Cashier | I want to search/add products to a cart | so that I can build up a customer's order quickly | J1 |
| US2 | Cashier | I want to apply a discount and select a payment method | so that I can complete the sale accurately | J1 |
| US3 | Cashier | I want to see the change due after entering cash tendered | so that I can hand back correct change | J1 |
| US4 | Admin | I want to create/edit/delete products and categories | so that the catalog stays accurate | J4 |
| US5 | Admin | I want to adjust stock levels manually (stock-in, correction) | so that inventory matches physical counts | J2 |
| US6 | Admin | I want to see a low-stock alert list | so that I can reorder in time | J2 |
| US7 | Admin | I want to view a sales report by date range | so that I can track business performance | J3 |
| US8 | Admin | I want to view a list of past transactions with details | so that I can investigate a specific sale | J3 |
| US9 | Cashier | I want to attach an existing customer to a sale (optional) | so that we can track repeat customer purchases | J1 |

---

## 6. Proposed Experience

**Design Direction:**
The checkout screen behaves like a register: large touch/click targets, product grid or search-as-you-type on the left, running cart on the right, big "Pay" button. Admin screens (products, categories, reports) follow the existing CRUD generator's table/form pattern already used elsewhere in the app for consistency.

**Key Screens / States:**
- Checkout (POS) screen — product search/grid, cart list, totals, payment panel
- Payment modal — select method (cash/other), enter amount tendered, show change
- Receipt view — post-sale summary, printable/viewable
- Product list/form (index, create, edit) — standard CRUD table
- Category list/form — standard CRUD table
- Inventory adjustment screen — select product, enter adjustment qty + reason
- Customer list/form — standard CRUD table
- Sales report dashboard — date range filter, totals, top products table/chart
- Transaction history — list with filters (date, cashier, status), detail view
- Empty state: "No products yet" on catalog, "No transactions today" on reports
- Error state: insufficient stock warning when adding to cart beyond available quantity; payment amount less than total blocked with inline error
- Loading state: skeleton rows on tables, spinner on payment submission

**Interaction Model:**
- Primary checkout flow: search/select product → adjust qty → (optional) apply discount → click Pay → enter tendered amount → confirm → view/print receipt
- Keyboard shortcuts on checkout: barcode scanner acts as fast keyboard input into search field + Enter to add
- Undo: allow removing a line item from cart before payment is confirmed; no undo after payment is finalized (must use a "void/refund" flow — flag as open question)

**Accessibility Notes:**
- All checkout actions reachable via keyboard (Tab/Enter)
- Sufficient color contrast for stock alerts (not color-only indicators — include icon/text)
- Screen reader labels on cart line items and totals

**Figma / Design Link:** [placeholder — add link when available]

---

## 7. Component Inventory

| Component | Type | Description | Linked Stories |
|-----------|------|-------------|-----------------|
| ProductSearchInput | Form | Search-as-you-type / barcode entry field | US1 |
| ProductGrid | Display | Grid of product cards for quick tap-to-add | US1 |
| CartTable | Display | List of current cart line items with qty controls | US1, US3 |
| CartSummary | Display | Subtotal, discount, tax, total | US2, US3 |
| DiscountInput | Form | Apply percentage/fixed discount to cart or line item | US2 |
| PaymentModal | Modal | Select payment method, enter amount tendered | US2, US3 |
| ChangeDisplay | Display | Shows calculated change due | US3 |
| ReceiptView | Display | Printable transaction receipt | US2, US3 |
| ProductForm | Form | Create/edit product (name, SKU, price, category, stock, image) | US4 |
| ProductTable | Display | Paginated product list w/ filters | US4 |
| CategoryForm | Form | Create/edit category | US4 |
| StockAdjustmentForm | Form | Adjust stock qty with reason | US5 |
| LowStockAlertList | Display | List of products below threshold | US6 |
| CustomerForm | Form | Create/edit customer | US9 |
| CustomerSelect | Form | Autocomplete select for attaching customer to sale | US9 |
| SalesReportFilter | Form | Date range + grouping filter | US7 |
| SalesReportChart | Display | Sales-over-time chart | US7 |
| TopProductsTable | Display | Best-selling products table | US7 |
| TransactionTable | Display | Paginated transaction list w/ filters | US8 |
| TransactionDetailView | Display | Line items, payment, cashier, timestamps of one sale | US8 |
| RoleGuardBanner | Display | Feedback when a cashier attempts an admin-only action | — |

---

## 8. Data Models

```typescript
interface Category {
  id: string;
  name: string;
  createdAt: string;
  updatedAt: string;
}

interface Product {
  id: string;
  categoryId: string;         // FK -> Category
  sku: string;                // unique
  name: string;
  price: number;              // selling price, in smallest currency unit or decimal
  costPrice: number | null;
  stock: number;              // current quantity on hand
  lowStockThreshold: number;  // trigger alert when stock <= this
  imageUrl: string | null;
  isActive: boolean;
  createdAt: string;
  updatedAt: string;
}

interface StockMovement {
  id: string;
  productId: string;          // FK -> Product
  type: 'sale' | 'stock_in' | 'adjustment' | 'return';
  quantityChange: number;     // negative for deductions
  reason: string | null;      // required for 'adjustment'
  userId: string;             // who performed it
  createdAt: string;
}

interface Customer {
  id: string;
  name: string;
  phone: string | null;
  email: string | null;
  createdAt: string;
  updatedAt: string;
}

interface Transaction {
  id: string;
  invoiceNumber: string;      // human-readable, sequential
  cashierId: string;          // FK -> User
  customerId: string | null;  // FK -> Customer
  subtotal: number;
  discountAmount: number;
  taxAmount: number;
  total: number;
  amountTendered: number;
  changeDue: number;
  paymentMethod: 'cash' | 'other';
  status: 'completed' | 'voided';
  createdAt: string;
}

interface TransactionItem {
  id: string;
  transactionId: string;      // FK -> Transaction
  productId: string;          // FK -> Product
  productName: string;        // snapshot at time of sale
  unitPrice: number;          // snapshot at time of sale
  quantity: number;
  discountAmount: number;
  lineTotal: number;
}
```

---

## 9. API / Integration Surface

| Method | Path | Description | Auth Required | Response Shape |
|--------|------|--------------|----------------|-----------------|
| GET | /products | List products (search, filter by category) | Yes | `{ data: Product[], total: number }` |
| POST | /products | Create product | Yes (Admin) | `Product` |
| PATCH | /products/:id | Update product | Yes (Admin) | `Product` |
| DELETE | /products/:id | Delete product | Yes (Admin) | `{ success: boolean }` |
| GET | /categories | List categories | Yes | `{ data: Category[] }` |
| POST | /categories | Create category | Yes (Admin) | `Category` |
| POST | /stock-movements | Record stock adjustment/stock-in | Yes (Admin) | `StockMovement` |
| GET | /stock-movements/low-stock | List products at/below threshold | Yes | `{ data: Product[] }` |
| GET | /customers | List/search customers | Yes | `{ data: Customer[] }` |
| POST | /customers | Create customer | Yes | `Customer` |
| POST | /transactions | Create a completed sale (checkout submit) | Yes (Cashier) | `Transaction` |
| GET | /transactions | List transactions (filter by date, cashier, status) | Yes | `{ data: Transaction[], total: number }` |
| GET | /transactions/:id | Get transaction detail with items | Yes | `Transaction & { items: TransactionItem[] }` |
| POST | /transactions/:id/void | Void a transaction (restock items) | Yes (Admin) | `Transaction` |
| GET | /reports/sales | Aggregated sales report by date range | Yes (Admin) | `{ totalSales: number, totalTransactions: number, topProducts: {productId,name,qty,revenue}[] }` |

**External integrations:**
- None required for MVP (no payment gateway, no printer SDK — browser print dialog used for receipts)

---

## 10. State Management Map

| State | Location | Persistence | Notes |
|-------|----------|-------------|-------|
| Cart items (current sale) | Local UI (component/Livewire state) | Session (cleared on payment confirm or navigate away) | Not persisted to DB until transaction is submitted |
| Product catalog | Server (DB via Repository) | Persistent | Fetched per page load/search |
| Auth/current user + role | Auth context (Fortify session) | Persistent (session) | Drives role-gated UI (Admin vs Cashier) |
| Sales report filters | URL query params | None (per-request) | Shareable/bookmarkable report views |
| Low-stock alert count | Server, cached briefly | Persistent (DB), short cache | Recomputed on stock movement |

---

## 11. Tech Stack Recommendation

| Layer | Choice | Rationale |
|-------|--------|-----------|
| Backend | Laravel 13 (existing) | Already the project foundation |
| Frontend | Blade + Livewire (or existing stack used by CRUD generator) | Matches existing repo conventions; enables reactive checkout UI without a separate SPA |
| Styling | Tailwind CSS v4 (existing) | Already installed per CLAUDE.md |
| Database | MySQL/SQLite (per existing `config/database.php`) | Use whatever the project already configures |
| Auth | Laravel Fortify (existing) | Already installed; extend with role middleware |
| Testing | Pest v4 (existing) | Project convention |

---

## 12. Suggested File Structure

```
app/
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── StockMovement.php
│   ├── Customer.php
│   ├── Transaction.php
│   └── TransactionItem.php
├── Repositories/
│   ├── Product/
│   │   ├── ProductRepositoryInterface.php
│   │   └── ProductRepository.php
│   ├── Category/...
│   ├── Customer/...
│   ├── StockMovement/...
│   └── Transaction/...
├── Services/
│   ├── ProductService.php
│   ├── CategoryService.php
│   ├── InventoryService.php      # stock deduction, adjustment, low-stock check
│   ├── CustomerService.php
│   └── CheckoutService.php       # cart total calc, payment, transaction creation
├── Http/
│   ├── Controllers/
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── CustomerController.php
│   │   ├── CheckoutController.php
│   │   ├── TransactionController.php
│   │   └── ReportController.php
│   └── Requests/
│       ├── Product/StoreProductRequest.php
│       ├── Product/UpdateProductRequest.php
│       └── ...
resources/
└── views/
    ├── checkout/
    │   └── index.blade.php
    ├── products/
    ├── categories/
    ├── customers/
    ├── transactions/
    └── reports/
database/
└── migrations/
    ├── xxxx_create_categories_table.php
    ├── xxxx_create_products_table.php
    ├── xxxx_create_stock_movements_table.php
    ├── xxxx_create_customers_table.php
    ├── xxxx_create_transactions_table.php
    └── xxxx_create_transaction_items_table.php
```

---

## 13. Acceptance Criteria

**US1 — Search/add products to cart**
- [ ] Typing in search filters product list by name or SKU in real time
- [ ] Clicking/tapping a product adds 1 unit to the cart
- [ ] Adding a product already in the cart increments its quantity instead of duplicating the line
- [ ] Edge case: adding more than available stock shows an inline warning and caps quantity at available stock
- [ ] Error state: searching with no matches shows "No products found"

**US2 — Apply discount and select payment method**
- [ ] Discount can be applied as a percentage or fixed amount at cart level
- [ ] Cart total recalculates immediately after discount is applied
- [ ] Payment method selector defaults to "cash"
- [ ] Edge case: discount cannot make total go below 0 (clamped)

**US3 — Change calculation**
- [ ] Entering an amount tendered less than total blocks submission with an inline error
- [ ] Entering amount >= total shows correct change due before confirming
- [ ] Confirming payment creates a Transaction + TransactionItems and deducts stock via StockMovement records

**US4 — Manage products/categories**
- [ ] Admin can create a product with name, SKU (unique), price, category, initial stock
- [ ] Duplicate SKU is rejected with a validation error
- [ ] Deleting a product referenced in past transactions is soft-deleted (not hard-deleted) to preserve transaction history
- [ ] Category must exist before a product can reference it

**US5 — Adjust stock levels**
- [ ] Admin can submit a stock adjustment with quantity delta and required reason text
- [ ] Adjustment creates a StockMovement record of type 'adjustment' and updates Product.stock
- [ ] Negative adjustment cannot bring stock below 0

**US6 — Low-stock alerts**
- [ ] Products with stock <= lowStockThreshold appear in the low-stock list
- [ ] List updates immediately after a sale or adjustment changes stock

**US7 — Sales report**
- [ ] Selecting a date range returns total sales, total transaction count, and top 5 products by revenue for that range
- [ ] Report excludes voided transactions from totals
- [ ] Empty state shown when no transactions exist in the selected range

**US8 — Transaction history**
- [ ] Transaction list is filterable by date range, cashier, and status
- [ ] Clicking a transaction shows full line-item detail, payment info, and cashier
- [ ] Admin can void a completed transaction, which restocks the sold items via new StockMovement records of type 'return'

**US9 — Attach customer to sale**
- [ ] Customer field on checkout is optional
- [ ] Typing searches existing customers by name/phone
- [ ] Sale can be completed with no customer selected

---

## 14. Open Questions & Risks

- **Q:** Should void/refund support partial line-item refunds, or only full-transaction void in MVP? — *Owner: PM*
- **Q:** Is a receipt printer (thermal, ESC/POS) required, or is browser print-to-PDF sufficient for MVP? — *Owner: PM*
- **Risk:** Concurrent sales on the same product could cause stock race conditions — *Mitigation: use DB-level row locking or atomic decrement in InventoryService*
- **Risk:** No payment gateway means no reconciliation against a bank/e-wallet feed — *Mitigation: out of scope for MVP, flagged for Phase 2*
- **Tradeoff:** Single-outlet scope simplifies data model (no per-location stock) but will require a stock model migration if multi-outlet is added later

---

## 15. Rollout & Next Steps

**MVP scope:**
- Includes: Product/category CRUD, inventory tracking with stock movements, checkout flow, transaction history, basic sales report, Admin/Cashier roles
- Excludes: Multi-outlet, payment gateway integration, loyalty/promotions, purchase orders

**Phase 2+ ideas:**
- Multi-outlet inventory
- Supplier & purchase order management
- Payment gateway integrations (QRIS, card)
- Loyalty points / customer promotions
- Barcode label printing

**Sign-off needed from:**
- [ ] PM
- [ ] Engineering lead
- [ ] Design

**Next steps:**
1. Confirm Blade+Livewire vs. existing frontend approach used by CRUD generator — *Owner: Eng lead*
2. Scaffold models/migrations via `php artisan make:rsc` for Product, Category, Customer — *Owner: Eng*
3. Design checkout screen wireframe — *Owner: Design*
