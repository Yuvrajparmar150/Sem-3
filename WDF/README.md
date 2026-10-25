# 🎓 StudentHub — Unified Academic & Campus Web Portal
### Course: Web Development Frameworks (`ITUE203`)
**Faculty of Technology & Engineering (FTE) | CHARUSAT**  
**Semester:** 3rd Semester, B.Tech Computer Engineering (Odd 2026-27)  
**Developer:** Yuvraj Parmar | **Enrollment No.:** `25DCE070`  

---

## 🌟 Project Overview

**StudentHub** is an enterprise-grade, responsive, and secure academic campus web portal built to fulfill the complete laboratory curriculum for **Practicals 1 through 15** of the Web Development Frameworks (`ITUE203`) course at CHARUSAT.

The portal provides an end-to-end digital campus experience featuring:
- **Frontend Layer (Practicals 1–6):** 16 semantic, accessible HTML5 pages styled with a pure Vanilla CSS design system (tokens, CSS Grid, Flexbox, Light/Dark themes) and interactive JavaScript components (hero slider, accordions, modal dialogs, Canvas CAPTCHA, debounced search, category filters, and Fetch API rendering).
- **File-Based Form Backend (Practical 7):** Server-side PHP processors providing input sanitization, regular expression validation, and atomic concurrency-safe storage in structured JSON and CSV formats.
- **Relational Database & Authentication Layer (Practicals 8–10):** Normalized MySQL schema (`users`, `students`, `events`, `registrations`, `audit_logs`), secure PDO connectivity with emulated prepares disabled, bcrypt password hashing via `password_hash()`, PHP session management with `session_regenerate_id()`, SHA-256 hashed persistent remember-me tokens, 15-minute inactivity timeout, and strict Role-Based Access Control (RBAC: Admin vs. Student).
- **Full-Stack Data Management & File Security (Practicals 11–12):** Complete CRUD administration for students (multi-column search, course filters, pagination, soft delete, CSV export) and campus events (poster image upload, MIME validation via `finfo`/`getimagesize`, random cryptographic renaming, and script execution prevention via `.htaccess`).
- **RESTful JSON API & Single-Page AJAX CRUD (Practical 13):** Asynchronous endpoints (`api/students_api.php`, `api/events_api.php`) consumed by a modern Fetch API single-page dashboard without page reloads, accompanied by standard HTTP status codes (`200`, `201`, `400`, `401`, `403`, `404`, `409`, `422`).
- **Administrative Intelligence & Audit Trail (Practical 14):** Real-time SQL aggregation metrics, graphical monthly registration distribution charts, role-sensitive dynamic navigation menus, user active/inactive status toggles, and security audit log inspection.
- **Verification, Testing & Deployment (Practical 15):** Comprehensive automated test suite (`tests/p15_results.json`) validating navigation integrity, authentication barriers, SQL injection defense, CSRF mitigation, XSS output escaping, upload shields, and full local Apache/XAMPP deployment.

---

## 📊 Syllabus Practical Summary (Practicals 1 to 15)

| Practical No. | Practical Title | Core Technology / Concept | CO Mapping | Status |
| :---: | :--- | :--- | :---: | :---: |
| **P1** | Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub Setup | Requirements, Sitemap, Wireframes, Git/GitHub | `CO1` | ✅ Completed |
| **P2** | Semantic HTML5 Pages with Accessibility-Ready Structure | HTML5 Semantics, WCAG Accessibility, ARIA | `CO1, CO2` | ✅ Completed |
| **P3** | Responsive UI Design using CSS2.0/CSS3.0, CSS Grid and Flexbox | CSS Grid, Flexbox, Media Queries, Design Tokens | `CO2` | ✅ Completed |
| **P4** | JavaScript DOM Manipulation, Event Handling, and UI Interactivity | DOM Manipulation, Sliders, Modals, LocalStorage | `CO3, CO4` | ✅ Completed |
| **P5** | Registration Form with Frontend Validation & User-Friendly Error Handling | Form Validation, Regex, Password Meter, Canvas CAPTCHA | `CO1, CO3` | ✅ Completed |
| **P6** | Rendering External JSON Data using Fetch API, Search and Filter | JSON Datasets, Fetch API, Search, Filter, Pagination | `CO3, CO4` | ✅ Completed |
| **P7** | PHP Form Processing with Server-Side Validation and CSV/JSON File Storage | PHP POST Handling, Sanitization, CSV/JSON Storage | `CO1, CO5` | ✅ Completed |
| **P8** | MySQL Schema Design, ER Model, PDO Connectivity, and Prepared Statements | Relational Schema, Foreign Keys, PDO, Prepared Queries | `CO1, CO5` | ✅ Completed |
| **P9** | Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing | Duplicate Check, `password_hash()`, CSRF, Audit Logs | `CO1, CO3, CO5` | ✅ Completed |
| **P10** | Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token | Sessions, `password_verify()`, RBAC, Inactivity Timeout | `CO1, CO5` | ✅ Completed |
| **P11** | Student Management CRUD with Search, Filter, and Prepared Statements | Student CRUD, Multi-field Search, Soft Delete, CSV Export | `CO1, CO4, CO5` | ✅ Completed |
| **P12** | Event Management CRUD with Poster Upload and File Validation | Event CRUD, MIME Verification, File Size Check, `.htaccess` | `CO1, CO4, CO5` | ✅ Completed |
| **P13** | PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload | REST JSON API, Fetch API, Debounced Search, Status Codes | `CO3, CO4, CO5` | ✅ Completed |
| **P14** | Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log | SQL Aggregation, Monthly Chart, User Toggle, Audit Trail | `CO1, CO4, CO5` | ✅ Completed |
| **P15** | Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment | End-to-End Testing, Security Checks, Local Deployment | `CO2, CO4, CO5` | ✅ Completed |

---

## 🗺️ Website Navigation Paths & User Workflows

