
### Step 1: Set Up the Development Environment & Project Skeleton
- Install **XAMPP** (or WAMP/MAMP) with PHP 8.1+ and MySQL 8.0+.
<!-- - Create the project folder `ubrip/` inside your web root. -->
<!-- - Copy the complete folder structure from **Section 4** of the SRS. Use the terminal/VS Code to generate the directories and empty placeholder files. -->
- Place the provided `.htaccess`, `composer.json`, `.env.example`, and `.gitignore` in the root.
- Run `composer install` to pull in the required PHP packages (dotenv, JWT, OAuth2).
- Copy `.env.example` to `.env` and fill in the database credentials, app URL, and a random `APP_SECRET` / `JWT_SECRET`.
- Start Apache & MySQL from the XAMPP control panel.

---

### Step 2: Create the Database & Seed with Kolkata Data
- Execute the full **schema.sql** from **Section 5.10** (run `mysql -u root -p < database/schema.sql`). This will create the `ubrip` database and all 8 tables, including the new `cities` table.
- Execute the **seed.sql** from **Section 5.11** to populate:
  - Two cities (*Kolkata* and *New York* as a placeholder)
  - 14 Kolkata bus stops with real coordinates
  - 5 Kolkata bus routes (S-1D, C8, 12C, etc.)
  - Fare slabs for the first route
  - One `super_admin` user (`admin@ubrip.in`)
- Verify with MySQL Workbench or CLI that all tables exist and data is inserted correctly.

---

### Step 3: Build the Core Backend (Database, Router & First Models)
- Implement the **Database singleton** from **Section 7.2** (`src/Core/Database.php`) – exactly as written.
- Create a simple **Router** (`src/Core/Router.php`) that parses the URL and calls the right controller method (for now, just map `/` to `HomeController::index()`).
- Write the **City model** (**Section 7.6**) and the **Route model** (**Section 7.7**) with the multi‑city methods (`allForCity`, `findWithStops`, `search`). Test them manually with a small PHP script or via the interactive PHP shell.
- Set up `config/config.php` to load the `.env` variables using `vlucas/phpdotenv`.
- At this point, you should be able to fetch all active routes for Kolkata directly from the database using PHP.

---

### Step 4: Assemble the Public Frontend (Layout, Search, Route Detail & Map)
- Create the base **HTML template** from **Section 8.7** (`views/layout/header.php` and `footer.php`), integrating Bootstrap 5, Google Fonts (Rajdhani + Noto Sans), and the custom CSS from the colour system (**Section 8.2**).
- Build the **homepage** (`views/home/index.php`): add the city selector dropdown (populated from the `cities` table), the search bar, and a list of routes from the active city.
- Implement **route detail** (`views/routes/detail.php`): display the info grid (timings, frequency, type badge), the stop‑sequence timeline, and the fare table – all using data fetched from `$route['stops']` and `$route['fares']`.
- Add the **Leaflet map** panel (**Section 8.8**):
  - Include Leaflet CSS/JS in the header.
  - In `assets/js/map.js`, initialise the map, draw a polyline through all stop coordinates, add numbered stop markers, and animate a bus icon moving along the route.  
  - Use the free OpenStreetMap tiles (no API key required).  
- Make sure the page works without JavaScript (progressive enhancement – show a static list of stops inside a `<noscript>` tag).

---

### Step 5: Implement Google OAuth Login, Admin Panel & Route Planning API
- **Google OAuth2**:
  - Follow the Google Cloud Console setup in **Section 3.4**.
  - Implement the `AuthService` (**Section 7.3**) and the `AuthController` (login, redirect, callback).
  - Apply the `AuthMiddleware` to protect all `/admin/*` routes.
- **Admin CRUD**:
  - Build the admin dashboard and controllers for cities, routes, stops, and fares (as per **Section 4** and **6.2**).
  - Temporarily, the seeded `super_admin` can log in and start managing Kolkata data.
- **Dijkstra Path Planner**:
  - Implement the `GraphService` from **Section 7.8**.
  - Create the `PlannerController` and the `/planner` page where users pick a “From” and “To” stop.
  - Expose the `/api/{city}/plan?from=…&to=…` endpoint so the frontend can display the suggested path.
- **Test** everything locally: search a route, watch the map animation, log in as admin, create a new route, and use the path planner.

---