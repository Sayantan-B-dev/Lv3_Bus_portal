# Universal Bus Route Information Portal (v2.0)

A high-performance, city-agnostic bus information system built with PHP 8.2 and MySQL 8.0. Designed for industrial clarity and transit efficiency.

## 🚀 Quick Setup

1. **Prerequisites:**
   - XAMPP / WAMP with PHP 8.1+ and MySQL 8.0+.
   - [Composer](https://getcomposer.org/) installed.

2. **Installation:**
   ```bash
   git clone https://github.com/Sayantan-B-dev/Lv3_Bus_portal.git
   cd bus-portal
   composer install
   ```

3. **Environment:**
   - Copy `.env.example` to `.env`.
   - Update `DB_NAME`, `DB_USER`, `DB_PASS` (usually empty for XAMPP root).
   - Set `APP_URL` to `http://localhost/bus-portal/public` (or your vhost).

4. **Database:**
   - Import `database/schema.sql` into phpMyAdmin.
   - Import `database/seed_kolkata.sql` for demo data.

5. **Web Server:**
   - Point your DocumentRoot to the `public/` directory.
   - Ensure `mod_rewrite` is enabled in Apache.

## 🛠️ Tech Stack
- **Backend:** Core PHP (MVC Architecture), PDO Singleton, JWT, Google OAuth 2.0.
- **Frontend:** HTML5, Vanilla CSS (Modern Industrial Dark), JS (ES6+), Bootstrap 5.
- **GIS:** Leaflet.js, OpenStreetMap (Dark Matter tiles), Nominatim API, Overpass API.
- **Algorithm:** Custom Dijkstra's Shortest Path for Journey Planning.

## 📂 Project Structure
- `api/`: RESTful API entry point.
- `config/`: App configuration and bootstrapping.
- `public/`: Web root (Assets + Index).
- `src/`: Core logic (Controllers, Models, Services, Middleware).
- `views/`: UI templates (PHP/HTML).

## 🔑 Admin Access
- Navigate to `/auth/login`.
- Login with Google (requires `GOOGLE_CLIENT_ID` in `.env`).
- Manage Routes, Stops, and Fares via the Dashboard.

---
*Built with ❤️ for Kolkata and beyond.*