```text
Visitor / Student                                         Administrator
-----------------                                         -------------
index.html (Landing)                                      login.php (Role: Admin)
  ├── events.html (JSON Events Explorer)                    │
  ├── faq.html (Collapsible Knowledgebase)                  ├── admin_dashboard.php (P14: Metrics, Charts, Logs)
  ├── register.html (P5/P7 File Registration)               ├── students_crud.php (P11: Student CRUD & CSV Export)
  ├── register_db.php (P9: DB Registration)                ├── events_crud.php (P12: Event CRUD & Poster Upload)
  │                                                         ├── ajax_crud.php (P13: SPA Fetch API CRUD)
  └── login.php (P10: Role-Based Authentication)            ├── submissions.php (P7: CSV/JSON Record Viewer)
        ├── Role: Student ──> my_dashboard.php (P10)        └── db_test.php (P8: Database Diagnostics)
        └── Role: Admin   ──> admin_dashboard.php (P14)
```

### 1. 🚀 Visitor & Public Exploration Flow (Practicals 1–4)
- **Home Landing (`index.html`):** Hero carousel slider with pause-on-hover, live campus statistics, quick action cards, announcement ticker, and dark/light theme switcher.
- **Events Explorer (`events.html`):** Dynamic catalog populated via `events.json`, debounced live search, category filter pills (Technical, Cultural, Sports), sorting, and client-side pagination.
- **Knowledge Base (`faq.html`):** Collapsible accordion with smooth animations, search query filter, and category tags.
- **Institutional Profile (`about.html`):** Department information, faculty directory, accreditation badges, and developer profile.

### 2. 📝 Client & Server Form Processing Flow (Practicals 5 & 7)
- **Form Interface (`register.html` & `contact.html`):** Real-time client-side regex feedback, visual password strength meter, HTML5 Canvas CAPTCHA with distortion lines, and dependent Country ➔ State ➔ City cascading dropdowns.
- **File Storage Processing (`php/process_register.php`, `process_contact.php`, `process_feedback.php`):** Validates and sanitizes input data, safely appending records into `data/registrations.json` (via `flock` / `LOCK_EX`) and `data/registrations.csv` (via `fputcsv()`).
- **Records Viewer (`admin.html` & `php/submissions.php`):** Inspect file-based submissions via client-side JavaScript or server-rendered PHP tables.

### 3. 🔐 Relational Database Authentication & RBAC Flow (Practicals 8–10)
- **Secure Registration (`php/register_db.php`):** Validates input, verifies that the email does not already exist in the `users` table, securely hashes passwords using `password_hash($pass, PASSWORD_DEFAULT)`, binds parameters to an `INSERT` statement, verifies anti-CSRF tokens, and logs the event in `audit_logs`.
- **Secure Login (`php/login.php`):** Verifies email existence and active status, cryptographically compares passwords using `password_verify()`, regenerates the session ID (`session_regenerate_id(true)`), handles optional 30-day SHA-256 remember-me cookies (`sh_remember`), and automatically routes users:
  - Users with role `admin` are routed to [`admin_dashboard.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/admin_dashboard.php).
  - Users with role `student` are routed to [`my_dashboard.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/my_dashboard.php).
- **Session Protection & Timeout (`php/layout.php`):** Inactivity timer terminates idle sessions after 900 seconds (15 minutes). Any attempt by a student to directly open admin pages triggers an immediate `403 Forbidden` block.

### 4. 🛠️ Administrative Management & CRUD Workflows (Practicals 11–14)
- **Student Management CRUD (`php/students_crud.php`):** Full data grid listing all active students. Supports live search across Name, Email, and Enrollment ID, course dropdown filtering, pagination, modal for adding/editing students with server-side validation, soft delete (`status = 'deleted'`), and one-click CSV export (`action=export`).
- **Event Management CRUD & File Upload (`php/events_crud.php`):** Event creation and updates with poster image attachments. Validates file size (≤ 2 MB), validates image MIME type using `finfo` and `getimagesize`, renames files cryptographically to prevent file collisions/traversals, stores them in `uploads/posters/`, and blocks script execution using an Apache `.htaccess` rule.
- **AJAX Single-Page CRUD (`php/ajax_crud.php`):** Interactively switches between Students API and Events API tabs. Executes asynchronous Create, Read, Update, and Delete operations using JavaScript `fetch()`, with real-time UI state indicators (Idle, Loading, Success, Error) and zero full-page reloads.
- **Admin Dashboard & Monitoring (`php/admin_dashboard.php`):** Real-time SQL aggregation stat cards (Total Users, Active Students, Open Events, Total Registrations), CSS gradient-based monthly registration histogram, active/inactive user status toggle buttons, and an audit trail inspecting recent security actions.

---

## 🧭 Complete Page Directory & Feature Reference

