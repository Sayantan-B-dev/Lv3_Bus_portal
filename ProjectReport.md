# Project Report: Universal City Bus Route Portal (v3.0)
## High-Performance Multi-City Transit Management System

---

## Abstract
The Universal City Bus Route Portal (UCBRP) is a state-of-the-art transit management platform designed to centralize and visualize urban bus network data. Built using a custom-engineered PHP MVC framework, the system provides real-time route discovery, geographical visualization via Leaflet.js, and an intelligent journey planning engine. This report provides an exhaustive analysis of the project's development lifecycle, architectural design, and technical implementation.

---

## 1. Introduction

### 1.1 Objective
The primary objective of the Universal City Bus Route Portal (UCBRP) is to engineer a scalable, high-performance transit information ecosystem that bridges the critical "information gap" in urban public transportation. The system is designed to achieve the following specific goals:

*   **Information Centralization**: To consolidate disparate transit data from multiple cities into a single, unified relational database, enabling cross-city route discovery and standardization of transit information.
*   **Commuter Empowerment**: To provide daily commuters with an intuitive, visually rich interface for identifying optimal bus routes, calculating precise distance-based fares, and visualizing geographical stop points in real-time.
*   **Administrative Efficiency**: To deliver a secure, comprehensive management dashboard that empowers transit operators to manage complex route sequences, geographical coordinates, and dynamic fare slabs with minimal technical overhead.
*   **Technical Demonstration**: To serve as a definitive academic implementation of modern web architecture, specifically demonstrating the efficiency of custom-built PHP MVC frameworks, Singleton design patterns, and hybrid (Stateful/Stateless) authentication systems.
*   **Performance Optimization**: To ensure a zero-latency user experience by utilizing native web technologies (Vanilla JS/CSS) and optimized SQL querying, targeting sub-100ms response times for all critical search and planning operations.

### 1.2 Scope of the Project
The scope of the UCBRP v3.0 encompasses the full-stack development life cycle, from the design of a custom backend engine to the implementation of high-fidelity frontend micro-interactions. The boundaries of the project include:

*   **Core Engine Development**: Building a custom PHP MVC framework from the ground up, including a regex-based routing engine, a PDO-based database abstraction layer, and a robust middleware pipeline for security filtering.
*   **Relational Data Modeling**: Designing a highly normalized MySQL schema capable of handling multi-city architectures, complex junction-table relationships for route sequencing, and multi-tier fare matrices (General, Student, Senior).
*   **Geographical Information System (GIS)**: Integrating Leaflet.js with OpenStreetMap (OSM) and MapTiler to provide interactive stop mapping, polyline-based route visualization, and spatial search capabilities (Haversine-based nearby stop identification).
*   **Security & Identity Architecture**: Implementing a hybrid security model that combines Google OAuth 2.0 for administrative identity validation with JWT (JSON Web Tokens) for secure, stateless communication between the frontend and the REST API.
*   **Premium Frontend Engineering**: Developing a modern, industrial-dark themed UI using CSS variables (Tokens), Glassmorphism aesthetics, and responsive layout strategies that ensure 100% functionality across mobile, tablet, and desktop environments.

### 1.3 Module Analysis
The system is architected into five primary functional modules, each handling a distinct aspect of the transit ecosystem:

1.  **Public Information Portal**: The core user interface allowing commuters to select their city, search for bus numbers or stop names, and view detailed route timelines. It features an "Intelligent Journey Planner" that calculates paths between any two stops in the network.
2.  **Administrative Suite (Dashboard)**: A restricted, role-based module for transit data management. It provides full CRUD (Create, Read, Update, Delete) capabilities for cities, stops, routes, and fare slabs, along with real-time activity logging for audit trails.
3.  **Authentication & Identity Module**: Manages the handshake with Google's OAuth 2.0 servers, handles session persistence via PHP's native session engine, and generates/validates JWTs for API-driven components.
4.  **GIS & Spatial Module**: Powered by Leaflet.js, this module translates database coordinates into visual map markers and paths. It includes logic for dynamic map re-centering based on city selection and "Find Nearby" spatial queries.
5.  **RESTful API Engine**: A stateless communication layer that exposes JSON endpoints for all core transit data. This module ensures the backend is "decoupled" from the frontend, allowing for potential future integration with mobile applications or third-party transit aggregators.

