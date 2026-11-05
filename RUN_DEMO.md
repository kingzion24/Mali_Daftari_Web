# Running the Living Water demo

Two processes: the **Laravel API** (PHP backend + data) and the **Astro UI** (the showcase frontend).

## 1. Backend — Laravel API (port 8000)

```bash
php artisan migrate:fresh --seed   # first time, or to reset the demo data
php artisan serve                  # serves the API at http://127.0.0.1:8000
```

## 2. Frontend — Astro UI (port 4321)

```bash
cd frontend
npm install        # first time only
npm run dev        # http://127.0.0.1:4321  ← open this for the walkthrough
```

Open **http://127.0.0.1:4321** — that's the Apple-styled UI the client sees.

## What's where

- **Astro UI** (`frontend/`) — Dashboard, Orders, Container Ledger, Sales Pipeline, Customers, Sales Report. SSR, reads live from the API.
- **Laravel API** (`routes/api.php`, `app/Http/Controllers/Api/DemoApiController.php`) — JSON for every screen.
- **Filament admin** (`http://127.0.0.1:8000/admin`, login `demo@livingwater.co.tz` / `demo1234`) — behind-the-scenes data entry; not the client-facing UI.

The product is a single returnable SKU: **Living Water 20L Dispenser Bottle**. Order and invoice numbers auto-generate.