| Page / File Path | Associated Practical | Purpose & Key Technical Features |
| :--- | :---: | :--- |
| [`index.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/index.html) | P2, P3, P4 | **Main Landing Portal**: Hero carousel slider, live metric cards, quick-action tiles, announcements, and theme switcher. |
| [`register.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/register.html) | P2, P5, P7 | **Student Registration**: Real-time Regex validation, live password strength meter, HTML5 Canvas CAPTCHA, dependent dropdowns, and file POST submission. |
| [`events.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/events.html) | P3, P6 | **Campus Events Explorer**: External JSON fetch (`events.json`), real-time debounced search, category filter pills, sorting, and pagination. |
| [`faq.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/faq.html) | P2, P4, P6 | **Knowledge Base & FAQs**: Dynamic accordion rendering from `faqs.json`, category filters, and live question search. |
| [`dashboard.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/dashboard.html) | P2, P3 | **Student Dashboard**: Overall attendance gauge, current semester GPA, today's schedule, and pending assignment alerts. |
| [`profile.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/profile.html) | P2, P3 | **Student Profile**: Academic bio, enrollment info, semester progression, skills tags, and contact details. |
| [`attendance.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/attendance.html) | P2, P3 | **Attendance Management**: Subject-wise percentage bars, eligibility status indicators, and leave application form. |
| [`timetable.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/timetable.html) | P2, P3 | **Weekly Class Schedule**: Daily timetable grid, lecture hall details, and lab batch divisions. |
| [`assignments.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/assignments.html) | P2, P4 | **Assignments & Labs**: Practical submission status, deadlines, download prompts, and upload modal dialog. |
| [`results.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/results.html) | P2, P3 | **Examination Results**: Semester grade points, SGPA / CGPA calculations, and grade breakdown. |
| [`about.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/about.html) | P2, P3 | **Institutional Overview**: Department information, faculty directory, vision, mission, and accreditation badges. |
| [`contact.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/contact.html) | P2, P5, P7 | **Helpdesk Contact**: Campus location, contact form with validation, processed by `php/process_contact.php`. |
| [`feedback.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/feedback.html) | P2, P4, P7 | **Portal Feedback**: Interactive star rating widget, feedback textarea, processed by `php/process_feedback.php`. |
| [`login.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/login.html) | P2, P5 | **Portal Authentication**: Static login mockup with role switcher (Student / Administrator) and demo shortcuts. |
| [`settings.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/settings.html) | P2, P4 | **User Settings**: Dark/Light theme toggle, notification preferences, and local cache controls. |
| [`admin.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/admin.html) | P6, P7 | **Admin Submissions Portal**: Frontend dashboard displaying stored JSON/CSV registrations and inquiries. |
| [`php/submissions.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/submissions.php) | P7 | **PHP Submissions Viewer**: Server-rendered PHP page reading and presenting file-based records in tables. |
| [`php/config.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/config.php) | P8, P10 | **Database Configuration**: Driver selection (MySQL/SQLite), environment variable bindings, and session timeout setting. |
| [`php/db.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/db.php) | P8 | **PDO Database Connector**: Secure singleton PDO connection, disabled emulated prepares, error logging, and cross-driver helper. |
| [`php/db_test.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/db_test.php) | P8 | **Connection Diagnostic**: Displays server versions, table row tallies, prepared statement verification, and table JOIN tests. |
| [`php/register_db.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/register_db.php) | P9 | **Secure DB Registration**: Backend regex validation, duplicate email check, `password_hash()`, prepared insert, and CSRF check. |
| [`php/login.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/login.php) | P10 | **Secure Login Processor**: `password_verify()`, session regeneration, role redirection (Admin/Student), and remember-me support. |
| [`php/logout.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/logout.php) | P10 | **Logout Handler**: Clears session arrays, destroys cookie tokens, unsets remember-me cookies, and redirects safely. |
| [`php/my_dashboard.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/my_dashboard.php) | P10 | **Protected Student Dashboard**: Verifies student authentication, displays last login time, timeout remaining, and registration count. |
| [`php/students_crud.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/students_crud.php) | P11 | **Student Management CRUD**: Admin-only student management with search, filter, pagination, soft-delete, and CSV export. |
| [`php/events_crud.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/events_crud.php) | P12 | **Event Management CRUD**: Admin-only event management with poster upload, MIME checking, size limits, and audit tracking. |
| [`php/ajax_crud.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/ajax_crud.php) | P13 | **Single-Page AJAX CRUD**: Interactive Fetch API interface for managing students and events with zero page reload. |
| [`php/api/students_api.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/api/students_api.php) | P13 | **Students RESTful JSON API**: GET (list/detail with search/pagination) and POST (create/update/delete) with HTTP status codes. |
| [`php/api/events_api.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/api/events_api.php) | P13 | **Events RESTful JSON API**: GET and POST endpoints handling event listing, search, additions, updates, and soft/hard removals. |
| [`php/admin_dashboard.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/admin_dashboard.php) | P14 | **Role-Based Admin Dashboard**: SQL aggregation metrics, monthly registration histogram, user status toggle, and audit log viewer. |
| [`tests/p15_results.json`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/tests/p15_results.json) | P15 | **Verification Test Suite**: Comprehensive automated test results covering 40+ security, RBAC, API, and accessibility tests. |

---

## 📋 Detailed Practical Coverage (Practicals 1 to 15)

### Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub Setup
- **Course Outcome:** `CO1`
- **Scope & Objectives:** Defined problem scope, user personas (Student, Faculty, Admin), sitemap architecture, low-fidelity wireframes, structured project directory, and version control setup.
- **Key Artifacts & Deliverables:**
  - `docs/Requirement analysis.docx`: Functional requirements and module specifications.
  - `docs/Site Map and Wire frame.docx`: Navigation hierarchy and UI wireframe designs.
  - `docs/Website preview.docx`: Visual previews of interface design.
  - Clean modular folder structure (`css/`, `js/`, `php/`, `data/`, `docs/`, `sql/`, `tests/`, `uploads/`).
- **Intermediate / Advanced Extension:** Created comprehensive responsive layout plans and structured REST-style API route mapping.

---

### Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure
- **Course Outcome:** `CO1, CO2`
- **Scope & Objectives:** Built 16 standard-compliant HTML5 pages ensuring semantic markup, correct document outline, and high accessibility (a11y).
- **Key Features Implemented:**
  - Semantic landmark elements across all pages: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, and `<footer>`.
  - Accessible skip-to-content links (`<a href="#main-content" class="skip-link">`) for keyboard navigation.
  - Explicit form associations: `<label for="...">`, `aria-required`, `aria-describedby`, and accessible error containers.
  - Informative `alt` text for images, clear heading hierarchy (`<h1>` to `<h3>`), and breadcrumb navigation.
- **Intermediate / Advanced Extension:** Implemented structured breadcrumbs and complete keyboard focus trap mechanisms.

---

### Practical 3: Responsive UI Design using CSS2.0/CSS3.0, CSS Grid and Flexbox
- **Course Outcome:** `CO2`
- **Scope & Objectives:** Mobile-first, responsive user interface developed with pure Vanilla CSS, utilizing CSS Grid and Flexbox layouts without external heavyweight CSS frameworks.
- **Key Features Implemented:**
  - **CSS Custom Properties (Design Tokens):** Centralized theme palette in `css/style.css` supporting both Light Mode and Dark Mode (`--bg-primary`, `--text-primary`, `--primary-color`, `--card-bg`, `--border-color`).
  - **CSS Grid Layouts:** Multi-column responsive card grids (`grid-template-columns: repeat(auto-fit, minmax(280px, 1fr))`) for events, statistics, and timetables.
  - **CSS Flexbox:** Alignment for navigation header, action buttons, breadcrumbs, and pagination controls.
  - **Responsive Media Queries:** Fluid breakpoints (`@media (max-width: 992px)`, `@media (max-width: 768px)`, `@media (max-width: 480px)`) ensuring seamless display on mobile phones, tablets, and desktops.