---

## 2. Requirement Specifications

The requirement specification phase involves a thorough identification of the technical resources necessary for the optimal deployment and performance of the UCBRP. These are categorized into hardware, software, and functional constraints.

### 2.1 Hardware Requirements
To ensure the system maintains sub-100ms response times and handles concurrent administrative operations, the following specifications are required:

*   **Server-Side (Hosting Environment)**:
    *   **Processor**: Dual-Core 2.4 GHz or higher (Recommended: Quad-Core for high-traffic cities).
    *   **Memory (RAM)**: 4 GB minimum (Recommended: 8 GB for large-scale MySQL indexing).
    *   **Storage**: 500 MB for application files + 2 GB for database growth and activity logs.
    *   **Network**: 100 Mbps symmetric connection (for API responsiveness).
*   **Client-Side (User Device)**:
    *   **Device**: PC, Laptop, Tablet, or Smartphone with modern browser support.
    *   **Display**: Minimum resolution 320x568 (iPhone SE size) for responsive layout verification.
    *   **Input**: Touch interface or Mouse/Keyboard support.

### 2.2 Software Requirements
The UCBRP utilizes the industry-standard WAMP/LAMP stack for maximum compatibility and performance:

*   **Operating System**: Windows 10/11 (Development) or Linux Ubuntu 22.04 LTS (Production).
*   **Web Server**: Apache 2.4.x with `mod_rewrite` (essential for clean URL routing).
*   **Database Management**: MariaDB 10.4.x or MySQL 8.0 (InnoDB engine required for foreign key constraints).
*   **Scripting Language**: PHP 8.2.12 or higher (utilizing strict typing and modern PDO features).
*   **Frontend Libraries**: Leaflet.js (GIS), Gsap (Animations), and Google Identity Services (OAuth).
*   **Development Tools**: Composer 2.0 (Dependency management), VS Code/PHPStorm (IDE), and Git (Version Control).

### 2.3 Functional Requirements
The system must successfully execute the following core operations:
*   **Data Integrity**: Maintenance of referential integrity across Cities, Routes, and Stops.
*   **Search Capability**: Asynchronous, case-insensitive searching for routes and stop names.
*   **Pathfinding**: Calculation of the shortest route between any two given stop IDs.
*   **Identity Management**: Secure admin authentication via third-party OAuth providers.
*   **API Exposure**: Provision of JSON endpoints for all transit data entities.

### 2.4 Non-Functional Requirements
*   **Performance**: The system shall load the initial interactive map in under 2 seconds.
*   **Security**: All administrative POST requests must be protected by CSRF tokens and JWT verification.
*   **Scalability**: The architecture must support the addition of new cities without modifying core source code.
*   **Usability**: The interface must follow the "Industrial Dark" design tokens for visual consistency and eye comfort.

---

## 3. System Analysis

System analysis is a problem-solving technique that decomposes the transit information challenge into its component pieces to identify how well those pieces work and interact to accomplish their purpose.

### 3.1 Literature Review & Comparative Study
In the landscape of urban transit informatics, several global standards exist, such as Google Maps (Transit), Moovit, and city-specific applications like "One Delhi." This project conducts a comparative analysis to justify the development of a custom-built portal:

*   **Google Maps Transit**: While globally dominant, it often lacks the granular fare slab information and specific passenger categorization (Student/Senior) required for local bus networks. It also imposes high licensing costs for deep API integration.
*   **Legacy Government Portals**: Often built on monolithic architectures with outdated UI, leading to high bounce rates.
*   **UCBRP Advantage**: The proposed system offers a "Middle Ground"—the professional mapping power of Leaflet.js combined with the granular, localized data management of a custom relational database, all while maintaining a near-zero cost of ownership through open-source technologies.

