---
name: POS
colors:
  background: 'oklch(98% 0.004 255)'
  on-background: 'oklch(22% 0.02 255)'
  surface: 'oklch(99% 0.002 255)'
  surface-container-lowest: 'oklch(100% 0 0)'
  surface-container-low: 'oklch(97% 0.005 255)'
  surface-container: 'oklch(95% 0.007 255)'
  surface-tint: 'oklch(45% 0.16 255)'
  on-surface: 'oklch(22% 0.02 255)'
  on-surface-variant: 'oklch(42% 0.02 255)'
  outline: 'oklch(56% 0.015 255)'
  outline-variant: 'oklch(87% 0.008 255)'
  primary: 'oklch(45% 0.16 255)'
  on-primary: 'oklch(99% 0 0)'
  primary-container: 'oklch(91% 0.04 255)'
  on-primary-container: 'oklch(28% 0.09 255)'
  primary-fixed-variant: 'oklch(30% 0.1 255)'
  secondary: 'oklch(45% 0.02 255)'
  on-secondary: 'oklch(99% 0 0)'
  success: 'oklch(56% 0.15 145)'
  success-container: 'oklch(93% 0.05 145)'
  on-success-container: 'oklch(28% 0.08 145)'
  warning: 'oklch(66% 0.15 75)'
  error: 'oklch(55% 0.2 25)'
  error-container: 'oklch(93% 0.05 25)'
  on-error-container: 'oklch(30% 0.1 25)'
  info: 'oklch(55% 0.12 240)'
typography:
  headline-lg:
    fontFamily: Instrument Sans
    fontSize: 30px
    fontWeight: '600'
    lineHeight: 36px
  headline-md:
    fontFamily: Instrument Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Instrument Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Instrument Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Instrument Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Instrument Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
  label-md:
    fontFamily: Instrument Sans
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Instrument Sans
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.05em
spacing:
  space-xxs: 0.25rem
  space-xs: 0.5rem
  space-sm: 0.75rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
  gutter: 1rem
---

# POS Design System

## Overview

Design system for a Laravel-based Point of Sale app targeting small-to-medium retail/F&B businesses. Two audiences: admins managing catalog/stock/reports, and cashiers running checkout on a shared in-store terminal. The system prioritizes clarity and speed over decoration — numbers, totals, and stock states must be unambiguous at a glance.

## Colors

- **Primary** (deep blue, oklch 45% 0.16 255): primary actions, links, active states — signals trust and reliability.
- **Secondary** (neutral slate): metadata, secondary actions.
- **Success / Warning / Error / Info**: transaction and stock-state feedback (payment confirmed, low stock, failed action).
- **Surface / Background**: near-white neutrals, minimal chroma — keeps focus on data, not chrome.

## Do's and Don'ts

- Do keep interactive touch targets at minimum 44px (cashiers often use touchscreens).
- Do use success/error/warning consistently for transaction and stock states — never repurpose them decoratively.
- Don't use gradients, glassmorphism, or decorative motion on data-heavy screens (checkout, inventory, reports).
- Don't drop contrast below WCAG AA for any text on surface/background.