- **Intermediate / Advanced Extension:** Implemented reusable component utility classes and smooth card hover transitions.

---

### Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity
- **Course Outcome:** `CO3, CO4`
- **Scope & Objectives:** Added dynamic client-side interactivity using modern JavaScript (ES6+), DOM element selection, and custom event listeners.
- **Key Features Implemented:**
  - **Hero Content Carousel / Slider:** Auto-advancing slides with pause-on-hover, previous/next navigation buttons, and indicator dots on `index.html`.
  - **Collapsible FAQ Accordion:** Interactive accordion expanding/collapsing questions with animated chevron indicators on `faq.html`.
  - **Accessible Modal Dialogs:** Registration and event details popups with backdrop overlay, ESC key closing, and focus traps.
  - **Dynamic Toast Notifications:** Non-intrusive floating feedback messages for user actions.
  - **Mobile Hamburger Drawer:** Off-canvas navigation menu triggered by hamburger button on mobile viewports.
  - **Theme Switcher:** Dark/Light theme toggle that persists the user's choice in browser `localStorage`.
- **Intermediate / Advanced Extension:** Restored user UI theme preferences immediately on page load to prevent theme flickering.

---

### Practical 5: Registration Form with Frontend Validation & User-Friendly Error Handling
- **Course Outcome:** `CO1, CO3`
- **Scope & Objectives:** Designed an accessible student registration form (`register.html`) with real-time input validation, regular expressions, visual strength feedback, and custom CAPTCHA.
- **Key Features Implemented:**
  - **Regular Expression Validations:**
    - Full Name: Minimum 3 characters, alphabetical with spaces (`/^[a-zA-Z\s]{3,50}$/`).
    - Student ID: Institutional enrollment format (`/^2[0-9][A-Z]{3}[0-9]{3}$/i`).
    - Email Address: Standard RFC-compliant email pattern.
    - Phone Number: 10-digit mobile number starting with 6-9 (`/^[6-9]\d{9}$/`).
    - Password: Minimum 8 characters with uppercase, lowercase, digit, and special character.
    - Confirm Password: Strict equality match with primary password.
  - **Live Password Strength Meter:** Interactive multi-segment strength bar (Weak, Moderate, Strong) with a dynamic criteria checklist.
  - **HTML5 Canvas CAPTCHA:** Custom alphanumeric CAPTCHA drawn directly onto an HTML5 `<canvas>` with randomized angles, colored distortion lines, background noise, and a refresh button.
  - **Real-Time UX Error Hints:** Instant feedback displayed on `keyup` and `blur` events with accessible error text.
- **Intermediate / Advanced Extension:** Real-time validation listeners and anti-bot HTML5 Canvas CAPTCHA engine.

---

### Practical 6: Rendering External JSON Data using Fetch API, Search and Filter
- **Course Outcome:** `CO3, CO4`
- **Scope & Objectives:** Asynchronously loaded and rendered structured data from external JSON files using the JavaScript Fetch API, featuring search, multi-criteria filtering, and pagination.
- **Key Features Implemented:**
  - **External JSON Datasets (15+ records each):**
    - `data/events.json`: Campus hackathons, workshops, seminars, venues, dates, and seat availability.
    - `data/students.json`: Student directory profiles with GPA, branches, and skill tags.
    - `data/faqs.json`: Categorized frequently asked questions and answers.
    - `data/notices.json`: Real-time campus announcements and academic circulars.
    - `data/locations.json`: Hierarchical Country ➔ State ➔ City dataset for dependent dropdowns.
  - **Real-Time Debounced Search:** Client-side search bar filtering records dynamically as the user types (350 ms debounce).
  - **Category Filter Pills & Sorting:** Instant filtering by event categories (Technical, Cultural, Sports, Academic) and sorting by date, title, or seat availability.
  - **Client-Side Pagination:** Modular page slicing (6 items per page) with Previous, Next, and numbered page buttons.
  - **Dependent Cascading Dropdowns:** Country selection automatically populates relevant States, which in turn populates Cities on `register.html`.
- **Intermediate / Advanced Extension:** Dependent cascading dropdowns and response caching in browser storage.

---

### Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage
- **Course Outcome:** `CO1, CO5`
- **Scope & Objectives:** Developed secure server-side form handling using PHP, performing server validation, sanitization, and persistent storage into both JSON and CSV files without database dependency.
- **Key Features Implemented:**
  - **HTTP POST Request Handlers:**
    - `php/process_register.php`: Processes student registration submissions.
    - `php/process_contact.php`: Processes contact helpdesk inquiries.
    - `php/process_feedback.php`: Processes student ratings and feedback submissions.
  - **Server-Side Sanitization & Validation:** Strict `POST` method verification, input sanitization via `htmlspecialchars()`, `trim()`, and `stripslashes()`, and format validation using `filter_var($email, FILTER_VALIDATE_EMAIL)` and server-side Regex matching.
  - **Safe & Atomic File Storage:** Appends records to structured JSON files (`data/registrations.json`) using `LOCK_EX` to prevent race conditions, and concurrently writes records into CSV files (`data/registrations.csv`) using `fputcsv()`.
  - **Submissions Data Viewer:** `admin.html` (client-side reader) and `php/submissions.php` (server-rendered PHP table with formatted dates and status badges).
- **Intermediate / Advanced Extension:** Real-time web display of stored records and CSRF token verification.

---