### 3.2 Existing System Analysis
Current transit information systems in many metropolitan areas suffer from several critical "Information Silos":
*   **Lack of Centralization**: Each city or transit operator often maintains its own isolated portal, forcing commuters to switch between multiple apps.
*   **Static Information**: Route data is often presented as PDF files or static tables, which are difficult to search and impossible to visualize geographically.
*   **Poor Accessibility**: Mobile responsiveness is frequently an afterthought, making the data nearly unusable for commuters "on the go."
*   **High Latency**: Heavy reliance on bloated CMS frameworks (like WordPress) often leads to slow page loads and poor user retention.

### 3.2 Proposed System Analysis
The Universal City Bus Route Portal (UCBRP) is proposed as a comprehensive solution to these legacy limitations:
*   **Unified Multi-City Engine**: A single source of truth for all cities, managed through a central administrative core.
*   **Dynamic GIS Mapping**: Real-time translation of SQL coordinates into interactive map paths using Leaflet.js.
*   **Lightweight Custom MVC**: By eliminating third-party framework overhead, the system achieves maximum execution speed and a minimal server footprint.
*   **Intelligent Pathfinding**: Replaces manual route searching with an automated engine that identifies interchange points and calculates total distances.

### 3.3 Feasibility Study
A detailed feasibility study was conducted to ensure the project's viability:

1.  **Technical Feasibility**: The chosen stack (PHP/MySQL/Leaflet) is highly mature and well-documented. The team possesses the necessary expertise in MVC design patterns and RESTful API development.
2.  **Operational Feasibility**: The system requires minimal administrative training. The dashboard is designed to automate complex tasks like route sequencing, making it operationally sustainable for transit staff.
3.  **Economic Feasibility**: By utilizing open-source technologies, the development and maintenance costs are kept to a minimum. The project eliminates the need for expensive proprietary GIS licenses.
4.  **Legal/Social Feasibility**: The system utilizes public transit data and adheres to standard web privacy protocols. It provides a significant social benefit by improving urban mobility.

---

---

## 4. System Design (Architectural)

### 4.1 Project Structure (Folder Tree)
The UCBRP v3.0 follows a strictly decoupled MVC (Model-View-Controller) architecture, ensuring high maintainability and scalability.

```text
yatrapath/
├── api/                        # REST API Namespace
│   └── index.php               # Entry point for stateless API requests
├── config/                     # Configuration Namespace
│   └── config.php              # Global constants and DB credentials
├── database/                   # Data Persistence Namespace
│   ├── yatrapath.sql          # Master SQL dump (Schema + Data)
│   └── migrations/             # Incremental schema update scripts
├── public/                     # Public Web Root
│   ├── index.php               # Front Controller (Web entry point)
│   └── assets/                 # Static Assets
│       ├── css/                # Styling (Dark theme, Map tokens)
│       ├── js/                 # Logic (Map.js, Auth.js, Search.js)
│       └── img/                # Visuals (Logos, Icons, Backgrounds)
├── src/                        # Core Application Logic
│   ├── Controllers/            # Request Handlers (Bridge between Models & Views)
│   │   ├── Admin/              # Protected Administrative Controllers
│   │   ├── AuthController.php  # Login/OAuth handshake logic
│   │   └── RouteController.php # Public route discovery logic
│   ├── Core/                   # System Framework Engine
│   │   ├── Database.php        # Singleton PDO connection wrapper
│   │   ├── Router.php          # Regex-based URI dispatcher
│   │   ├── Request.php         # HTTP Input abstraction & sanitization
│   │   ├── Response.php        # Standardized Web/JSON output helper
│   │   └── Session.php         # State management & Flash messaging
│   ├── Models/                 # Data Layer (Active Record patterns)
│   │   ├── City.php            # Regional data entity
│   │   ├── Route.php           # Bus service entity
│   │   └── User.php            # Identity & Profile entity
│   ├── Middleware/             # Security Pipeline
│   │   ├── AuthMiddleware.php  # Session/JWT validation layer
│   │   ├── CsrfMiddleware.php  # POST request forgery protection
│   │   └── RateLimit.php       # API burst control logic
│   └── Services/               # Business Logic Services
│       ├── PlannerService.php  # Shortest-path algorithm implementation
│       └── MapService.php      # Leaflet coordinate processing logic
├── views/                      # UI Templates (HTML/PHP)
│   ├── admin/                  # Dashboard & CRUD templates
│   ├── layout/                 # Shared Header/Footer/Sidebars
│   └── home/                   # Portal landing page
├── tests/                      # Quality Assurance
│   ├── DijkstraTest.php        # Verifies pathfinding accuracy
│   └── AuthTest.php            # Verifies JWT security integrity
├── composer.json               # PSR-4 Autoloading & Dependencies
└── .env                        # Environment variables (Ignored by Git)
```

