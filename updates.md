# Project Updates — Bus Route Portal

## 🚀 Recent Changes & Improvements

### 1. Architectural & Routing Fixes
- **Root Redirection**: Implemented a root `.htaccess` file to automatically and transparently route all traffic to the `public/` directory, fixing the "Index of" directory listing issue.
- **Environment Configuration**: Fully integrated `.env` support for sensitive credentials (Google Client ID/Secret, Database, ORS API Key).

### 2. Frontend Design Overhaul (Premium UI)
- **Aesthetic Shift**: Replaced the default Bootstrap look with a high-end, dark-themed aesthetic inspired by modern design standards.
- **Typography System**: Integrated `Cormorant Garamond` for display headings and `Lato` for body text.
- **Visual Effects**: Added ambient glow effects, noise textures, and glassmorphism elements across all public and admin pages.
- **Component Restyling**:
    - **Home Page**: New hero section, search interaction, and popular route cards.
    - **Routes & Details**: Modernized list views and stop timelines with premium borders and spacing.
    - **Planner**: Integrated a side-panel journey planner with a large map view.

### 3. Authentication & Security
- **Google OAuth 2.0**: Implemented secure sign-in via Google.
- **Separate Login Flows**:
    - Created a dedicated **Admin Access** portal (`/auth/adminLogin`) for authorized personnel.
    - Simplified the **User Login** flow for public users to access personalized features.
- **Role-Based Access Control (RBAC)**:
    - Default sign-ups are assigned the `viewer` role.
    - Automatic redirection: Admins go to the Dashboard; Viewers go to the Home Page.
    - Strict 403 Forbidden protection for all `/admin` routes.

### 4. Admin Dashboard Enhancements
- **Restyled Dashboard**: Overhauled the management hub with a sleek dark sidebar and interactive stat cards.
- **Data Management**: Updated Cities and Routes management interfaces with "ghost" table designs and premium action buttons.
- **Emoji Purge**: Removed all colorful emojis and decorative symbols across the entire codebase to maintain a professional, clean interface.

### 5. Interaction Design (UX)
- **Premium Buttons**: Added shimmering light-sweep effects and directional slide animations to all primary buttons and links.
- **Navigational Clarity**: Implemented sliding underlines in the header to indicate the active page.
- **User Profile**: Added a "User Pill" in the header to show the signed-in user's name and avatar.

### 6. Technical Integrations
- **Dijkstra Algorithm**: Implemented for efficient pathfinding between bus stops.
- **Routing Service**: Integrated OpenRouteService (ORS) for GeoJSON-based map rendering and turn-by-turn directions.
- **CSRF Protection**: Standardized CSRF token generation and validation across all forms.

---
*Last Updated: April 26, 2026*