### Practical 8: MySQL Schema Design, ER Model, PDO Connectivity, and Prepared Statements
- **Course Outcome:** `CO1, CO5`
- **Scope & Objectives:** Architected and normalized a relational database for StudentHub (`studenthub`), created primary/foreign key relationships with cascade rules, and implemented a secure PHP PDO database connector using prepared statements.
- **Database Schema (`sql/studenthub_mysql.sql`):**
  - `users`: User identity, email, bcrypt password hash, role (`admin`/`student`), status (`active`/`inactive`), remember-me token hash, and last login timestamp.
  - `students`: Enrollment number (`UNIQUE`), full name, email (`UNIQUE`), mobile, course, academic year, gender, CGPA, and status (`active`/`deleted` for soft-deletion).
  - `events`: Event title, category, date, venue, seats, description, poster image path, status (`open`/`closed`), and `created_by` foreign key referencing `users(id)`.
  - `registrations`: Bridge table linking `student_id` and `event_id` with a `UNIQUE KEY (student_id, event_id)` constraint preventing duplicate registrations.
  - `audit_logs`: Audit trail tracking `user_id`, `action`, `details`, client `ip_address`, and timestamp.
- **Secure Connector (`php/db.php` & `php/config.php`):**
  - Employs PHP Data Objects (PDO) with `PDO::ATTR_EMULATE_PREPARES => false` to force real server-side prepared statements and prevent SQL injection.
  - Configured with `PDO::ERRMODE_EXCEPTION` and custom logging (`error_log`) so raw database error messages are never leaked to clients.
  - Supports dual database drivers: native MySQL for production/XAMPP and an automated SQLite fallback (`data/studenthub.sqlite`) for self-contained demonstration.
- **Diagnostic Tool (`php/db_test.php`):**
  - Displays database engine versions, table row tallies, prepared statement execution tests (`SELECT ... WHERE enrollment_no = ?`), and multi-table `JOIN` tests (`registrations` ➔ `students` ➔ `events`).
- **Intermediate / Advanced Extension:** Prepared statements across all queries and cross-platform date-bucketing helper (`sql_month()`).

---

### Practical 9: Secure User Registration with Database Insert, Duplicate Email Check, and Password Hashing
- **Course Outcome:** `CO1, CO3, CO5`
- **Scope & Objectives:** Implemented secure account registration saving credentials to the database, enforcing server-side regex validation, duplicate identity checks, and cryptographic password hashing.
- **Key Features Implemented (`php/register_db.php`):**
  - **Duplicate Account Verification:** Executes a prepared query (`SELECT id FROM users WHERE email = ?`) before insertion; duplicate attempts are blocked with clear feedback.
  - **Cryptographic Password Hashing:** Uses PHP's `password_hash($pass, PASSWORD_DEFAULT)` generating strong bcrypt hashes (e.g. `$2y$10$...`); plaintext passwords are never stored.
  - **Cross-Site Request Forgery (CSRF) Defense:** Generates and verifies a cryptographically secure token (`csrf_token()`) on form submission.
  - **Audit Trail Logging:** Automatically writes successful registrations and duplicate registration attempts to the `audit_logs` table.
- **Intermediate / Advanced Extension:** Integrated CSRF token validation and automated audit logging on registration events.

---

### Practical 10: Secure Login/Logout with Sessions, Role-Based Access, Timeout, and Remember-Me Token
- **Course Outcome:** `CO1, CO5`
- **Scope & Objectives:** Developed a session authentication module enforcing `password_verify()`, session fixation defense, role-based access control (RBAC), inactivity timeouts, and persistent login tokens.
- **Key Features Implemented (`php/login.php`, `php/logout.php`, `php/layout.php`):**
  - **Password Verification:** Authenticates users via `password_verify($password, $user['password_hash'])`.
  - **Session Fixation Prevention:** Calls `session_regenerate_id(true)` immediately upon successful authentication.
  - **Cookie Security:** Enforces `HttpOnly` and `SameSite=Lax` flags on session and remember cookies to mitigate XSS-based cookie theft.
  - **Inactivity Session Timeout:** Automatically destroys sessions after 900 seconds (15 minutes) of inactivity and redirects the user with an expiration notice (`?timeout=1`).
  - **Persistent Remember-Me Authentication:** Generates a 32-byte cryptographically random token, stores its SHA-256 hash in the database, sets an HTTP-only cookie (`sh_remember`), and verifies it on subsequent visits without storing plaintext tokens.
  - **Role-Based Access Control (RBAC):** Restricts administrative endpoints (`require_login(['admin'])`). Unauthorized access attempts by students or visitors are rejected with an HTTP `403 Forbidden` response.
  - **Complete Logout (`php/logout.php`):** Destroys the session array, expires the cookie in the browser, unsets persistent tokens in the database, and redirects to login with confirmation.
- **Intermediate / Advanced Extension:** Last login timestamp tracking in `users.last_login` and SHA-256 hashed persistent remember-me tokens.

---

### Practical 11: Student Management CRUD with Search, Filter, and Prepared Statements
- **Course Outcome:** `CO1, CO4, CO5`
- **Scope & Objectives:** Developed an administrative management module (`php/students_crud.php`) to view, add, update, search, filter, and delete student records using secure prepared statements.
- **Key Features Implemented:**
  - **Search & Multi-Criteria Filtering:** Live search filtering across Name, Email, and Enrollment ID combined with course filter dropdowns.
  - **Pagination:** Dynamic limit/offset SQL pagination rendering 8 students per page with page navigation controls.
  - **Modal Add & Edit Forms:** In-place editing modal with server-side validation against enrollment number patterns, email formats, and mobile numbers.
  - **Soft Delete Mechanism:** Deleting a student marks `status = 'deleted'` rather than executing a hard SQL deletion, preserving academic history and foreign key references.
  - **One-Click CSV Export:** Implemented an export action (`students_crud.php?action=export`) generating standard RFC-compliant CSV files on the fly.
- **Intermediate / Advanced Extension:** Integrated soft delete preservation and downloadable CSV roster export.

---