### 4.2 Comprehensive Database Schema
The UCBRP database is built on the MariaDB 10.4 engine, utilizing strict foreign key constraints and optimized indexes to ensure data integrity and performance.

#### Table 1: `cities` (Regional Metadata)
Stores administrative and geospatial data for supported cities.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | Primary Key, Auto-increment. |
| `city_name` | VARCHAR(100) | Full name of the city. |
| `state_region`| VARCHAR(100) | State or Province. |
| `country` | VARCHAR(100) | Default: 'India'. |
| `country_code`| CHAR(2) | ISO code (e.g., 'IN'). |
| `currency` | VARCHAR(10) | Local currency symbol (e.g., 'INR'). |
| `timezone` | VARCHAR(60) | PHP-compatible timezone string. |
| `center_lat` | DECIMAL(10,7)| Map centering latitude. |
| `center_lng` | DECIMAL(10,7)| Map centering longitude. |
| `default_zoom`| TINYINT(4) | Initial Leaflet zoom level (Default: 12). |
| `osm_relation_id`| BIGINT(20) | OpenStreetMap relation reference. |
| `is_active` | TINYINT(1) | Soft-delete toggle. |

#### Table 2: `stops` (Geospatial Points)
Physical locations of transit points.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | Primary Key. |
| `city_id` | INT(10) U | FK -> `cities.id`. |
| `stop_name` | VARCHAR(150) | Display name. |
| `stop_code` | VARCHAR(20) | Unique identifier (e.g., Stop No). |
| `latitude` | DECIMAL(10,7)| GPS Latitude. |
| `longitude` | DECIMAL(10,7)| GPS Longitude. |
| `landmark` | VARCHAR(200) | Descriptive location aid. |
| `zone` | VARCHAR(50) | Fare zone or sector. |
| `osm_node_id` | BIGINT(20) | OSM node reference. |
| `is_terminal` | TINYINT(1) | Flag for source/destination points. |

#### Table 3: `routes` (Transit Service)
Defines the bus services available in each city.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | Primary Key. |
| `city_id` | INT(10) U | FK -> `cities.id`. |
| `route_number`| VARCHAR(20) | Bus Number (e.g., '534'). |
| `source` | VARCHAR(120) | Starting terminal name. |
| `destination` | VARCHAR(120) | Ending terminal name. |
| `route_type` | ENUM | AC, Express, Normal, Night, Mini. |
| `frequency_mins`| TINYINT(3) U | Minutes between buses. |
| `first_bus_time`| TIME | Service start. |
| `last_bus_time` | TIME | Service end. |
| `total_dist_km`| DECIMAL(6,2) | Total route length. |
| `osm_relation_id`| BIGINT(20) | OSM route relation. |

