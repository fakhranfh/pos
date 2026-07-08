# Product

## Register

product

## Users

Two roles: **Admins** who manage the product catalog, stock, customers, and sales reports; and **Cashiers** who run register-style checkout (search/add products, apply discounts, take payment, calculate change). Small-to-medium retail and F&B business owners/staff, often on a shared in-store terminal, needing speed and low error tolerance during a live transaction.

## Product Purpose

A web-based Point of Sale system (Laravel 13) for single-outlet retail/F&B businesses: product & inventory management, customer records, cashier checkout, transaction history, and sales reporting. MVP scope — cash/manual payment only, no payment gateway, no offline support. Success = a cashier can complete a sale in seconds with no training beyond a walkthrough.

## Brand Personality

Trustworthy & efficient. Solid, dependable software for people handling real money and real inventory — not flashy, not playful. Confidence through clarity: clean numbers, unambiguous states, fast interactions.

## Anti-references

Generic AI-SaaS landing page tropes (gradient text, tiny uppercase eyebrows, hero-metric-with-gradient-accent cards, cream/sand backgrounds, glassmorphism). Also avoid anything that reads as consumer/social (the previous "FeedLoop" scroll-feed design language inherited from the boilerplate template does not apply here).

## Design Principles

- Numbers and state must be unambiguous at a glance (stock, totals, change due) — clarity over decoration.
- Design for a shared in-store terminal: large touch targets, fast contrast, minimal cognitive load under time pressure.
- Show the real product (checkout, inventory, reporting), not abstract feature icons.
- Keep the visual language consistent between the marketing landing page and the authenticated app.

## Accessibility & Inclusion

WCAG AA contrast minimum. Respect `prefers-reduced-motion`. Minimum 44px touch targets for interactive elements (cashiers may use tablets/touchscreens).
