# Universal Bus Route Information Portal — SRS v2.0

> **Evolved from DTC (Delhi) → Universal City Bus Portal**
> Kolkata-seeded demo data. Any city selectable at runtime (Kolkata, Delhi, New York, London, etc.)
> Version 2.0 | April 2025 | Status: Ready for Development

| Field | Value |
|---|---|
| Document Type | Software Requirements Specification |
| Project Name | Universal Bus Route Information Portal |
| Version | 2.0 |
| Base City (Demo Seed) | Kolkata, West Bengal, India |
| Status | Final — Ready for Development |
| Classification | Internal — Open Source |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Overall System Description](#2-overall-system-description)
3. [Pre-Installation Requirements](#3-pre-installation-requirements)
4. [Project Structure](#4-project-structure)
5. [Database Design](#5-database-design)
6. [URL Routes & API Endpoints](#6-url-routes--api-endpoints)
7. [Core PHP Implementation](#7-core-php-implementation)
8. [Frontend Design Requirements](#8-frontend-design-requirements)
9. [Advanced Features: Maps, Algorithms & Dynamic Data](#9-advanced-features-maps-algorithms--dynamic-data)
10. [Security Requirements](#10-security-requirements)
11. [Testing Requirements](#11-testing-requirements)
12. [Deployment Guide](#12-deployment-guide)
13. [Appendix](#13-appendix)

---

## 1. Introduction

### 1.1 Purpose

This SRS provides a complete, authoritative description of the Universal Bus Route Information Portal. Any developer — beginner or expert — who reads this document should be able to build the entire system without further clarification.

### 1.2 Project Overview

A web-based public-facing application allowing citizens of **any city worldwide** to search, browse, and explore bus routes. Users can:

- Search routes by stop, number, or neighborhood
- View stop sequences, timings, frequency, fares
- See live animated maps with route polylines
- Get shortest-path suggestions between two stops (Dijkstra-based)
- Switch city/region dynamically from the UI — no page reload

The demo seed data is **Kolkata, West Bengal** but the system is city-agnostic by design.

### 1.3 Scope

- Public portal for viewing bus route information
- Admin dashboard for managing routes, stops, fares
- OAuth 2.0 (Google) for admin auth
- RESTful PHP backend with MySQL
- Responsive frontend: HTML, CSS (custom + Bootstrap 5), JavaScript
- OpenStreetMap + Leaflet.js for free, unlimited map integration
- OpenRouteService API for free routing/directions
- Overpass API for fetching real stop data from OpenStreetMap
- Dijkstra algorithm for shortest-path routing between stops
- City selector — runtime switch between any city worldwide
- Full API layer for future mobile app integration

### 1.4 Definitions

| Term | Definition |
|---|---|
| SRS | Software Requirements Specification |
| OAuth | Open Authorization |
| JWT | JSON Web Token |
| PDO | PHP Data Objects |
| CSRF | Cross-Site Request Forgery |
| XSS | Cross-Site Scripting |
| MVC | Model-View-Controller |
| AC | Air-Conditioned bus |
| REST | Representational State Transfer |
| CRUD | Create, Read, Update, Delete |
| OSM | OpenStreetMap — free open map data |
| ORS | OpenRouteService — free routing API |
| Overpass | OSM query API for fetching geo data |
| Dijkstra | Graph shortest-path algorithm used for route planning |
| GTFS | General Transit Feed Specification — universal transit data format |

### 1.5 Design Philosophy: Universal First

**The system must never hardcode a city.** Everything city-specific (stops, routes, fare rules, currency, timezone) comes from the database `cities` table or from live OSM/API calls. A user in New York and a user in Kolkata see the same UI — different data.

---

## 2. Overall System Description

### 2.1 Product Perspective

Standalone web application. Three-tier architecture:

- **Presentation Layer** — HTML/CSS/JS (Bootstrap 5 + Leaflet.js)
- **Application Layer** — PHP 8.x
- **Data Layer** — MySQL 8.x

External free services used:

| Service | Purpose | Cost |
|---|---|---|
| OpenStreetMap (OSM) | Base map tiles | Free forever |
| Leaflet.js | Map rendering library | Free, MIT |
| Overpass API | Fetch real bus stops from OSM | Free |
| OpenRouteService API | Turn-by-turn routing, isochrones | Free tier: 2000 req/day |
| Nominatim | City geocoding (city name → lat/lng) | Free |
| GTFS feeds (optional) | Official transit data for major cities | Free for most cities |

### 2.2 User Classes

| User Class | Description | Access Level |
|---|---|---|
| Public User | Any citizen searching bus routes | Read-only public pages |
| Admin | Transit staff managing route data | Full CRUD on all data |
| Super Admin | System administrator | All features + user management |
| Developer | REST API consumer | API endpoints only |

### 2.3 Operating Environment

| Component | Specification |
|---|---|
| Web Server | Apache 2.4+ or Nginx 1.18+ |
| PHP Version | PHP 8.1+ (8.2 recommended) |
| Database | MySQL 8.0+ or MariaDB 10.6+ |
| PHP Extensions | PDO, PDO_MySQL, mbstring, json, openssl, curl, session |
| Browser Support | Chrome 100+, Firefox 100+, Safari 15+, Edge 100+ |
| Min Server RAM | 2 GB RAM, 1 vCPU |
| Recommended | 4 GB RAM, 2 vCPU |
| SSL | Required in production |

### 2.4 Constraints

- PHP 8.x + MySQL only — no Node.js/Python backend
- Bootstrap 5 + Leaflet.js — no React/Vue/Angular
- All DB queries via PDO prepared statements
- Google OAuth 2.0 for admin login
- No third-party PHP frameworks (no Laravel/Symfony)
- Core public pages must work without JavaScript (progressive enhancement)
- Map must use **free** tile providers only (OSM/CartoDB)

### 2.5 Assumptions

- Server has internet access (for OAuth callbacks, Overpass API, OSM tiles)
- Admin team seeds city data initially; system auto-enriches from OSM
- OpenRouteService free API key provisioned for routing

---

## 3. Pre-Installation Requirements

### 3.1 Required Software

| Software | Version | Purpose | URL |
|---|---|---|---|
| XAMPP / WAMP / MAMP | 8.2+ | Local PHP + MySQL + Apache | https://www.apachefriends.org |
| PHP | 8.1 or 8.2 | Backend language | Included in XAMPP |
| MySQL | 8.0+ | Database | Included in XAMPP |
| Composer | 2.x | PHP dependency manager | https://getcomposer.org |
| Git | 2.x | Version control | https://git-scm.com |
| VS Code | Latest | IDE | https://code.visualstudio.com |
| MySQL Workbench | 8.0 | DB GUI | https://www.mysql.com/products/workbench/ |
| Postman | Latest | API testing | https://www.postman.com |

### 3.2 VS Code Extensions

- PHP Intelephense
- MySQL (query runner)
- Prettier
- GitLens
- Live Server
- Bootstrap 5 Snippets
- Thunder Client

### 3.3 Required Accounts & Free API Keys

| Account | Purpose | URL |
|---|---|---|
| Google Cloud Console | OAuth 2.0 credentials | https://console.cloud.google.com |
| GitHub | Version control | https://github.com |
| OpenRouteService | Free routing API key (2000 req/day) | https://openrouteservice.org |
| Nominatim | No key needed — free geocoding | https://nominatim.org |
| Overpass API | No key needed — free OSM data | https://overpass-api.de |

> **No Google Maps API key needed.** All mapping uses Leaflet.js + OpenStreetMap (free, no billing).

### 3.4 Google OAuth Setup

1. Go to https://console.cloud.google.com → New Project → name it `Bus Route Portal`
2. APIs & Services → OAuth consent screen → External → fill app name, email
3. Credentials → Create Credentials → OAuth client ID → Web application
4. Authorized redirect URIs:
   - `http://localhost/bus-portal/auth/google/callback` (dev)
   - `https://yourdomain.com/auth/google/callback` (prod)
5. Copy Client ID and Client Secret → paste into `.env`

### 3.5 Composer Packages

```bash
composer install
```

```json
{
  "require": {
    "php": ">=8.1",
    "vlucas/phpdotenv": "^5.5",
    "firebase/php-jwt": "^6.8",
    "league/oauth2-google": "^4.0"
  },
  "autoload": {
    "psr-4": { "App\\": "src/" }
  }
}
```

---

## 4. Project Structure

```
bus-portal/
├── public/                      # Web root — Apache DocumentRoot
│   ├── index.php                # Entry point / home page
│   ├── .htaccess                # URL rewriting + security headers
│   └── assets/
│       ├── css/
│       │   ├── main.css         # Custom styles (see Section 8)
│       │   ├── map.css          # Map panel styles
│       │   └── admin.css        # Admin panel styles
│       ├── js/
│       │   ├── main.js          # Public portal JS
│       │   ├── search.js        # Search autocomplete
│       │   ├── map.js           # Leaflet map + animations
│       │   ├── dijkstra.js      # Client-side graph shortest path
│       │   ├── city-selector.js # Dynamic city switching
│       │   └── admin.js         # Admin panel JS
│       └── img/
│           └── logo.svg
│
├── src/                         # PHP application source
│   ├── Core/
│   │   ├── Database.php         # PDO singleton
│   │   ├── Router.php           # URL router
│   │   ├── Request.php          # HTTP request abstraction
│   │   ├── Response.php         # HTTP response abstraction
│   │   └── Session.php          # Session manager
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── RouteController.php
│   │   ├── SearchController.php
│   │   ├── PlannerController.php  # Journey planner (Dijkstra)
│   │   ├── CityController.php     # City switching + OSM import
│   │   ├── AuthController.php
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── AdminRouteController.php
│   │       ├── AdminStopController.php
│   │       ├── AdminFareController.php
│   │       └── AdminCityController.php
│   ├── Models/
│   │   ├── City.php
│   │   ├── Route.php
│   │   ├── Stop.php
│   │   ├── RouteStop.php
│   │   ├── Fare.php
│   │   └── User.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   ├── CsrfMiddleware.php
│   │   └── RateLimitMiddleware.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── SearchService.php
│   │   ├── MapService.php           # Leaflet/OSM integration
│   │   ├── OverpassService.php      # Fetch stops from OSM
│   │   ├── RoutingService.php       # OpenRouteService API calls
│   │   ├── DijkstraService.php      # Server-side Dijkstra
│   │   ├── GtfsImportService.php    # Import GTFS feed for a city
│   │   └── CityResolverService.php  # Nominatim geocoding
│   └── Helpers/
│       ├── Validator.php
│       ├── Sanitizer.php
│       └── Pagination.php
│
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── admin-layout.php
│   ├── home/
│   │   └── index.php
│   ├── routes/
│   │   ├── list.php
│   │   └── detail.php
│   ├── search/
│   │   └── results.php
│   ├── planner/
│   │   └── index.php              # Journey planner UI
│   ├── auth/
│   │   └── login.php
│   └── admin/
│       ├── dashboard.php
│       ├── routes/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       ├── stops/
│       │   └── index.php
│       ├── fares/
│       │   └── index.php
│       └── cities/
│           └── index.php
│
├── api/
│   ├── index.php                  # API router
│   ├── routes.php
│   ├── route.php
│   ├── search.php
│   ├── stops.php
│   ├── cities.php
│   ├── planner.php                # Dijkstra journey planner endpoint
│   └── osm-import.php             # Trigger OSM stop import
│
├── config/
│   └── config.php
├── database/
│   ├── schema.sql
│   ├── seed_kolkata.sql           # Kolkata demo data
│   └── migrations/
├── storage/
│   └── logs/
├── tests/
│   ├── RouteTest.php
│   ├── DijkstraTest.php
│   └── AuthTest.php
├── .env
├── .env.example
├── .gitignore
└── composer.json
```

---

## 5. Database Design

### 5.1 Entity Relationship Overview

8 core tables:

- `cities` — master list of supported cities
- `routes` — bus routes (belong to a city)
- `stops` — bus stops (belong to a city)
- `route_stops` — junction: which stops are on which route, in order
- `fares` — fare slabs per route
- `users` — admin users
- `oauth_tokens` — Google OAuth tokens
- `activity_log` — audit trail

### 5.2 Table: cities

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | City ID |
| city_name | VARCHAR(100) | NOT NULL | e.g. Kolkata |
| state_region | VARCHAR(100) | NULLABLE | e.g. West Bengal |
| country | VARCHAR(100) | NOT NULL DEFAULT 'India' | Country name |
| country_code | CHAR(2) | NOT NULL DEFAULT 'IN' | ISO 3166-1 alpha-2 |
| currency | VARCHAR(10) | NOT NULL DEFAULT 'INR' | Currency code |
| timezone | VARCHAR(60) | NOT NULL DEFAULT 'Asia/Kolkata' | IANA timezone |
| center_lat | DECIMAL(10,7) | NOT NULL | Map center latitude |
| center_lng | DECIMAL(10,7) | NOT NULL | Map center longitude |
| default_zoom | TINYINT | DEFAULT 12 | Leaflet default zoom |
| osm_relation_id | BIGINT | NULLABLE | OSM relation ID for auto-import |
| is_active | TINYINT(1) | DEFAULT 1 | Show in city selector |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### 5.3 Table: routes

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | Route ID |
| city_id | INT UNSIGNED | FK cities.id, NOT NULL | City this route belongs to |
| route_number | VARCHAR(20) | NOT NULL | e.g. S-12, 45A, N-7 |
| source | VARCHAR(120) | NOT NULL | Origin stop name |
| destination | VARCHAR(120) | NOT NULL | Terminal stop name |
| route_type | ENUM | NOT NULL | 'AC','Express','Normal','Night','Mini' |
| frequency_mins | TINYINT UNSIGNED | NOT NULL | Minutes between buses |
| first_bus_time | TIME | NOT NULL | First departure |
| last_bus_time | TIME | NOT NULL | Last departure |
| total_distance_km | DECIMAL(6,2) | NOT NULL | Route length |
| description | TEXT | NULLABLE | |
| osm_relation_id | BIGINT | NULLABLE | OSM route relation ID |
| is_active | TINYINT(1) | DEFAULT 1 | Soft delete |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | |

> **Unique constraint:** `(city_id, route_number)` — same route number can exist in different cities.

### 5.4 Table: stops

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | Stop ID |
| city_id | INT UNSIGNED | FK cities.id, NOT NULL | City this stop belongs to |
| stop_name | VARCHAR(150) | NOT NULL | Full stop name |
| stop_code | VARCHAR(20) | NULLABLE | Short code |
| latitude | DECIMAL(10,7) | NULLABLE | GPS latitude |
| longitude | DECIMAL(10,7) | NULLABLE | GPS longitude |
| landmark | VARCHAR(200) | NULLABLE | Nearby landmark |
| zone | VARCHAR(50) | NULLABLE | Fare zone |
| osm_node_id | BIGINT | NULLABLE | OSM node ID (for enrichment) |
| is_terminal | TINYINT(1) | DEFAULT 0 | Major terminal flag |
| is_active | TINYINT(1) | DEFAULT 1 | |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

> **Unique constraint:** `(city_id, stop_name)`

### 5.5 Table: route_stops (Junction)

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | |
| route_id | INT UNSIGNED | FK routes.id, CASCADE | |
| stop_id | INT UNSIGNED | FK stops.id, RESTRICT | |
| stop_order | TINYINT UNSIGNED | NOT NULL | Position (1, 2, 3...) |
| distance_from_start_km | DECIMAL(6,2) | NOT NULL | km from first stop |
| arrival_time_offset_mins | SMALLINT UNSIGNED | NULLABLE | Minutes from first stop |
| is_major_stop | TINYINT(1) | DEFAULT 0 | |

> **Unique constraints:** `(route_id, stop_order)` and `(route_id, stop_id)`

### 5.6 Table: fares

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | |
| route_id | INT UNSIGNED | FK routes.id, CASCADE | |
| min_km | DECIMAL(5,2) | NOT NULL | |
| max_km | DECIMAL(5,2) | NOT NULL | |
| fare_amount | DECIMAL(6,2) | NOT NULL | In city's currency |
| passenger_type | ENUM | DEFAULT 'General' | 'General','Student','Senior' |

### 5.7 Table: users

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | |
| google_id | VARCHAR(100) | UNIQUE, NULLABLE | |
| name | VARCHAR(150) | NOT NULL | |
| email | VARCHAR(200) | UNIQUE, NOT NULL | |
| avatar_url | VARCHAR(500) | NULLABLE | |
| role | ENUM | DEFAULT 'viewer' | 'super_admin','admin','viewer' |
| is_active | TINYINT(1) | DEFAULT 1 | |
| last_login_at | TIMESTAMP | NULLABLE | |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### 5.8 Table: oauth_tokens

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | |
| user_id | INT UNSIGNED | FK users.id, CASCADE | |
| access_token | TEXT | NOT NULL | Encrypted at rest |
| refresh_token | TEXT | NULLABLE | |
| token_type | VARCHAR(50) | DEFAULT 'Bearer' | |
| expires_at | DATETIME | NOT NULL | |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### 5.9 Table: activity_log

| Column | Type | Constraints | Description |
|---|---|---|---|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | |
| user_id | INT UNSIGNED | FK users.id, SET NULL | |
| action | VARCHAR(100) | NOT NULL | e.g. 'route.create' |
| entity_type | VARCHAR(50) | NULLABLE | 'route','stop','fare' |
| entity_id | INT UNSIGNED | NULLABLE | |
| details | JSON | NULLABLE | |
| ip_address | VARCHAR(45) | NULLABLE | |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### 5.10 Complete SQL Schema

```sql
-- database/schema.sql
-- Run: mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS bus_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bus_portal;

CREATE TABLE cities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  city_name VARCHAR(100) NOT NULL,
  state_region VARCHAR(100) NULL,
  country VARCHAR(100) NOT NULL DEFAULT 'India',
  country_code CHAR(2) NOT NULL DEFAULT 'IN',
  currency VARCHAR(10) NOT NULL DEFAULT 'INR',
  timezone VARCHAR(60) NOT NULL DEFAULT 'Asia/Kolkata',
  center_lat DECIMAL(10,7) NOT NULL,
  center_lng DECIMAL(10,7) NOT NULL,
  default_zoom TINYINT DEFAULT 12,
  osm_relation_id BIGINT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_country (country_code)
) ENGINE=InnoDB;

CREATE TABLE routes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  city_id INT UNSIGNED NOT NULL,
  route_number VARCHAR(20) NOT NULL,
  source VARCHAR(120) NOT NULL,
  destination VARCHAR(120) NOT NULL,
  route_type ENUM('AC','Express','Normal','Night','Mini') NOT NULL DEFAULT 'Normal',
  frequency_mins TINYINT UNSIGNED NOT NULL DEFAULT 20,
  first_bus_time TIME NOT NULL,
  last_bus_time TIME NOT NULL,
  total_distance_km DECIMAL(6,2) NOT NULL DEFAULT 0.00,
  description TEXT NULL,
  osm_relation_id BIGINT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
  UNIQUE KEY uq_city_route (city_id, route_number),
  INDEX idx_route_type (route_type),
  INDEX idx_source (source),
  INDEX idx_destination (destination)
) ENGINE=InnoDB;

CREATE TABLE stops (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  city_id INT UNSIGNED NOT NULL,
  stop_name VARCHAR(150) NOT NULL,
  stop_code VARCHAR(20) NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  landmark VARCHAR(200) NULL,
  zone VARCHAR(50) NULL,
  osm_node_id BIGINT NULL,
  is_terminal TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
  UNIQUE KEY uq_city_stop (city_id, stop_name),
  INDEX idx_zone (zone),
  INDEX idx_coords (latitude, longitude)
) ENGINE=InnoDB;

CREATE TABLE route_stops (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  route_id INT UNSIGNED NOT NULL,
  stop_id INT UNSIGNED NOT NULL,
  stop_order TINYINT UNSIGNED NOT NULL,
  distance_from_start_km DECIMAL(6,2) NOT NULL DEFAULT 0.00,
  arrival_time_offset_mins SMALLINT UNSIGNED NULL,
  is_major_stop TINYINT(1) DEFAULT 0,
  FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE,
  FOREIGN KEY (stop_id) REFERENCES stops(id) ON DELETE RESTRICT,
  UNIQUE KEY uq_route_stop_order (route_id, stop_order),
  UNIQUE KEY uq_route_stop (route_id, stop_id)
) ENGINE=InnoDB;

CREATE TABLE fares (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  route_id INT UNSIGNED NOT NULL,
  min_km DECIMAL(5,2) NOT NULL,
  max_km DECIMAL(5,2) NOT NULL,
  fare_amount DECIMAL(6,2) NOT NULL,
  passenger_type ENUM('General','Student','Senior') DEFAULT 'General',
  FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE,
  INDEX idx_route_fare (route_id, passenger_type)
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  google_id VARCHAR(100) NULL,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL,
  avatar_url VARCHAR(500) NULL,
  role ENUM('super_admin','admin','viewer') DEFAULT 'viewer',
  is_active TINYINT(1) DEFAULT 1,
  last_login_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_email (email),
  UNIQUE KEY uq_google_id (google_id)
) ENGINE=InnoDB;

CREATE TABLE oauth_tokens (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  access_token TEXT NOT NULL,
  refresh_token TEXT NULL,
  token_type VARCHAR(50) DEFAULT 'Bearer',
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_token (user_id)
) ENGINE=InnoDB;

CREATE TABLE activity_log (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(50) NULL,
  entity_id INT UNSIGNED NULL,
  details JSON NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_action (action),
  INDEX idx_entity (entity_type, entity_id)
) ENGINE=InnoDB;
```

### 5.11 Seed Data — Kolkata (Demo)

```sql
-- database/seed_kolkata.sql
USE bus_portal;

-- CITIES (seed several for city-selector demo)
INSERT INTO cities (city_name, state_region, country, country_code, currency, timezone, center_lat, center_lng, default_zoom, osm_relation_id) VALUES
('Kolkata', 'West Bengal', 'India', 'IN', 'INR', 'Asia/Kolkata', 22.5726, 88.3639, 13, 3609492),
('Delhi', 'Delhi', 'India', 'IN', 'INR', 'Asia/Kolkata', 28.6139, 77.2090, 12, 1942586),
('Mumbai', 'Maharashtra', 'India', 'IN', 'INR', 'Asia/Kolkata', 19.0760, 72.8777, 12, 7888811),
('New York', 'New York', 'United States', 'US', 'USD', 'America/New_York', 40.7128, -74.0060, 12, 175905),
('London', 'England', 'United Kingdom', 'GB', 'GBP', 'Europe/London', 51.5074, -0.1278, 12, 65606);

-- KOLKATA STOPS (city_id = 1)
INSERT INTO stops (city_id, stop_name, stop_code, latitude, longitude, landmark, is_terminal) VALUES
(1, 'Esplanade', 'ESL01', 22.5626, 88.3510, 'Esplanade Metro Station', 1),
(1, 'Howrah Station', 'HWH01', 22.5839, 88.3423, 'Howrah Railway Station', 1),
(1, 'Sealdah', 'SDL01', 22.5651, 88.3700, 'Sealdah Railway Station', 1),
(1, 'Gariahat', 'GHT01', 22.5204, 88.3631, 'Gariahat Market', 0),
(1, 'Tollygunge', 'TLY01', 22.4993, 88.3523, 'Tollygunge Metro Station', 1),
(1, 'Behala Chowrasta', 'BHC01', 22.4976, 88.3130, 'Behala Chowrasta Crossing', 0),
(1, 'Jadavpur', 'JDP01', 22.4974, 88.3714, 'Jadavpur University', 0),
(1, 'Ultadanga', 'ULT01', 22.5783, 88.3924, 'Ultadanga Bridge', 0),
(1, 'Salt Lake Sector V', 'SLK05', 22.5726, 88.4382, 'IT Hub Salt Lake', 1),
(1, 'Ruby', 'RBY01', 22.5127, 88.3963, 'Ruby Hospital', 0),
(1, 'Science City', 'SCC01', 22.5366, 88.3947, 'Science City Kolkata', 0),
(1, 'Park Street', 'PKS01', 22.5539, 88.3527, 'Park Street', 0),
(1, 'Ballygunge', 'BLG01', 22.5274, 88.3648, 'Ballygunge Station', 0),
(1, 'Shyambazar', 'SYB01', 22.5916, 88.3722, 'Shyambazar 5-Point Crossing', 0),
(1, 'Dunlop', 'DLP01', 22.6436, 88.3748, 'Dunlop Bridge', 1),
(1, 'Dum Dum', 'DMD01', 22.6260, 88.3984, 'Dum Dum Airport Area', 1),
(1, 'Airport Gate 1', 'APT01', 22.6547, 88.4467, 'Netaji Subhas Chandra Bose Airport', 1),
(1, 'Barasat', 'BRT01', 22.7221, 88.4768, 'Barasat Bus Stand', 1),
(1, 'Garia', 'GRA01', 22.4622, 88.3898, 'Garia Station', 1),
(1, 'New Alipore', 'NAL01', 22.5198, 88.3319, 'New Alipore Post Office', 0),
(1, 'Kalighat', 'KLG01', 22.5264, 88.3468, 'Kalighat Temple', 0),
(1, 'Rashbehari', 'RSB01', 22.5193, 88.3570, 'Rashbehari Crossing', 0),
(1, 'Deshapriya Park', 'DPK01', 22.5235, 88.3600, 'Deshapriya Park', 0),
(1, 'Hatibagan', 'HTB01', 22.5820, 88.3658, 'Hatibagan Market', 0),
(1, 'College Street', 'CLS01', 22.5777, 88.3614, 'Presidency College', 0);

-- KOLKATA ROUTES (city_id = 1)
INSERT INTO routes (city_id, route_number, source, destination, route_type, frequency_mins, first_bus_time, last_bus_time, total_distance_km) VALUES
(1, 'S-12', 'Howrah Station', 'Salt Lake Sector V', 'AC', 10, '06:00:00', '22:00:00', 22.50),
(1, '45A', 'Esplanade', 'Garia', 'Normal', 15, '05:30:00', '21:30:00', 18.30),
(1, '230', 'Dunlop', 'Tollygunge', 'Express', 20, '06:00:00', '22:00:00', 27.40),
(1, 'AC-46', 'Airport Gate 1', 'Esplanade', 'AC', 30, '05:00:00', '23:30:00', 31.60),
(1, 'N-7', 'Esplanade', 'Barasat', 'Night', 60, '23:00:00', '04:30:00', 34.20),
(1, '6', 'Howrah Station', 'Jadavpur', 'Normal', 10, '05:00:00', '22:30:00', 14.80),
(1, 'S-9', 'Gariahat', 'Sealdah', 'AC', 12, '06:30:00', '21:00:00', 8.70),
(1, '215', 'Shyambazar', 'Behala Chowrasta', 'Normal', 18, '05:30:00', '22:00:00', 19.50);

-- ROUTE STOPS for Route 1 (S-12: Howrah → Salt Lake Sector V)
INSERT INTO route_stops (route_id, stop_id, stop_order, distance_from_start_km, arrival_time_offset_mins, is_major_stop) VALUES
(1, 2, 1, 0.00, 0, 1),
(1, 1, 2, 4.20, 12, 1),
(1, 12, 3, 6.80, 22, 0),
(1, 3, 4, 9.50, 32, 1),
(1, 8, 5, 13.20, 45, 0),
(1, 11, 6, 17.60, 58, 0),
(1, 9, 7, 22.50, 72, 1);

-- FARES for Route 1 (S-12, AC)
INSERT INTO fares (route_id, min_km, max_km, fare_amount, passenger_type) VALUES
(1, 0, 5, 12.00, 'General'),
(1, 5, 10, 18.00, 'General'),
(1, 10, 20, 25.00, 'General'),
(1, 20, 100, 35.00, 'General'),
(1, 0, 5, 6.00, 'Student'),
(1, 5, 100, 12.00, 'Student'),
(1, 0, 5, 6.00, 'Senior'),
(1, 5, 100, 12.00, 'Senior');

-- ADMIN USER
INSERT INTO users (name, email, role, is_active) VALUES
('Portal Admin', 'admin@busportal.in', 'super_admin', 1);
```

---

## 6. URL Routes & API Endpoints

### 6.1 Public Web Routes

| Method | URL Pattern | Controller | Action | Description |
|---|---|---|---|---|
| GET | / | HomeController | index | Homepage with city selector + search |
| GET | /routes | RouteController | list | All routes for selected city |
| GET | /routes/{id} | RouteController | detail | Single route detail + map |
| GET | /search | SearchController | results | Search results |
| GET | /planner | PlannerController | index | Journey planner UI |
| POST | /planner/find | PlannerController | find | Dijkstra shortest path |
| GET | /auth/login | AuthController | login | Admin login page |
| GET | /auth/google | AuthController | googleRedirect | Google OAuth redirect |
| GET | /auth/google/callback | AuthController | googleCallback | OAuth callback |
| POST | /auth/logout | AuthController | logout | Logout |

### 6.2 Admin Web Routes (Auth Required)

| Method | URL Pattern | Controller | Action |
|---|---|---|---|
| GET | /admin | DashboardController | index |
| GET | /admin/routes | AdminRouteController | index |
| GET | /admin/routes/create | AdminRouteController | create |
| POST | /admin/routes | AdminRouteController | store |
| GET | /admin/routes/{id}/edit | AdminRouteController | edit |
| POST | /admin/routes/{id} | AdminRouteController | update |
| POST | /admin/routes/{id}/delete | AdminRouteController | destroy |
| GET | /admin/stops | AdminStopController | index |
| POST | /admin/stops | AdminStopController | store |
| POST | /admin/stops/import-osm | AdminStopController | importFromOsm |
| GET | /admin/fares/{routeId} | AdminFareController | index |
| POST | /admin/fares | AdminFareController | store |
| GET | /admin/cities | AdminCityController | index |
| POST | /admin/cities | AdminCityController | store |

### 6.3 REST API Endpoints

> All API endpoints return JSON. Set header: `Accept: application/json`

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| GET | /api/cities | No | All active cities |
| GET | /api/routes?city_id=1 | No | Routes for a city |
| GET | /api/routes?city_id=1&type=AC | No | Filtered by type |
| GET | /api/routes/{id} | No | Route + stops + fares |
| GET | /api/search?q={query}&city_id=1 | No | Full-text search |
| GET | /api/stops?city_id=1 | No | All stops for a city |
| GET | /api/stops/{id} | No | Stop + routes through it |
| POST | /api/planner | No | Dijkstra journey plan |
| GET | /api/stops/nearby?lat=&lng=&radius= | No | Stops within radius |
| POST | /api/routes | Bearer JWT | Create route |
| PUT | /api/routes/{id} | Bearer JWT | Update route |
| DELETE | /api/routes/{id} | Bearer JWT | Soft delete |
| POST | /api/osm/import-stops | Bearer JWT | Import stops from Overpass |

### 6.4 API Response Format

```json
// Success
{
  "status": "success",
  "data": { },
  "meta": { "page": 1, "per_page": 20, "total": 150, "city": "Kolkata" }
}

// Error
{
  "status": "error",
  "message": "Route not found",
  "code": 404
}
```

### 6.5 Journey Planner API

```
POST /api/planner
Content-Type: application/json

{
  "city_id": 1,
  "from_stop_id": 2,
  "to_stop_id": 9,
  "passenger_type": "General"
}
```

Response:

```json
{
  "status": "success",
  "data": {
    "algorithm": "dijkstra",
    "total_distance_km": 22.5,
    "estimated_time_mins": 72,
    "transfers": 0,
    "fare": 35.00,
    "currency": "INR",
    "legs": [
      {
        "route_id": 1,
        "route_number": "S-12",
        "route_type": "AC",
        "board_stop": "Howrah Station",
        "alight_stop": "Salt Lake Sector V",
        "stops_count": 7,
        "distance_km": 22.5,
        "time_mins": 72
      }
    ]
  }
}
```

---

## 7. Core PHP Implementation

### 7.1 Environment Configuration (.env)

```env
# .env — NEVER commit to Git

APP_NAME=Bus Route Portal
APP_ENV=development
APP_URL=http://localhost/bus-portal
APP_SECRET=your-random-32-char-secret-key-here

DB_HOST=localhost
DB_PORT=3306
DB_NAME=bus_portal
DB_USER=root
DB_PASS=your_db_password
DB_CHARSET=utf8mb4

GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost/bus-portal/auth/google/callback

# Free — get at openrouteservice.org
ORS_API_KEY=your-openrouteservice-api-key

SESSION_LIFETIME=7200
JWT_SECRET=another-random-32-char-secret-key
JWT_EXPIRY=3600

# No Maps API key needed — using OpenStreetMap (free)
```

### 7.2 Database Singleton (src/Core/Database.php)

```php
<?php
namespace App\Core;
use PDO; use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $_ENV['DB_HOST'], $_ENV['DB_PORT'],
                $_ENV['DB_NAME'], $_ENV['DB_CHARSET']);
            try {
                self::$instance = new PDO($dsn,
                    $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log('DB Connection failed: ' . $e->getMessage());
                http_response_code(503);
                die(json_encode(['status'=>'error','message'=>'Service unavailable']));
            }
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
```

### 7.3 Auth Service — Google OAuth 2.0 (src/Services/AuthService.php)

```php
<?php
namespace App\Services;
use Firebase\JWT\JWT; use Firebase\JWT\Key;
use App\Core\Database;

class AuthService {
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct() {
        $this->clientId     = $_ENV['GOOGLE_CLIENT_ID'];
        $this->clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
        $this->redirectUri  = $_ENV['GOOGLE_REDIRECT_URI'];
    }

    public function getGoogleAuthUrl(): string {
        $params = http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $this->generateState(),
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ]);
        return 'https://accounts.google.com/o/oauth2/auth?' . $params;
    }

    public function exchangeCode(string $code): array {
        $response = $this->httpPost(
            'https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ]);
        return json_decode($response, true);
    }

    public function getGoogleUser(string $accessToken): array {
        $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
        ]);
        $result = curl_exec($ch); curl_close($ch);
        return json_decode($result, true);
    }

    public function findOrCreateUser(array $googleUser): array {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE google_id = ?');
        $stmt->execute([$googleUser['sub']]);
        $user = $stmt->fetch();
        if (!$user) {
            $stmt = $db->prepare(
                'INSERT INTO users (google_id,name,email,avatar_url) VALUES (?,?,?,?)'
            );
            $stmt->execute([
                $googleUser['sub'], $googleUser['name'],
                $googleUser['email'], $googleUser['picture'] ?? null,
            ]);
            $userId = $db->lastInsertId();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        }
        return $user;
    }

    public function generateJWT(array $user): string {
        $payload = [
            'iss'   => $_ENV['APP_URL'],
            'sub'   => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'iat'   => time(),
            'exp'   => time() + (int)$_ENV['JWT_EXPIRY'],
        ];
        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }

    public function validateJWT(string $token): ?array {
        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) { return null; }
    }

    private function generateState(): string {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;
        return $state;
    }

    private function httpPost(string $url, array $data): string {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $r = curl_exec($ch); curl_close($ch); return $r;
    }
}
```

### 7.4 Auth Middleware (src/Middleware/AuthMiddleware.php)

```php
<?php
namespace App\Middleware;
use App\Services\AuthService;

class AuthMiddleware {
    public static function handle(): array {
        $authService = new AuthService();
        $token = null;

        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            $token = substr($header, 7);
        }
        if (!$token && isset($_SESSION['admin_token'])) {
            $token = $_SESSION['admin_token'];
        }
        if (!$token) { self::unauthorized(); }

        $payload = $authService->validateJWT($token);
        if (!$payload) { self::unauthorized(); }

        return $payload;
    }

    private static function unauthorized(): never {
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'Unauthorized']);
        } else {
            header('Location: /auth/login');
        }
        exit;
    }
}
```

### 7.5 CSRF Middleware (src/Middleware/CsrfMiddleware.php)

```php
<?php
namespace App\Middleware;

class CsrfMiddleware {
    public static function generateToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validate(): void {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die(json_encode(['status'=>'error','message'=>'CSRF token mismatch']));
        }
    }
}
```

### 7.6 Route Model (src/Models/Route.php)

```php
<?php
namespace App\Models;
use App\Core\Database;

class Route {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function allByCity(int $cityId, array $filters = [], int $page = 1, int $perPage = 20): array {
        $sql = 'SELECT * FROM routes WHERE city_id = ? AND is_active = 1';
        $params = [$cityId];

        if (!empty($filters['type'])) {
            $sql .= ' AND route_type = ?';
            $params[] = $filters['type'];
        }
        $sql .= ' ORDER BY route_number ASC';
        $offset = ($page - 1) * $perPage;
        $sql .= ' LIMIT ? OFFSET ?';
        $params[] = $perPage; $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM routes WHERE id = ? AND is_active = 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findWithStops(int $id): ?array {
        $route = $this->findById($id);
        if (!$route) return null;

        $stmt = $this->db->prepare(
            'SELECT s.*, rs.stop_order, rs.distance_from_start_km,
             rs.arrival_time_offset_mins, rs.is_major_stop
             FROM route_stops rs
             JOIN stops s ON rs.stop_id = s.id
             WHERE rs.route_id = ?
             ORDER BY rs.stop_order ASC'
        );
        $stmt->execute([$id]);
        $route['stops'] = $stmt->fetchAll();
        $route['fares'] = $this->getFares($id);
        return $route;
    }

    public function search(string $query, int $cityId): array {
        $like = '%' . $query . '%';
        $stmt = $this->db->prepare(
            'SELECT DISTINCT r.* FROM routes r
             LEFT JOIN route_stops rs ON r.id = rs.route_id
             LEFT JOIN stops s ON rs.stop_id = s.id
             WHERE r.city_id = ? AND r.is_active = 1 AND (
               r.route_number LIKE ? OR r.source LIKE ? OR
               r.destination LIKE ? OR s.stop_name LIKE ?)
             ORDER BY r.route_number ASC LIMIT 20'
        );
        $stmt->execute([$cityId, $like, $like, $like, $like]);
        return $stmt->fetchAll();
    }

    public function getFares(int $routeId): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM fares WHERE route_id = ? ORDER BY min_km, passenger_type'
        );
        $stmt->execute([$routeId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            'INSERT INTO routes (city_id,route_number,source,destination,route_type,
             frequency_mins,first_bus_time,last_bus_time,total_distance_km,description)
             VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->execute([
            $data['city_id'], $data['route_number'], $data['source'], $data['destination'],
            $data['route_type'], $data['frequency_mins'],
            $data['first_bus_time'], $data['last_bus_time'],
            $data['total_distance_km'], $data['description'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function softDelete(int $id): bool {
        $stmt = $this->db->prepare('UPDATE routes SET is_active = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
```

### 7.7 Dijkstra Service (src/Services/DijkstraService.php)

This is the core routing algorithm. It builds a graph from the database and finds the shortest path between two stops, optionally minimizing transfers.

```php
<?php
namespace App\Services;
use App\Core\Database;

class DijkstraService {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Build adjacency graph for a city.
     * Graph: stop_id => [ [stop_id, distance_km, route_id, route_number], ... ]
     */
    private function buildGraph(int $cityId): array {
        $stmt = $this->db->prepare(
            'SELECT rs1.stop_id AS from_stop, rs2.stop_id AS to_stop,
             ABS(rs2.distance_from_start_km - rs1.distance_from_start_km) AS dist,
             rs1.route_id, r.route_number, r.route_type
             FROM route_stops rs1
             JOIN route_stops rs2 ON rs1.route_id = rs2.route_id
               AND rs2.stop_order = rs1.stop_order + 1
             JOIN routes r ON rs1.route_id = r.id
             WHERE r.city_id = ? AND r.is_active = 1'
        );
        $stmt->execute([$cityId]);
        $edges = $stmt->fetchAll();

        $graph = [];
        foreach ($edges as $e) {
            $graph[$e['from_stop']][] = [
                'to'           => $e['to_stop'],
                'dist'         => (float)$e['dist'],
                'route_id'     => $e['route_id'],
                'route_number' => $e['route_number'],
                'route_type'   => $e['route_type'],
            ];
            // Bidirectional
            $graph[$e['to_stop']][] = [
                'to'           => $e['from_stop'],
                'dist'         => (float)$e['dist'],
                'route_id'     => $e['route_id'],
                'route_number' => $e['route_number'],
                'route_type'   => $e['route_type'],
            ];
        }
        return $graph;
    }

    /**
     * Run Dijkstra from fromStop to toStop within a city.
     * Returns shortest path with legs and metadata.
     */
    public function findShortestPath(int $cityId, int $fromStop, int $toStop): ?array {
        $graph = $this->buildGraph($cityId);

        $dist = []; $prev = []; $prevEdge = [];
        $visited = [];
        $queue = new \SplMinHeap();

        foreach (array_keys($graph) as $node) {
            $dist[$node] = PHP_FLOAT_MAX;
        }
        $dist[$fromStop] = 0.0;
        $queue->insert([0.0, $fromStop]);

        while (!$queue->isEmpty()) {
            [$d, $u] = $queue->extract();
            if (isset($visited[$u])) continue;
            $visited[$u] = true;
            if ($u === $toStop) break;

            foreach ($graph[$u] ?? [] as $edge) {
                $v = $edge['to'];
                $alt = $d + $edge['dist'];
                if ($alt < ($dist[$v] ?? PHP_FLOAT_MAX)) {
                    $dist[$v] = $alt;
                    $prev[$v] = $u;
                    $prevEdge[$v] = $edge;
                    $queue->insert([$alt, $v]);
                }
            }
        }

        if (!isset($prev[$toStop]) && $fromStop !== $toStop) return null;

        // Reconstruct path
        $path = []; $cur = $toStop;
        while ($cur !== $fromStop) {
            $path[] = ['stop' => $cur, 'edge' => $prevEdge[$cur]];
            $cur = $prev[$cur];
        }
        $path[] = ['stop' => $fromStop, 'edge' => null];
        $path = array_reverse($path);

        // Group into legs (consecutive stops on same route)
        $legs = $this->groupIntoLegs($path, $cityId);

        return [
            'algorithm'          => 'dijkstra',
            'total_distance_km'  => round($dist[$toStop], 2),
            'estimated_time_mins'=> $this->estimateTime($dist[$toStop], count($legs)),
            'transfers'          => max(0, count($legs) - 1),
            'legs'               => $legs,
        ];
    }

    private function groupIntoLegs(array $path, int $cityId): array {
        $legs = []; $currentRouteId = null; $legStops = [];

        foreach ($path as $step) {
            $edge = $step['edge'];
            if ($edge === null) {
                $legStops[] = $step['stop'];
                continue;
            }
            if ($currentRouteId !== $edge['route_id']) {
                if (!empty($legStops)) {
                    $legs[] = $this->buildLeg($legStops, $currentRouteId, $cityId);
                }
                $currentRouteId = $edge['route_id'];
                $legStops = [end($legStops) ?: $step['stop']];
            }
            $legStops[] = $step['stop'];
        }
        if (!empty($legStops) && $currentRouteId) {
            $legs[] = $this->buildLeg($legStops, $currentRouteId, $cityId);
        }
        return $legs;
    }

    private function buildLeg(array $stopIds, int $routeId, int $cityId): array {
        $stopIds = array_unique(array_filter($stopIds));
        $stmt = $this->db->prepare('SELECT route_number, route_type FROM routes WHERE id = ?');
        $stmt->execute([$routeId]);
        $route = $stmt->fetch();

        $boardId = reset($stopIds);
        $alightId = end($stopIds);

        $stmt = $this->db->prepare('SELECT stop_name FROM stops WHERE id = ?');
        $stmt->execute([$boardId]); $board = $stmt->fetchColumn();
        $stmt->execute([$alightId]); $alight = $stmt->fetchColumn();

        return [
            'route_id'     => $routeId,
            'route_number' => $route['route_number'] ?? '',
            'route_type'   => $route['route_type'] ?? '',
            'board_stop'   => $board,
            'alight_stop'  => $alight,
            'stops_count'  => count($stopIds),
        ];
    }

    private function estimateTime(float $distKm, int $legs): int {
        // Avg speed 20 km/h + 5 min per transfer
        return (int)(($distKm / 20) * 60 + ($legs - 1) * 5);
    }
}
```

### 7.8 Overpass Service — Import Real Stops from OSM (src/Services/OverpassService.php)

```php
<?php
namespace App\Services;
use App\Core\Database;

class OverpassService {
    private string $endpoint = 'https://overpass-api.de/api/interpreter';

    /**
     * Fetch bus stops from OpenStreetMap for a given city bounding box.
     * bbox: [south, west, north, east]
     */
    public function fetchBusStops(array $bbox): array {
        [$s, $w, $n, $e] = $bbox;
        $query = "[out:json][timeout:30];
          node[\"highway\"=\"bus_stop\"]({$s},{$w},{$n},{$e});
          out body;";

        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'data=' . urlencode($query),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['elements'] ?? [];
    }

    /**
     * Import fetched OSM stops into the stops table for a city.
     */
    public function importStopsForCity(int $cityId, array $bbox): int {
        $db = Database::getInstance();
        $elements = $this->fetchBusStops($bbox);
        $imported = 0;

        foreach ($elements as $el) {
            $name = $el['tags']['name'] ?? null;
            if (!$name) continue;

            $stmt = $db->prepare(
                'INSERT IGNORE INTO stops
                 (city_id, stop_name, latitude, longitude, osm_node_id)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$cityId, $name, $el['lat'], $el['lon'], $el['id']]);
            if ($stmt->rowCount() > 0) $imported++;
        }
        return $imported;
    }
}
```

### 7.9 City Resolver Service (src/Services/CityResolverService.php)

```php
<?php
namespace App\Services;

class CityResolverService {
    /**
     * Geocode a city name to lat/lng using Nominatim (free, no key needed).
     */
    public function geocodeCity(string $cityName, string $countryCode = ''): ?array {
        $q = urlencode($cityName . ($countryCode ? ", {$countryCode}" : ''));
        $url = "https://nominatim.openstreetmap.org/search?q={$q}&format=json&limit=1";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['User-Agent: BusPortal/2.0 (contact@yoursite.com)'],
        ]);
        $response = curl_exec($ch); curl_close($ch);
        $results = json_decode($response, true);

        if (empty($results)) return null;
        return [
            'lat'         => (float)$results[0]['lat'],
            'lng'         => (float)$results[0]['lon'],
            'display_name'=> $results[0]['display_name'],
            'osm_id'      => $results[0]['osm_id'],
        ];
    }

    /**
     * Compute bounding box from center + radius in km.
     * Returns [south, west, north, east]
     */
    public function getBoundingBox(float $lat, float $lng, float $radiusKm = 15): array {
        $delta = $radiusKm / 111.0;
        return [$lat - $delta, $lng - $delta, $lat + $delta, $lng + $delta];
    }
}
```

---

## 8. Frontend Design Requirements

### 8.1 Design Philosophy

**Industrial Dark + Transit Clarity.** High-contrast, immediately readable on mobile in bright sunlight. Kolkata-flavored by default (tram-yellow, Howrah green accents) but the palette adapts to city brand colors stored in the `cities` table. The UI is city-aware — switching city updates colors, currency, map center, and language hints.

### 8.2 Color System (CSS Variables)

```css
/* public/assets/css/main.css — paste as first rule */
:root {
  /* Brand — default Kolkata palette (overridden dynamically per city) */
  --clr-primary:     #E8B84B;   /* Kolkata tram yellow */
  --clr-primary-dk:  #B8921E;   /* Hover state */
  --clr-primary-glow: rgba(232,184,75,0.15);
  --clr-accent:      #1B6B3A;   /* Howrah green */

  /* Neutrals */
  --clr-bg:        #0D0D0D;
  --clr-surface:   #161616;
  --clr-surface2:  #1F1F1F;
  --clr-border:    #2A2A2A;
  --clr-text:      #E8E8E8;
  --clr-muted:     #6B6B6B;
  --clr-white:     #FFFFFF;

  /* Status */
  --clr-green:  #27AE60;
  --clr-amber:  #F5A623;
  --clr-blue:   #4A90E2;
  --clr-gray:   #6B6B6B;

  /* Typography Scale */
  --fs-xs:   0.65rem;
  --fs-sm:   0.75rem;
  --fs-base: 0.875rem;
  --fs-md:   1rem;
  --fs-lg:   1.25rem;
  --fs-xl:   1.5rem;
  --fs-2xl:  2rem;
  --fs-3xl:  3rem;

  /* Spacing */
  --sp-xs: 4px; --sp-sm: 8px; --sp-md: 16px;
  --sp-lg: 24px; --sp-xl: 48px;

  /* Borders — sharp, no radius */
  --radius-none: 0;
  --radius-sm:   2px;

  /* Shadows */
  --shadow-primary: 0 4px 30px rgba(232,184,75,0.2);
  --shadow-card:    0 2px 12px rgba(0,0,0,0.5);

  /* Transitions */
  --trans-fast: 0.15s ease;
  --trans-med:  0.3s ease;
}
```

**Dynamic city theming:** When a user switches city, JavaScript updates `--clr-primary` and `--clr-accent` from the city's brand colors (stored in `cities.brand_json` JSON column — add this column for future expansion). Example:

```js
document.documentElement.style.setProperty('--clr-primary', city.brand_color);
```

### 8.3 Typography

| Element | Font | Weight | Size | Color |
|---|---|---|---|---|
| Logo / Route Numbers | Rajdhani | 700 | --fs-2xl to 3xl | --clr-primary |
| Nav Links | Rajdhani | 600 | --fs-sm | --clr-muted (hover: white) |
| Body Text | Noto Sans | 400 | --fs-base | --clr-text |
| Labels / Tags | Rajdhani | 600 | --fs-xs | --clr-muted |
| Code / IDs | Courier New | 400 | --fs-sm | --clr-primary |

```html
<link href='https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Noto+Sans:ital,wght@0,300;0,400;0,600;1,300&display=swap' rel='stylesheet'>
```

### 8.4 Component Specifications

#### 8.4.1 City Selector (Global Header Widget)

- Positioned in header, right of logo
- Dropdown shows city name + country flag emoji
- Selecting a city: updates `localStorage.cityId`, fires `citychange` custom event, re-fetches routes via AJAX, re-centers map, updates all stats
- No page reload required
- Shows loading skeleton during fetch

#### 8.4.2 Header / Navigation

- Height: 64px, sticky, z-index: 100
- Background: `--clr-surface`, border-bottom: 2px solid `--clr-primary`
- Box shadow: `--shadow-primary`
- Nav links: letter-spacing 2px, active = 2px primary bottom border

#### 8.4.3 Search Bar

- Max-width: 680px, centered
- Border: 1.5px solid `--clr-border`, background: `--clr-bg`
- Focus: border-color → `--clr-primary`
- Button: `--clr-primary`, dark text, Rajdhani 700
- Autocomplete dropdown: shows stop names + route numbers as user types (debounced 300ms)
- No border-radius — sharp corners throughout

#### 8.4.4 Route Cards

- Left accent: 3px bar, transparent → `--clr-primary` on active
- Route number: Rajdhani 700, `--fs-2xl`, `--clr-white`
- Hover: `--clr-surface`, Active: `--clr-surface2`

#### 8.4.5 Route Type Badge Colors

| Type | Background | Text |
|---|---|---|
| AC | rgba(39,174,96,0.15) | #27AE60 |
| Express | rgba(245,166,35,0.15) | #F5A623 |
| Night | rgba(74,144,226,0.15) | #4A90E2 |
| Normal | rgba(107,107,107,0.15) | #6B6B6B |
| Mini | rgba(232,184,75,0.15) | var(--clr-primary) |

#### 8.4.6 Stop Sequence Timeline

- `padding-left: 30px`, position relative
- Vertical line: `::before`, left 9px, width 2px, gradient `--clr-primary` → `--clr-green`
- First dot: 14×14px, `--clr-primary`
- Last dot: 14×14px, `--clr-green`
- Major stop dot: `--clr-amber`
- Distance label: Rajdhani 600, `--fs-sm`, `--clr-muted`

#### 8.4.7 Stats Bar

- Flex row, border-bottom: 1px solid `--clr-border`
- Number: Rajdhani 700, `--fs-2xl`, `--clr-primary`
- Label: Rajdhani 600, `--fs-xs`, letter-spacing 2px, uppercase, `--clr-muted`
- Stats: Routes Count, Stops Count, Cities Supported, Avg Frequency

#### 8.4.8 Info Grid (Route Detail)

- CSS Grid: 4 columns, each bordered right
- Label: Rajdhani 600, `--fs-xs`, letter-spacing 2.5px, uppercase, `--clr-muted`
- Value: Rajdhani 700, `--fs-lg`, `--clr-white`
- First bus: `--clr-green`. Last bus: `--clr-amber`

### 8.5 Map Panel (Leaflet.js — Free)

The map is a core feature, not optional. It uses **Leaflet.js + OpenStreetMap** — completely free.

```html
<!-- In <head> -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<!-- Before </body> -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

**Map features:**

- Dark tile layer: CartoDB Dark Matter (free, no key)
  ```
  https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png
  ```
- Route polyline drawn from stop lat/lng coordinates, animated using `polyline.animate()` (CSS stroke-dashoffset trick)
- Stop markers: custom SVG circles color-coded by stop type
- Animated bus icon travels along polyline to show route direction
- Popup on stop click: shows stop name, routes through it, nearest landmark
- On city change: map flyTo() with smooth animation to new city center

```js
// public/assets/js/map.js — key snippets

const map = L.map('map-container', { zoomControl: false });
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
  attribution: '©OSM ©CartoDB', maxZoom: 19
}).addTo(map);

// Draw animated route polyline
function drawRoute(stops) {
  const coords = stops.map(s => [s.latitude, s.longitude]);
  const polyline = L.polyline(coords, {
    color: getComputedStyle(document.documentElement)
              .getPropertyValue('--clr-primary').trim(),
    weight: 4, opacity: 0.85
  }).addTo(map);

  // Animate: stroke-dashoffset trick via CSS class
  polyline.getElement()?.classList.add('route-draw-animation');
  map.fitBounds(polyline.getBounds(), { padding: [40, 40] });
}

// Animated bus marker along route
function animateBus(stops) {
  let idx = 0;
  const busIcon = L.divIcon({ className: 'bus-marker', html: '🚌', iconSize: [24, 24] });
  const marker = L.marker(stops[0], { icon: busIcon }).addTo(map);

  const timer = setInterval(() => {
    idx = (idx + 1) % stops.length;
    marker.setLatLng([stops[idx].latitude, stops[idx].longitude]);
    if (idx === stops.length - 1) clearInterval(timer);
  }, 800);
}
```

**Map CSS animation:**

```css
/* public/assets/css/map.css */
.route-draw-animation {
  stroke-dasharray: 2000;
  stroke-dashoffset: 2000;
  animation: drawLine 2s ease forwards;
}
@keyframes drawLine {
  to { stroke-dashoffset: 0; }
}
.bus-marker { font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }
```

### 8.6 Journey Planner UI

- Two stop search inputs: "From" and "To" with autocomplete
- Passenger type selector (General / Student / Senior)
- "Find Route" button triggers `POST /api/planner`
- Result shows: animated step-by-step leg display, transfer indicators, total fare in local currency, estimated time
- Map automatically draws the suggested path

### 8.7 Layout Grid

| Area | Desktop | Tablet | Mobile |
|---|---|---|---|
| Header | Sticky 64px | Same | Same, hide nav links |
| City Selector | Header right | Header right | Full-width dropdown |
| Hero / Search | Full width | Same | Reduced padding |
| Main Layout | 320px sidebar + 1fr detail + map panel | Stack | Stack |
| Map Panel | Right side, 40% width | Full width below detail | Full width collapsible |
| Info Grid | 4 columns | 2 columns | 2 columns |

### 8.8 Responsive Breakpoints

| Breakpoint | Width | Changes |
|---|---|---|
| Mobile | < 576px | Stack all layouts, hide nav, reduce font sizes |
| Tablet | 576px–768px | 2-col info grid, stack sidebar |
| Desktop | > 768px | Full 3-panel layout (sidebar + detail + map) |

### 8.9 HTML Page Structure Template

```html
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title><?= htmlspecialchars($pageTitle) ?> — Bus Route Portal</title>
  <link href='https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Noto+Sans:ital,wght@0,300;0,400;0,600;1,300&display=swap' rel='stylesheet'>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <link href='/assets/css/main.css' rel='stylesheet'>
  <link href='/assets/css/map.css' rel='stylesheet'>
</head>
<body>
  <?php include 'views/layout/header.php'; ?>
  <main><!-- Page content --></main>
  <?php include 'views/layout/footer.php'; ?>
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
  <script src='https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'></script>
  <script src='/assets/js/main.js'></script>
  <script src='/assets/js/map.js'></script>
  <script src='/assets/js/city-selector.js'></script>
</body>
</html>
```

### 8.10 City Selector JavaScript (public/assets/js/city-selector.js)

```js
// Dynamic city switching — no page reload
class CitySelector {
  constructor() {
    this.currentCityId = localStorage.getItem('cityId') || 1;
    this.init();
  }

  async init() {
    const cities = await this.fetchCities();
    this.render(cities);
    this.loadCity(this.currentCityId);
  }

  async fetchCities() {
    const res = await fetch('/api/cities');
    const json = await res.json();
    return json.data;
  }

  render(cities) {
    const select = document.getElementById('city-selector');
    cities.forEach(city => {
      const opt = document.createElement('option');
      opt.value = city.id;
      opt.textContent = `${city.city_name}, ${city.country}`;
      if (city.id == this.currentCityId) opt.selected = true;
      select.appendChild(opt);
    });
    select.addEventListener('change', e => this.loadCity(e.target.value));
  }

  async loadCity(cityId) {
    this.currentCityId = cityId;
    localStorage.setItem('cityId', cityId);

    // Show skeleton loaders
    document.getElementById('routes-list').classList.add('loading');

    // Fetch routes for city
    const res = await fetch(`/api/routes?city_id=${cityId}`);
    const json = await res.json();

    // Dispatch event — map.js and search.js listen to this
    document.dispatchEvent(new CustomEvent('citychange', {
      detail: { cityId, meta: json.meta }
    }));

    // Update UI
    this.updateRoutesList(json.data);
    document.getElementById('routes-list').classList.remove('loading');
  }

  updateRoutesList(routes) {
    // Render route cards dynamically
    const container = document.getElementById('routes-list');
    container.innerHTML = routes.map(r => `
      <div class="route-card" data-id="${r.id}" onclick="loadRoute(${r.id})">
        <span class="route-number">${r.route_number}</span>
        <span class="badge badge-${r.route_type.toLowerCase()}">${r.route_type}</span>
        <div class="route-endpoints">${r.source} → ${r.destination}</div>
        <div class="route-meta">Every ${r.frequency_mins} min</div>
      </div>
    `).join('');
  }
}

const citySelector = new CitySelector();
```

### 8.11 Error & Edge Case Handling

All errors must be handled gracefully — never show raw PHP errors or blank screens.

| Scenario | Handling |
|---|---|
| City has no routes yet | Show "No routes found. Import from OSM?" prompt (admin) or "Coming soon" (public) |
| Stop has no coordinates | Hide map pin, show text "Location not available" |
| Dijkstra finds no path | "No direct route found. Try nearby stops." with suggestions |
| API timeout / offline | Show cached last result with "Data may be outdated" banner |
| Search returns zero | Show "Did you mean...?" suggestions using levenshtein distance |
| Invalid route ID | Friendly 404 with search bar |
| City selector API fails | Fall back to stored city list in localStorage |
| OSM import returns 0 stops | Show "No stops found in area. Try expanding search radius." |
| JWT expired (admin) | Silently refresh or redirect to login with return URL |
| Overpass API rate limit | Queue and retry with exponential backoff |

---

## 9. Advanced Features: Maps, Algorithms & Dynamic Data

### 9.1 Dijkstra Routing — Technical Details

The graph is built from `route_stops` adjacency: each pair of consecutive stops on a route forms a weighted edge. Weight = distance in km (can be swapped for time-in-minutes for time-optimized routing).

**Algorithm complexity:** O((V + E) log V) using a min-heap. Suitable for city-scale graphs (typically 200–2000 stops).

**Enhancements to implement progressively:**

1. **Multi-criteria Dijkstra** — minimize transfers as secondary objective: `weight = distance + α × transfers` (tune α)
2. **A\* optimization** — use straight-line distance to destination as heuristic for faster search
3. **Time-dependent routing** — penalize routes not running at the current hour (check `first_bus_time` / `last_bus_time`)
4. **RAPTOR algorithm** (future) — handles real-time GTFS feeds with thousands of routes efficiently

```js
// public/assets/js/dijkstra.js — client-side version for instant preview
// Loaded from /api/stops?city_id=X into a local graph object

class DijkstraGraph {
  constructor(edges) {
    this.adj = {};
    edges.forEach(([u, v, w, route]) => {
      (this.adj[u] = this.adj[u] || []).push({ v, w, route });
      (this.adj[v] = this.adj[v] || []).push({ v: u, w, route });
    });
  }

  shortestPath(src, dst) {
    const dist = {}, prev = {}, visited = new Set();
    const pq = [[0, src]]; // [cost, node]
    Object.keys(this.adj).forEach(n => dist[n] = Infinity);
    dist[src] = 0;

    while (pq.length) {
      pq.sort((a, b) => a[0] - b[0]);
      const [d, u] = pq.shift();
      if (visited.has(u)) continue;
      visited.add(u);
      if (u === dst) break;
      for (const { v, w, route } of (this.adj[u] || [])) {
        const alt = d + w;
        if (alt < dist[v]) {
          dist[v] = alt; prev[v] = { node: u, route };
          pq.push([alt, v]);
        }
      }
    }

    if (dist[dst] === Infinity) return null;
    const path = []; let cur = dst;
    while (cur) { path.unshift({ stop: cur, via: prev[cur]?.route }); cur = prev[cur]?.node; }
    return { path, totalDist: dist[dst] };
  }
}
```

### 9.2 Real Map Data Integration

#### Using Overpass API (Free, No Key)

Fetch real bus stops for any city bounding box:

```
https://overpass-api.de/api/interpreter?data=[out:json];
node["highway"="bus_stop"](22.4,88.2,22.7,88.5);out body;
```

Response includes: OSM node ID, lat, lng, `tags.name`, `tags.ref` (stop code).

#### GTFS Feed Import (Optional, Free for Most Cities)

Many cities publish free GTFS feeds:

| City | GTFS URL |
|---|---|
| Kolkata KMRC (Metro) | https://data.gov.in (search GTFS Kolkata) |
| Delhi DTC | https://otd.delhi.gov.in |
| New York MTA | https://new.mta.info/developers |
| London TfL | https://tfl.gov.uk/info-for/open-data-users/ |

`GtfsImportService.php` should parse `stops.txt`, `routes.txt`, `stop_times.txt` from ZIP and insert into the database.

#### OpenRouteService (Free tier: 2000 req/day)

Used for: turn-by-turn directions between stops, isochrone maps (how far can you travel from a stop in 30 min), distance matrix.

```php
// src/Services/RoutingService.php
public function getDirections(float $fromLat, float $fromLng, float $toLat, float $toLng): ?array {
    $url = 'https://api.openrouteservice.org/v2/directions/driving-car/json';
    $body = json_encode([
        'coordinates' => [[$fromLng, $fromLat], [$toLng, $toLat]]
    ]);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . $_ENV['ORS_API_KEY'],
            'Content-Type: application/json',
        ],
    ]);
    $res = curl_exec($ch); curl_close($ch);
    return json_decode($res, true);
}
```

### 9.3 Adding a New City — Full Flow

1. Admin goes to `/admin/cities` → "Add City"
2. Types city name → system calls Nominatim → auto-fills lat/lng
3. Admin confirms → city saved to `cities` table
4. Admin clicks "Import Stops from OSM" → `OverpassService` fetches stops
5. Admin can manually add routes, OR import GTFS feed
6. City appears in public city selector immediately
7. Map auto-centers on new city when selected

### 9.4 Predictive Arrival Estimation

When `arrival_time_offset_mins` is stored in `route_stops`, the system can compute:

```
estimated_arrival = first_bus_time + offset_mins + floor(now - first_bus_time) / frequency_mins × frequency_mins
```

Display: "Next bus in ~12 minutes" computed client-side from route frequency + current time.

```js
// public/assets/js/main.js
function nextBusIn(firstBusTime, lastBusTime, frequencyMins) {
  const now = new Date();
  const [fh, fm] = firstBusTime.split(':').map(Number);
  const [lh, lm] = lastBusTime.split(':').map(Number);
  const firstMins = fh * 60 + fm;
  const lastMins = lh * 60 + lm;
  const nowMins = now.getHours() * 60 + now.getMinutes();
  if (nowMins < firstMins) return `First bus at ${firstBusTime}`;
  if (nowMins > lastMins) return `Service ended. First bus tomorrow at ${firstBusTime}`;
  const elapsed = nowMins - firstMins;
  const next = frequencyMins - (elapsed % frequencyMins);
  return next <= 1 ? 'Arriving now' : `Next bus in ~${next} min`;
}
```

---

## 10. Security Requirements

### 10.1 Authentication & Authorization

- Admin access exclusively via Google OAuth 2.0
- JWT tokens in HttpOnly cookies — not localStorage
- JWT expiry: 1 hour (`JWT_EXPIRY` in `.env`)
- RBAC: `super_admin` > `admin` > `viewer`
- OAuth state parameter validated on every callback

### 10.2 SQL Injection Prevention

- All queries via PDO prepared statements — zero raw SQL concatenation
- Production DB user: `SELECT, INSERT, UPDATE, DELETE` only — no `DROP, CREATE`

### 10.3 XSS Prevention

- All HTML output via `htmlspecialchars()` with `ENT_QUOTES | ENT_HTML5`
- API responses: `Content-Type: application/json`
- CSP header: `default-src 'self'; script-src 'self' cdn.jsdelivr.net unpkg.com fonts.googleapis.com`

### 10.4 CSRF Protection

- All POST forms include hidden `_csrf` token
- All AJAX POSTs include `X-CSRF-Token` header
- `CsrfMiddleware::validate()` at top of every POST handler

### 10.5 Rate Limiting

- Search API: 60 req/min per IP
- Auth endpoints: 10 req/min per IP
- Overpass import: 1 req/min per admin (rate-limit to avoid OSM ban)

### 10.6 Security Headers (.htaccess)

```apache
# public/.htaccess
Options -Indexes
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy strict-origin-when-cross-origin
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' cdn.jsdelivr.net unpkg.com fonts.googleapis.com; style-src 'self' fonts.googleapis.com cdn.jsdelivr.net unpkg.com 'unsafe-inline'; img-src * data:; connect-src 'self' overpass-api.de nominatim.openstreetmap.org api.openrouteservice.org"

<FilesMatch "\.(env|sql|log|json|lock)$">
  Require all denied
</FilesMatch>
```

---

## 11. Testing Requirements

### 11.1 Manual Testing Checklist

| Test Case | Expected Result | Priority |
|---|---|---|
| Load homepage | City selector visible, stats populated | High |
| Switch city to New York | Routes update, map re-centers, no page reload | High |
| Search 'Howrah' | Returns routes passing Howrah Station | High |
| Search empty string | Shows all routes or validation message | Medium |
| Click route S-12 | Detail panel shows stops, fares, animated map | High |
| Filter 'AC' chip | Only AC routes shown | High |
| Journey planner: Howrah → Salt Lake | Returns Dijkstra path with S-12 | High |
| No path exists | Friendly "no route found" message | High |
| Admin login via Google | Redirected to dashboard | High |
| Admin adds new city | Appears in city selector immediately | High |
| Admin imports OSM stops | Stops count increases, shown on map | High |
| Admin creates route | Route appears in public list | High |
| Mobile 375px | Stacked layout, map collapsible, readable | High |
| Invalid /routes/9999 | Friendly 404 with search bar | Medium |
| API GET /api/routes?city_id=1 | Returns JSON array | High |
| API POST /api/routes without JWT | 401 Unauthorized | High |
| CSRF: POST without token | 403 Forbidden | High |
| XSS: `<script>alert(1)</script>` in search | Rendered as text, not executed | High |
| Overpass import for new city | Stops imported and visible on map | Medium |
| Next bus calculation | Shows correct countdown based on current time | Medium |

### 11.2 API Testing with Postman

- Base URL: `http://localhost/bus-portal`
- Environment variables: `BASE_URL`, `JWT_TOKEN`, `CITY_ID`
- Test sequence: Cities → Auth → Routes → Search → Planner → Admin CRUD

---

## 12. Deployment Guide

### 12.1 Local Development Setup

```bash
# 1. Install XAMPP, start Apache and MySQL
# 2. Clone repo
git clone https://github.com/yourorg/bus-portal.git C:/xampp/htdocs/bus-portal
# 3. Copy env
cp .env.example .env
# (edit .env with DB credentials, Google OAuth keys, ORS key)
# 4. Install PHP deps
composer install
# 5. Create DB and run schema
mysql -u root -p < database/schema.sql
# 6. Seed Kolkata data
mysql -u root -p bus_portal < database/seed_kolkata.sql
# 7. Visit http://localhost/bus-portal
```

### 12.2 Production Deployment Checklist

| Step | Task |
|---|---|
| 1 | Set `APP_ENV=production` in `.env` |
| 2 | Set strong random `APP_SECRET` and `JWT_SECRET` (32+ chars) |
| 3 | Configure SSL on domain |
| 4 | Update Google OAuth redirect URI to production domain |
| 5 | Set production DB credentials (non-root user) |
| 6 | Point Apache `DocumentRoot` to `/path/to/bus-portal/public` |
| 7 | `composer install --no-dev --optimize-autoloader` |
| 8 | Ensure `storage/logs/` writable by web server |
| 9 | Add cron to rotate logs weekly |
| 10 | Verify `.env` is outside web root |
| 11 | Test Overpass API reachability from server |
| 12 | Test Nominatim geocoding from server |
| 13 | Configure rate limiting for Overpass calls |

---

## 13. Appendix

### 13.1 .gitignore

```gitignore
.env
vendor/
storage/logs/
*.log
.DS_Store
Thumbbs.db
node_modules/
```

### 13.2 .env.example

```env
APP_NAME=Bus Route Portal
APP_ENV=development
APP_URL=http://localhost/bus-portal
APP_SECRET=CHANGE_ME_32_CHARS_MIN

DB_HOST=localhost
DB_PORT=3306
DB_NAME=bus_portal
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost/bus-portal/auth/google/callback

ORS_API_KEY=

SESSION_LIFETIME=7200
JWT_SECRET=CHANGE_ME_ANOTHER_32_CHARS
JWT_EXPIRY=3600
```

### 13.3 Free Services Summary

| Service | What it does | Limits | Key needed? |
|---|---|---|---|
| OpenStreetMap | Base map tiles | None | No |
| CartoDB Dark Matter | Dark map tiles | None | No |
| Leaflet.js | Map rendering | None | No |
| Nominatim | City geocoding | 1 req/sec | No |
| Overpass API | Bus stop data | Fair use | No |
| OpenRouteService | Routing, directions | 2000 req/day | Yes (free) |
| GTFS feeds | Full transit data | Varies by city | Usually no |

### 13.4 Future Expansion Hooks (Do Not Remove)

The following are intentionally left as stubs for future development:

- `GtfsImportService.php` — GTFS ZIP parser
- `cities.osm_relation_id` column — for automated OSM route imports
- `/api/osm/import-stops` endpoint — admin-triggered OSM enrichment
- `dijkstra.js` client-side graph — for offline-capable PWA version
- `CityController.php` → `addCity()` — Nominatim-powered city onboarding
- `brand_json` column on `cities` (future) — per-city color theming
- RAPTOR algorithm class stub in `src/Services/` — for real-time GTFS routing

### 13.5 Learning Resources

| Topic | Resource | URL |
|---|---|---|
| PHP 8 | PHP Manual | https://www.php.net/manual |
| PDO | PDO Guide | https://www.php.net/manual/en/book.pdo.php |
| OAuth 2.0 | OAuth Simplified | https://www.oauth.com |
| Bootstrap 5 | Docs | https://getbootstrap.com/docs/5.3 |
| Leaflet.js | Docs | https://leafletjs.com |
| Dijkstra | Visualized | https://visualgo.net/en/sssp |
| Overpass API | Tutorial | https://wiki.openstreetmap.org/wiki/Overpass_API |
| OpenRouteService | Docs | https://openrouteservice.org/dev/#/api-docs |
| Nominatim | Usage Policy | https://operations.osmfoundation.org/policies/nominatim/ |
| GTFS | Spec | https://gtfs.org |
| JWT | Debugger | https://jwt.io |

### 13.6 Version History

| Version | Date | Changes |
|---|---|---|
| 1.0 | April 25, 2025 | Initial DTC Delhi specification |
| 2.0 | April 2025 | Kolkata seed data, universal city support, Leaflet maps, Dijkstra routing, OSM/Overpass integration, dynamic city selector |

---

*— End of Document —*
*Universal Bus Route Portal | SRS v2.0*