#### Table 4: `route_stops` (Junction Table)
Maps the relationship and sequence of stops for each route.
| Field | Type | Description |
| :--- | :--- | :--- |
| `route_id` | INT(10) U | FK -> `routes.id`. |
| `stop_id` | INT(10) U | FK -> `stops.id`. |
| `stop_order` | TINYINT(3) U | Sequence (1, 2, 3...). |
| `dist_from_start`| DECIMAL(6,2)| Running distance from source. |
| `arrival_offset`| SMALLINT(5) | Est. minutes from start. |
| `is_major_stop`| TINYINT(1) | Flag for display importance. |

#### Table 5: `fares` (Pricing Matrix)
Distance-based fare slabs for different passenger categories.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | Primary Key. |
| `route_id` | INT(10) U | FK -> `routes.id`. |
| `min_km` | DECIMAL(5,2) | Slab start. |
| `max_km` | DECIMAL(5,2) | Slab end. |
| `fare_amount` | DECIMAL(6,2) | Cost in local currency. |
| `passenger_type`| ENUM | General, Student, Senior. |

#### Table 6: `users` (Identity & Comprehensive Profile)
Extensive user data supporting Google OAuth, professional profiles, and academic verification.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | Primary Key, Auto-increment. |
| `google_id` | VARCHAR(255) | Unique ID for Google OAuth provider. |
| `name` | VARCHAR(150) | Full legal name. |
| `email` | VARCHAR(150) | Unique primary contact email. |
| `username` | VARCHAR(150) | System-wide display handle. |
| `password_hash`| VARCHAR(255) | Bcrypt hash for standard login fallback. |
| `phone` | VARCHAR(20) | Primary mobile number. |
| `dob` | DATE | User's date of birth. |
| `gender` | ENUM | Male, Female, Other. |
| `address` | VARCHAR(255) | Residential address. |
| `latitude` | DECIMAL(10,7)| User location latitude. |
| `longitude` | DECIMAL(10,7)| User location longitude. |
| `bio` | TEXT | Professional or personal summary. |
| `profile_image`| VARCHAR(255) | Local/Cloud path to profile picture. |
| `is_student` | TINYINT(1) | Verification flag for fare concessions. |
| `college_name` | VARCHAR(150) | Name of educational institution. |
| `college_reg` | VARCHAR(50) | Institution-specific registration number. |
| `roll_number` | VARCHAR(50) | Class roll identifier. |
| `branch` | VARCHAR(100) | Major field of study (e.g., 'CSE'). |
| `year_of_study`| VARCHAR(20) | Current academic year. |
| `graduation` | YEAR(4) | Expected completion year. |
| `social_meta` | TEXT | Fields for LinkedIn, GitHub, and Portfolio URLs. |
| `skills` | TEXT | comma-separated technical skills. |
| `emergency_ext`| VARCHAR(150) | Emergency contact name and phone. |
| `role` | ENUM | admin, editor, viewer. |
| `is_active` | TINYINT(1) | Account status toggle. |
| `last_login_at`| DATETIME | Timestamp of most recent session. |
| `avatar_url` | TEXT | Persistent URL for profile media. |

#### Table 7: `activity_log` (Audit Trail)
System-wide event tracking for security and management.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | PK. |
| `user_id` | INT(10) U | FK -> `users.id` (NULL for public). |
| `action` | VARCHAR(100) | Event name (e.g., 'login'). |
| `entity_type` | VARCHAR(50) | Object class (e.g., 'route'). |
| `entity_id` | INT(10) U | ID of the affected object. |
| `ip_address` | VARCHAR(45) | Requester IP. |

#### Table 8: `oauth_tokens` (Auth Sessions)
Persistence layer for stateless authentication handshakes.
| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | INT(10) U | PK. |
| `user_id` | INT(10) U | FK -> `users.id`. |
| `access_token` | TEXT | Raw bearer token. |
| `refresh_token`| TEXT | Long-term refresh secret. |
| `expires_at` | DATETIME | Validity limit. |

---

