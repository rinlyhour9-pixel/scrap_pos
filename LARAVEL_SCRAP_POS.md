# Laravel Scrap POS

The Laravel 12 application source is at this workspace root. It is intentionally a single-owner system: one authenticated account and no roles, branches, or staff features.

## Setup

1. Create a MySQL database named `scrap_pos` (or change the `DB_*` values in `.env`).
2. Copy `.env.example` to `.env` and set the database password if needed.
3. Run `composer install` on a machine with package access.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate --seed`.
6. Run `php artisan serve` and open the address shown.

Development login: `admin@example.com` / `password`.

Change this password before using the application outside development.

## Included workflows

- Purchase POS calculates net weight, records a purchase, payment, stock movement, and weighted-average material cost in one database transaction.
- Sales POS prevents overselling, records COGS at the weighted-average cost, and decreases stock atomically.
- Stock cannot change without a stock-movement entry. The inventory screen also supports adjustments, loss, and damage records.
- Dashboard, materials, customer/supplier contacts, purchases, sales receipts, expenses, reports, and responsive desktop/tablet UI are provided.

The existing Flutter maintenance app files were left untouched; the Laravel folders and files are new additions in this same workspace.