### Practical 12: Event Management CRUD with Poster Upload and File Validation
- **Course Outcome:** `CO1, CO4, CO5`
- **Scope & Objectives:** Created an event administration module (`php/events_crud.php`) supporting event creation, status updates, deletions, and secure poster file uploads.
- **Key Features Implemented:**
  - **Secure Multi-Stage Upload Pipeline:**
    - Size Restriction: Strictly caps poster files to 2 MB (handled gracefully even if exceeding server `post_max_size`).
    - MIME Type Inspection: Employs PHP `finfo` (Fileinfo) to verify genuine MIME types, permitting only `image/jpeg`, `image/png`, and `image/webp`.
    - Binary Integrity Check: Executes `getimagesize()` to confirm the uploaded file is a valid image binary, preventing disguised executable scripts.
    - Cryptographic Renaming: Generates unique randomized filenames (`poster_<hex>.ext`) using `random_bytes(8)` to eliminate directory traversal or file overwrite exploits.
  - **Upload Directory Hardening (`uploads/posters/.htaccess`):** Configured Apache directives (`php_flag engine off`, `RemoveHandler .php`, `Require all denied` for script extensions) ensuring uploaded files can never be executed on the server.
  - **Event Status Management:** Toggle event status between `open` and `closed` with seats tracking.
  - **Audit Logging:** Every event modification and deletion is recorded in `audit_logs`.
- **Intermediate / Advanced Extension:** Apache `.htaccess` script execution prevention and audit trail tracking for all event updates.

---

### Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload
- **Course Outcome:** `CO3, CO4, CO5`
- **Scope & Objectives:** Designed RESTful JSON API endpoints for students and events and developed an interactive single-page application (`php/ajax_crud.php`) consuming the APIs asynchronously via JavaScript `fetch()`.
- **RESTful Endpoints Implemented:**
  - `php/api/students_api.php`:
    - `GET ?page=1&per=8&q=...`: Returns paginated student records with search filtering and metadata.
    - `GET ?id=5`: Returns specific student details.
    - `POST {action: 'create' | 'update' | 'delete'}`: Performs student record management.
  - `php/api/events_api.php`:
    - `GET ?status=open&q=...`: Returns event listings with category and status filtering.
    - `POST {action: 'create' | 'update' | 'delete'}`: Performs event record management.
- **Standard HTTP Status Codes:** Responds with `200 OK`, `201 Created`, `400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `409 Conflict` (duplicates), and `422 Unprocessable Entity` (validation errors).
- **Single-Page UI Features (`php/ajax_crud.php`):**
  - Tabbed interface switching dynamically between Students API and Events API without page reload.
  - Debounced search bar (350 ms) triggering smooth asynchronous data queries.
  - Asynchronous modal dialogs for record creation and editing.
  - Live status indicator badges reflecting request lifecycle (`Idle`, `Loading…`, `Saved`, `Error`).
- **Intermediate / Advanced Extension:** Debounced live search, complete REST status code conformance, and centralized error handling.

---

### Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log
- **Course Outcome:** `CO1, CO4, CO5`
- **Scope & Objectives:** Built a centralized administrative control center (`php/admin_dashboard.php`) aggregating database metrics, enforcing RBAC, and providing system traceability.
- **Key Features Implemented:**
  - **SQL Aggregation Metrics:** Real-time query counts displaying Total Users, Active Students, Open Events, and Event Registrations.
  - **Visual Registration Histogram:** Queries monthly registration frequency (`GROUP BY DATE_FORMAT(registered_at, '%Y-%m')`) and renders a responsive bar chart with CSS gradient styling.
  - **Role-Sensitive Dynamic Navigation:** The layout engine (`php/layout.php:menu_for()`) dynamically constructs menu links based on the authenticated user's role (Admin vs. Student vs. Guest).
  - **User Access Management:** Instant toggle button allowing administrators to activate or deactivate user accounts (`status = 'active' | 'inactive'`) with self-deactivation protection.
  - **Audit Log Inspection:** Data table displaying the latest 10 security and management actions with timestamps, user identities, action tags, details, and client IP addresses.
- **Intermediate / Advanced Extension:** Active/inactive user status toggle and monthly registration frequency visualization.

---

### Practical 15: Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment
- **Course Outcome:** `CO2, CO4, CO5`
- **Scope & Objectives:** Integrated all 16 static HTML5 pages and 8 dynamic PHP backend modules into a unified, secure, and production-ready application. Executed a comprehensive automated test suite, compiled full documentation, generated SQL seed dumps, and prepared the repository for local and online deployment.
- **Key Deliverables:**
  - Unified system integration linking static pages and dynamic database flows.
  - Comprehensive test suite (`tests/p15_results.json`) validating 40+ criteria across navigation, authentication, security, RBAC, CRUD, and accessibility.
  - Production SQL seed database (`sql/studenthub_mysql.sql`) containing 22 student records, campus events, and pre-hashed user credentials.
  - Full local deployment guidelines for Apache/XAMPP environments.
  - Clean, organized GitHub version control history.

---

## 🧪 Verification & Automated Test Suite (Practical 15 Results)

The portal underwent rigorous verification across 40+ automated test cases documented in [`tests/p15_results.json`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/tests/p15_results.json):

```
========================================================================================
                          STUDENTHUB TEST SUITE SUMMARY (P15)