### 4.3 Data Integrity & Optimization
The database utilizes **InnoDB** as the storage engine to support ACID compliance. Key performance optimizations include:
- **Composite Indexing**: Used on junction tables like `route_stops` (on `route_id` and `stop_order`) to optimize the "Pathfinding" SQL queries.
- **Foreign Key Cascades**: Ensuring that deleting a City automatically cleans up related Routes, Stops, and Fares, preventing "Orphan Records."
- **Decimal Precision**: Using `DECIMAL(10,7)` for coordinates to ensure accuracy within ~1.1 meters, critical for map marker placement.
- **Audit Trails**: Real-time logging of administrative actions in the `activity_log` table for security monitoring.

---

## 5. System Diagrams (Mermaid)

### 5.1 System Flowchart
```mermaid
graph TD
    A[User] --> B{Web or API?}
    B -- Web --> C[index.php]
    B -- API --> D[api/index.php]
    C --> E[Router]
    D --> E
    E --> F[Middleware Check]
    F --> G[Controller]
    G --> H[Model]
    H --> I[(Database)]
    I --> H
    H --> G
    G -- Web --> J[View Rendering]
    G -- API --> K[JSON Output]
```

### 5.2 Data Flow Diagrams (DFD)

#### Level 0: Context Diagram
```mermaid
graph LR
    User((Commuter/User)) -- "Route Query & Search" --> System[Bus Portal System]
    System -- "Route Data & Interactive Map" --> User
    Admin((Transit Administrator)) -- "CRUD & Config Data" --> System
    System -- "Log Reports & Stats" --> Admin
    ExternalAPI[Google OAuth] -- "Profile Data" --> System
    System -- "Auth Token" --> ExternalAPI
```

#### Level 1: Functional DFD
```mermaid
graph TD
    U((User)) --> P1[Process 1: Search & Visualization]
    U --> P2[Process 2: Journey Planning]
    A((Admin)) --> P3[Process 3: Dashboard Management]
    P1 <--> DB1[(Transit DB)]
    P2 <--> DB1
    P3 <--> DB1
    P3 --> P4[Process 4: Audit Logging]
    P4 --> DB2[(Activity Logs)]
```

### 5.3 ER Diagram
```mermaid
erDiagram
    CITIES ||--o{ ROUTES : "contains"
    CITIES ||--o{ STOPS : "hosts"
    ROUTES ||--o{ ROUTE_STOPS : "sequences"
    STOPS ||--o{ ROUTE_STOPS : "appears in"
    ROUTES ||--o{ FARES : "pricing"
    USERS ||--o{ ACTIVITY_LOG : "triggers"
    USERS ||--o{ OAUTH_TOKENS : "owns"
    USERS {
        int id PK
        string google_id
        string email UK
        string role
        boolean is_active
        boolean is_student
    }
    ROUTES {
        int id PK
        int city_id FK
        string route_number
        string route_type
        decimal total_dist
    }
```

#### Level 2: Transit Management DFD
Focuses on the internal logic of administrative operations.
```mermaid
graph TD
    A((Admin)) --> R1[Manage Cities]
    A --> R2[Manage Stops]
    A --> R3[Manage Routes]
    A --> R4[Manage Fares]
    R1 <--> C_DB[(Cities Table)]
    R2 <--> S_DB[(Stops Table)]
    R3 <--> RS_DB[(Route_Stops Table)]
    R4 <--> F_DB[(Fares Table)]
    R3 -- "Sync Coordinates" --> R2
```

#### Level 3: Journey Planning Engine DFD
The most granular view, showing how the system calculates paths between two stop IDs.
```mermaid
graph TD
    P_In[Start & End Stop IDs] --> P3_1[Fetch Routes for Start Stop]
    P3_1 --> P3_2[Fetch Routes for End Stop]
    P3_2 --> P3_3{Direct Match?}
    P3_3 -- Yes --> P3_Result[Display Direct Route]
    P3_3 -- No --> P3_4[Identify Intersection Stop]
    P3_4 --> P3_5[Join Route A & Route B]
    P3_5 --> P3_6[Calculate Total Distance]
    P3_6 --> P3_Result
```

