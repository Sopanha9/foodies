# Laravel Migration Plan

## Current status

- Legacy PHP project remains in repository root.
- New Laravel app is scaffolded in the laravel directory.
- Core entities, routes, controllers, and Blade placeholders are in place.

## Migration sequence

1. Database first

- Move existing schema to Laravel migrations (done as baseline).
- Add seeders for categories, menu items, and admin account.

2. Customer flow

- Migrate login/register/account from legacy scripts into Laravel auth.
- Replace cart and checkout logic with Laravel controllers and form requests.

3. Admin flow

- Migrate admin login, categories, menu management, and orders pages.
- Add middleware and role checks.

4. API and frontend hardening

- Introduce service layer for order placement and payment callbacks.
- Add request validation, policies, and tests.

5. Cutover

- Point web root to laravel/public.
- Archive legacy PHP entry points once all modules are validated.