========================================================================================
Test Category     Scope / Criteria Evaluated                            Status
----------------------------------------------------------------------------------------
Navigation        370 local asset & page hyperlinks across 16 HTML pages PASS (0 broken)
Authentication    Anonymous redirection to login on protected pages     PASS (HTTP 302)
Authentication    Rejection of wrong passwords with user alert          PASS (HTTP 200)
Authentication    Role-based redirection (Student ➔ my_dashboard)       PASS (HTTP 302)
Authentication    Role-based redirection (Admin ➔ admin_dashboard)       PASS (HTTP 302)
Authentication    Session termination and cookie invalidation on logout PASS (HTTP 302)
Security          SQL injection attempts in login fields neutralized    PASS (0 bypass)
Security          Session cookies marked HttpOnly and SameSite=Lax      PASS (Enforced)
Security          Unauthenticated API requests blocked                  PASS (HTTP 401)
Security          Missing CSRF token on destructive POST rejected       PASS (Block delete)
Security          Output escaping of user-supplied HTML (XSS prevention)PASS (Escaped)
Security          SQL injection in search inputs blocked via PDO preparesPASS (total = 0)
Security          Upload of malicious PHP script disguised as poster     PASS (Rejected)
Security          Upload folder .htaccess script execution disabled      PASS (Protected)
Security          Passwords stored exclusively as bcrypt hashes         PASS ($2y$10$...)
RBAC Controls     Students blocked from accessing Admin Dashboard       PASS (HTTP 403)
RBAC Controls     Students blocked from executing Student CRUD actions  PASS (HTTP 403)
RBAC Controls     Students permitted to read Events API                 PASS (HTTP 200)
RBAC Controls     Students forbidden from modifying records via API     PASS (HTTP 403)
API Status Codes  Missing CSRF header ➔ 403 Forbidden                   PASS (HTTP 403)
API Status Codes  Invalid payload validation failure ➔ 422              PASS (HTTP 422)
API Status Codes  Successful record creation ➔ 201 Created              PASS (HTTP 201)
API Status Codes  Duplicate entry conflict ➔ 409 Conflict               PASS (HTTP 409)
API Status Codes  Soft delete of active record ➔ 200 OK                 PASS (HTTP 200)
API Status Codes  Querying deleted record ➔ 404 Not Found               PASS (HTTP 404)
API Status Codes  Unsupported HTTP verbs ➔ 405 Method Not Allowed       PASS (HTTP 405)
Audit Trail       Administrative and security events captured in DB     PASS (13+ rows)
Accessibility     Semantic landmarks (<header>, <nav>, <main>, <footer>) PASS (All pages)
Accessibility     Keyboard skip-to-content links present                PASS (All pages)
Responsive UI     No horizontal overflow at 768px tablet & 1280px desktopPASS (0 overflow)
Responsive UI     No horizontal overflow on all PHP backend pages (390px)PASS (0 overflow)
========================================================================================
```

---

## 🗂️ Project Directory Structure

```text
StudentHub/
│
├── index.html                    # P2-P4: Main landing portal with hero carousel & widgets
├── dashboard.html                # P2-P3: Student dashboard with timetable & metrics
├── register.html                 # P2, P5, P7: Registration with Regex validation & Canvas CAPTCHA
├── events.html                   # P3, P6: Dynamic campus events explorer with search & filter
├── profile.html                  # P2-P3: Student academic profile & CGPA breakdown
├── attendance.html               # P2-P3: Subject-wise attendance tracker & leave application
├── assignments.html              # P2, P4: Academic assignments, lab reports & upload modal
├── results.html                  # P2-P3: Semester examination results & grades
├── timetable.html                # P2-P3: Weekly lecture and laboratory schedule
├── faq.html                      # P2, P4, P6: Interactive collapsible FAQ accordion
├── feedback.html                 # P2, P4, P7: Student ratings & feedback form
├── about.html                    # P2-P3: Institutional background & department details
├── contact.html                  # P2, P5, P7: Campus contact details & helpdesk inquiry form
├── login.html                    # P2, P5: Static authentication portal mockup
├── settings.html                 # P2, P4: User preferences & theme settings
├── admin.html                    # P6-P7: Admin portal & JSON/CSV submissions viewer
│
├── css/
│   └── style.css                 # Master modern design system (Grid, Flexbox, Light/Dark tokens)
│
├── js/
│   └── main.js                   # Master JavaScript logic (DOM, Carousel, CAPTCHA, Fetch API)
│
├── php/
│   ├── config.php                # P8, P10: Database credentials & session configuration
│   ├── db.php                    # P8: Secure PDO singleton database connector
│   ├── db_test.php               # P8: Database diagnostics & connection test tool
│   ├── layout.php                # Shared layout, RBAC guards, CSRF helpers & flash messages
│   ├── register_db.php           # P9: Database registration with duplicate check & password_hash
│   ├── login.php                 # P10: Secure login with password_verify, sessions & remember-me
│   ├── logout.php                # P10: Session destruction & logout redirect
│   ├── my_dashboard.php          # P10: Protected student dashboard
│   ├── students_crud.php         # P11: Student management CRUD, search, pagination & CSV export
│   ├── events_crud.php           # P12: Event management CRUD with poster upload & validation
│   ├── ajax_crud.php             # P13: Single-page Fetch API CRUD interface
│   ├── admin_dashboard.php       # P14: Role-based admin dashboard with metrics, chart & audit logs
│   ├── submissions.php           # P7: Server-rendered CSV/JSON records viewer
│   ├── process_register.php      # P7: File-based registration processor
│   ├── process_contact.php       # P7: File-based contact inquiry processor
│   ├── process_feedback.php      # P7: File-based feedback processor
│   ├── api.php                   # P7: Lightweight JSON API router
│   └── api/
│       ├── _bootstrap.php        # P13: Shared API initialization & JSON auth helpers
│       ├── students_api.php      # P13: Students RESTful JSON API
│       └── events_api.php        # P13: Events RESTful JSON API
│
├── sql/
│   ├── studenthub_mysql.sql      # P8: Production MySQL schema & seeded dataset (22+ records)
│   └── studenthub_sqlite.sql     # P8: Portable SQLite schema for demonstration
│
├── data/
│   ├── events.json               # P6: Campus events dataset
│   ├── students.json             # P6: Student directory dataset
│   ├── faqs.json                 # P4, P6: Categorized FAQs
│   ├── notices.json              # P6: Campus announcements
│   ├── locations.json            # P6: Dependent country/state/city dataset
│   ├── registrations.json / .csv # P7: File-based registration records
│   ├── contacts.json / .csv      # P7: File-based inquiry records
│   ├── feedbacks.json / .csv     # P7: File-based user ratings
│   └── studenthub.sqlite         # P8: Seeded SQLite database demo file
│
├── uploads/
│   ├── .htaccess                 # P12: Script execution protection rule
│   └── posters/
│       └── .htaccess             # P12: Nested poster folder security rule
│
├── tests/
│   └── p15_results.json          # P15: Automated test suite verification results
│
├── docs/
│   ├── Practical_List_ITUE203_WDF_2026_27.pdf  # FTE CHARUSAT syllabus & practical list
│   ├── Requirement analysis.docx               # P1: Requirements & specifications
│   ├── Site Map and Wire frame.docx            # P1: Sitemap hierarchy & wireframes
│   └── Website preview.docx                    # Project UI screenshots
│
└── README.md                     # Comprehensive project documentation
```

---

## 💻 Technologies & Tools Used

- **Markup & Accessibility**: HTML5 (Semantic tags, Skip-links, Canvas 2D API, WAI-ARIA attributes).
- **Styling & Responsive Layout**: Vanilla CSS3 (Custom CSS Properties/Design Tokens, CSS Grid, Flexbox, Fluid Media Queries, Dark/Light palettes).
- **Client-Side Scripting**: Modern JavaScript (ES6+, DOM Manipulation, Event Listeners, Fetch API, Promises, Canvas API, LocalStorage).
- **Server-Side Processing**: PHP 7.4+ / 8.x (Session management, POST processing, `flock`, `fputcsv`, `finfo` MIME validation).
- **Database & Storage**: MySQL 8.x / MariaDB (Relational schema, foreign key constraints, indexes) and SQLite3 fallback.
- **Database Abstraction**: PHP Data Objects (PDO) with prepared statements (`PDO::ATTR_EMULATE_PREPARES => false`).
- **Web Security**: `password_hash()` (bcrypt), `password_verify()`, `session_regenerate_id()`, Anti-CSRF token verification, XSS output sanitization (`htmlspecialchars`), `.htaccess` script execution prevention.
- **Fonts & Visuals**: Google Fonts (`Outfit`, `Plus Jakarta Sans`), SVG iconography, Unicode emojis.
- **Development Tools**: Visual Studio Code, Live Server, XAMPP (Apache + MySQL), Git, GitHub, Browser DevTools.

---

## 🚀 How to Run & Test the Application

### Option 1: Static Frontend Testing (Practicals 1 to 6)
1. Open the project folder in **Visual Studio Code**.
2. Right-click on [`index.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/index.html) and select **"Open with Live Server"**.
3. Explore:
   - Interactive hero carousel, announcement cards, and theme switcher on `index.html`.
   - Real-time client-side regex validations and HTML5 Canvas CAPTCHA on `register.html`.
   - Asynchronous JSON fetching, debounced search, category filtering, and pagination on `events.html` and `faq.html`.