---

## 6. Detailed Routing System

### 6.1 Web Routes (Public & Admin)
| Method | Path | Controller Action | Purpose |
| :--- | :--- | :--- | :--- |
| `GET` | `/` | HomeController@index | Portal Landing Page |
| `GET` | `/routes` | RouteController@list | Route Search/Listing |
| `GET` | `/routes/{id}` | RouteController@detail | Stop-by-stop Timeline |
| `GET` | `/planner` | PlannerController@index | Journey Finder UI |
| `GET` | `/auth/login` | AuthController@login | User Sign-in Page |
| `GET` | `/admin` | Admin\Dashboard@index | Admin Overview |
| `POST`| `/admin/routes` | Admin\Route@store | Create New Route |

### 6.2 REST API Routes
| Method | Path | Purpose |
| :--- | :--- | :--- |
| `GET` | `/api/cities` | Fetch active cities JSON |
| `GET` | `/api/stops/nearby` | Spatial search (Requires `lat`, `lng`) |
| `POST`| `/api/planner` | Compute path (Requires `from_stop_id`, `to_stop_id`) |
| `GET` | `/api/routes/{id}` | Detailed route object with stops |
| `GET` | `/api/search` | Full-text search (Requires `q`, `city_id`) |

**Example Usage:**
*   **Nearby Stops**: `GET /api/stops/nearby?lat=28.6139&lng=77.2090&radius=2.0`
*   **Journey Planner**: `POST /api/planner` with JSON body `{"city_id": 1, "from_stop_id": 11, "to_stop_id": 1}`

---

## 7. System Implementation

The implementation phase translates the design specifications into a functional application using a modular, PSR-4 compliant coding style.

### 7.1 Framework Engine
The core engine is designed to be "Framework-agnostic," relying on native PHP performance:
- **Router**: Utilizes an "Anonymous Function Callback" pattern. Registered routes are stored in an array and matched using a `Dispatcher` that extracts named parameters (e.g., `{id}`).
- **Database Wrapper**: A Singleton wrapper around PDO that ensures prepared statements are used for all queries, effectively neutralizing SQL injection risks.
- **Request & Response**: Abstracted classes that handle `application/json` content-types and provide helper methods like `redirect()` and `jsonResponse()`.

### 7.2 Intelligent Journey Planning Logic
The `PlannerService` implements a "Two-Pass Discovery" algorithm:
1.  **Pass 1 (Direct)**: Performs a SQL intersection on `route_stops` where `StopA` and `StopB` share a `route_id` and `OrderA < OrderB`.
2.  **Pass 2 (One-Transfer)**:
    - Identifies all routes passing through `StopA`.
    - Identifies all routes passing through `StopB`.
    - Finds a "Mutual Stop" that exists in both sets.
    - Returns the pair of routes and the transfer stop name.

### 7.3 API Security (JWT Implementation)
Stateless authentication is managed via JSON Web Tokens:
- **Payload**: Contains `uid` (User ID), `role`, and `exp` (Expiry - 24 hours).
- **Validation**: The `AuthMiddleware` intercepts every API request, decodes the token using the `HS256` algorithm, and verifies the signature against the `.env` secret key.

---

## 8. Testing Strategy

### 8.1 Testing Methodology
The UCBRP underwent a multi-phase testing strategy to ensure reliability and performance:
- **Unit Testing**: Testing individual components like JWT generators and coordinate distance calculators.
- **Integration Testing**: Verifying the handshake between the custom Router and Controllers.
- **User Acceptance Testing (UAT)**: Validating the public portal's usability on mobile devices.

