# Investment Dashboard (PHP)

Web dashboard to view and update assets. Uses PHP and MySQL on InfinityFree.

## Setup on InfinityFree

1. In **cPanel → MySQL Databases**, note:
   - **MySQL host** (e.g. `sql123.infinityfree.com`) and put it in `config.php` as `db_host`.
   - Database name: `if0_41335119_investdash`
   - MySQL username and password (use the same as in `config.php`).

2. Copy `config.example.php` to `config.php` if you prefer to start from the example. Edit `config.php`:
   - `db_host` – MySQL host from cPanel
   - `db_name` – `if0_41335119_investdash`
   - `db_user` – your MySQL username (e.g. `if0_41335119`)
   - `db_pass` – your MySQL password

3. Upload the project into your hosting account (e.g. `htdocs` or the folder that maps to `investdash2026.great-site.net`).

4. Open `https://investdash2026.great-site.net/` (or the path where you uploaded `index.php`).

## Structure

- `index.php` – list all assets and total HKD
- `edit.php?id=<ID>` – view and update one asset (all fields)
- `config.php` – database credentials (do not commit; use `config.example.php` as template)
- `includes/db.php` – PDO connection
- `static/style.css` – styles

## Database

Table `asset`: ID, type, description, amt, exch_rate, qty, amt_hkd.