### Option 2: Full-Stack PHP & MySQL Database Testing (Practicals 7 to 15)

#### 1. Server Configuration
1. Copy or clone the `WDF` repository folder into your local web server root directory:
   ```text
   C:\xampp\htdocs\StudentHub\
   ```
2. Start **Apache** and **MySQL** services using the **XAMPP Control Panel**.

#### 2. Database Initialization
1. Open your web browser and navigate to **phpMyAdmin** (`http://localhost/phpmyadmin/`).
2. Create a new database named `studenthub` with collation `utf8mb4_unicode_ci`.
3. Click on the **Import** tab and choose the file:
   ```text
   C:\xampp\htdocs\StudentHub\sql\studenthub_mysql.sql
   ```
4. Click **Go** to create the tables (`users`, `students`, `events`, `registrations`, `audit_logs`) and populate the seed records.

#### 3. Database Connection Verification (Practical 8)
Navigate to:
```text
http://localhost/StudentHub/php/db_test.php
```
Verify that the database driver shows `PDO MySQL`, table row counts are populated, and the prepared statement test displays student details.

#### 4. Pre-configured Demonstration Accounts (Practicals 9 & 10)
Use the seeded credentials below to test Role-Based Access Control:

| Role | Email Address | Password | Permissions & Dashboard |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@charusat.edu.in` | `Admin@2026` | Full administrative access to `admin_dashboard.php`, `students_crud.php`, `events_crud.php`, and `ajax_crud.php`. |
| **Student** | `25dce070@charusat.edu.in` | `Student@2026` | Student access to `my_dashboard.php`; admin endpoints are blocked (`403 Forbidden`). |
| **Student** | `25dce071@charusat.edu.in` | `Student@2026` | Secondary active student account. |
| **Inactive Student** | `25dce072@charusat.edu.in` | `Student@2026` | Inactive account testing; login is rejected with an account suspension notice. |

#### 5. Module Testing Workflows
- **Registration & Hashing (Practical 9):** Navigate to `php/register_db.php`, create a new student account, verify password hashing in the database, and test duplicate email rejection.
- **Login, Sessions & RBAC (Practical 10):** Log in at `php/login.php`. Observe role-based routing, test remember-me token generation, and verify that students attempting to open `admin_dashboard.php` receive an HTTP `403 Forbidden` response.
- **Student Management CRUD (Practical 11):** Log in as admin, navigate to `php/students_crud.php`, search students, filter by course, add/edit records, perform soft deletion, and download the roster via the CSV Export button.
- **Event Management & File Upload (Practical 12):** Navigate to `php/events_crud.php`, create an event, attach an image poster (≤ 2 MB), and verify upload security.
- **Single-Page AJAX CRUD (Practical 13):** Navigate to `php/ajax_crud.php`, toggle between Students and Events tabs, and execute live search and CRUD operations with zero full-page reloads.
- **Admin Dashboard & Audit Trail (Practical 14):** Navigate to `php/admin_dashboard.php` to inspect dynamic SQL aggregation metrics, the monthly registration bar chart, user status toggle switches, and the chronological audit log.
- **Automated Verification (Practical 15):** Inspect `tests/p15_results.json` to review the automated test results verifying 40+ criteria across security, authentication, and layout standards.

---

## 👨‍🎓 Student Details

- **Name:** Yuvraj Parmar  
- **Enrollment Number:** `25DCE070`  
- **Program:** B.Tech Computer Engineering  
- **Semester:** 3rd Semester (Odd 2026-27)  
- **Subject:** Web Development Frameworks (`ITUE203`)  
- **Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
- **Academic Year:** 2026-2027  