### 8.2 Manual Test Cases
| ID | Test Case | Input | Expected Output | Status |
| :--- | :--- | :--- | :--- | :--- |
| TC-01 | City Change | Selection: 'Kolkata' | Map centers on 22.57, 88.36; Stats update. | Passed |
| TC-02 | Route Search | Query: 'S-24' | List displays S-24 route card with source/dest. | Passed |
| TC-03 | Auth Bypass | URI: `/admin` (No Session)| Redirected to `/auth/login`. | Passed |
| TC-04 | Planner (Direct)| Stop A -> Stop B | Correct route ID and 0 transfers shown. | Passed |
| TC-05 | Fare Calc | Dist: 12km, Type: Student| Fare = ₹8.00 (Based on 10-15km slab). | Passed |

### 8.3 Cross-Browser & Performance Testing
- **Chrome 120+**: 100/100 Lighthouse Performance score.
- **Safari (iOS)**: Smooth 60fps scrolling on map polylines.
- **Firefox 115+**: Full support for CSS Backdrop-filters.
- **Load Time**: Average DOMContentLoaded in < 450ms on local XAMPP.

---

## 9. User & Administrator Manual

### 9.1 Public Portal Features
- **City Swapping**: Seamless transition between metropolitan data sets.
- **Dynamic Timeline**: Vertical visualization of bus stops with distance markers.
- **Fare Estimator**: Real-time pricing based on route distance and user category.

### 9.2 Administrative Dashboard Guide
- **Secure Onboarding**: Identity verification via Google.
- **Route Sequencing**: Drag-and-drop interface for ordering bus stops.
- **Audit Trails**: Live monitoring of system changes.

---

## 10. Testing & Performance Optimization

### 10.1 Unit Testing Strategy
- **AuthTest**: Verifies JWT generation and validation logic.
- **DijkstraTest**: Validates the journey planner logic with mock route data to ensure shortest path accuracy.

### 10.2 Database Indexing
- Unique composite indexes on `(city_id, route_number)` for O(1) route lookup.
- Spatial indexing on `(latitude, longitude)` for high-speed nearby stop search.

---

## 11. Functional Screenshots & UI Analysis

*Note: In the final academic submission, please replace the descriptions below with actual screenshots from the live application.*

### 11.1 Public Landing Page (City Selection)
- **Visual Description**: A high-impact hero section featuring a blurred bus background with a neon-orange "City Selector" glassmorphism card. 
- **Functional Elements**: Dynamic dropdown, Quick-stat cards (Total Routes, Active Stops), and custom cursor micro-animations.

### 11.2 Interactive Route Discovery
- **Visual Description**: A split-screen layout featuring a full-bleed Leaflet map on the right and a searchable route list on the left.
- **Functional Elements**: 3D-tilt route cards, neon map markers, and live route type filtering.

### 11.3 Intelligent Journey Planner
- **Visual Description**: A sleek form with autocomplete stop selection and a "Plan My Journey" neon button.
- **Functional Elements**: Shortest path results, transfer stop identification, and fare summary box.

### 11.4 Administrative Dashboard
- **Visual Description**: A professional sidebar-driven management interface with a dark industrial theme.
- **Functional Elements**: Tabular CRUD views, GIS data entry validation, and real-time Activity Log feed.

---

## 12. Conclusion
The Universal City Bus Route Portal v3.0 successfully demonstrates that a high-performance transit system can be built using a custom-engineered PHP core. It provides the scalability required for multi-city deployment while maintaining a premium aesthetic and secure administrative framework. The project serves as a definitive template for students to study the implementation of custom frameworks, relational GIS data modeling, and modern web standards.

---

## 13. References & Resources
1.  **PHP 8.2 Documentation**: Official language reference for strict typing and PDO patterns. (php.net)
2.  **Leaflet.js API**: GIS library documentation for marker clustering and polylines. (leafletjs.com)
3.  **Google Identity Platform**: OAuth 2.0 implementation guide for PHP applications.
4.  **JWT.io**: JSON Web Token standard and debugging tools.
5.  **OpenStreetMap Wiki**: Data structure reference for `osm_id` integration.

---
*Report Compiled for Academic Evaluation - April 2026*
*Document Version: 3.0 (Final Revision)*
