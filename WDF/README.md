# 🎓 StudentHub — Unified Academic & Campus Web Portal
### Course: Web Development Frameworks (`ITUE203`)
**Faculty of Technology & Engineering (FTE) | CHARUSAT**  
**Semester:** 3rd Semester, B.Tech Computer Engineering (Odd 2026-27)  
**Developer:** Yuvraj Parmar | **Enrollment No.:** `25DCE070`  

---

## 🌟 Project Overview

**StudentHub** is a responsive, accessible, and user-centric student academic and campus web portal built to fulfill the laboratory requirements for **Practicals 1 through 7** of the Web Development Frameworks curriculum.

The portal provides an intuitive digital campus interface where students can browse upcoming hackathons and workshops, register for events with real-time validated forms and custom canvas CAPTCHAs, inspect academic schedules, view attendance criteria, access FAQs, and submit helpdesk inquiries. All form submissions are validated and securely stored server-side using PHP into structured JSON and CSV formats.

---

## 🗺️ Website Navigation Paths & How to Handle the Website

To easily browse and test all capabilities of **StudentHub**, use the following navigation paths and workflows:

### 1. 🚀 Visitor & Public Exploration Flow
```text
Landing Page (index.html) 
  ├──> Browse Featured Events & News (events.html)
  ├──> Frequently Asked Questions with Search & Filter (faq.html)
  ├──> About Faculty & Institute Overview (about.html)
  └──> Contact Helpdesk Form (contact.html)
```

### 2. 📝 Student Registration & Form Processing Flow (Practicals 5 & 7)
```text
index.html ──> Click "Register" ──> register.html
  │
  ├── 1. Fill Student Information (Full Name, Student ID, Email, Phone, Password)
  ├── 2. Watch Real-time Regex Validation feedback & live Password Strength Meter
  ├── 3. Solve HTML5 Canvas CAPTCHA (Click 🔄 refresh if needed)
  ├── 4. Select Dependent Academic Dropdowns (Country -> State -> City)
  └── 5. Click "Complete Registration":
          ├── Submits via HTTP POST to php/process_register.php
          ├── Data is sanitized and validated on server side
          └── Saved atomically to data/registrations.json & data/registrations.csv
```

### 3. 📊 Authenticated Student Dashboard Flow
```text
index.html ──> Click "Login" ──> login.html ──> Click "Student Sign In" ──> dashboard.html
  │
  ├──> Profile Page (profile.html)      : Personal details, CGPA breakdown, enrolled courses
  ├──> Attendance Page (attendance.html): Subject-wise attendance % and leave application
  ├──> Timetable Page (timetable.html)  : Day-by-day weekly lecture & lab schedule
  ├──> Assignments Page (assignments.html): Lab reports status, deadlines & upload modal
  ├──> Results Page (results.html)      : Semester GPA, grade points, and transcript
  ├──> Settings Page (settings.html)    : Theme preference toggle and account preferences
  └──> Feedback Page (feedback.html)    : Star rating and feedback submission (P7)
```

### 4. ⚙️ Admin & Submissions Viewer Flow (Practical 7)
```text
Navigation Bar ──> Click "Admin Portal" ──> admin.html
  │
  ├── Option A: View Submissions via Frontend (admin.html)
  │    └── Reads data/registrations.json and data/contacts.json dynamically
  │
  └── Option B: View Submissions via PHP Backend (php/submissions.php)
       └── Server-rendered data table displaying both JSON and CSV records
```

---

## 🧭 Complete Page Directory & Feature Reference

| Page / File Path | Associated Practical | Purpose & Key Features |
| :--- | :---: | :--- |
| [`index.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/index.html) | P2, P3, P4 | **Main Landing Portal**: Hero carousel slider, live metric cards, quick-action tiles, announcements, and theme switcher. |
| [`register.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/register.html) | P2, P5, P7 | **Student Registration**: Real-time Regex validation, live password strength meter, HTML5 Canvas CAPTCHA, dependent dropdowns, and POST submission. |
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
| [`login.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/login.html) | P2, P5 | **Portal Authentication**: Clean login form with role switcher (Student / Administrator) and demo shortcuts. |
| [`settings.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/settings.html) | P2, P4 | **User Settings**: Dark/Light theme toggle, notification preferences, and local cache controls. |
| [`admin.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/admin.html) | P6, P7 | **Admin Submissions Portal**: Frontend dashboard displaying stored JSON/CSV registrations and inquiries. |
| [`php/submissions.php`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/php/submissions.php) | P7 | **PHP Submissions Viewer**: Server-rendered PHP page reading and presenting file-based records in tables. |

---

## 📋 Detailed Practical Coverage (Practicals 1 to 7)

### Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub Setup
- **Course Outcome:** `CO1`
- **Scope & Objectives:** Defined problem scope, user personas (Student, Faculty, Admin), sitemap architecture, low-fidelity wireframes, structured project directory, and version control setup.
- **Artifacts:**
  - `docs/Requirement analysis.docx`: Functional requirements and module specifications.
  - `docs/Site Map and Wire frame.docx`: Navigation hierarchy and UI wireframe designs.
  - `docs/Website preview.docx`: Visual previews of interface design.
- **Key Deliverables:** Clean folder structure (`css/`, `js/`, `php/`, `data/`, `docs/`) and structured layout.

---

### Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure
- **Course Outcome:** `CO1, CO2`
- **Scope & Objectives:** Built 12+ standard-compliant HTML5 pages ensuring semantic markup, correct document outline, and high accessibility (a11y).
- **Key Features Implemented:**
  - Semantic landmark elements across all pages: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, and `<footer>`.
  - Accessible skip-to-content links (`<a href="#main-content" class="skip-link">`) for keyboard navigation.
  - Form accessibility: Explicit `<label for="...">` associations, `aria-required`, `aria-describedby`, and accessible error containers.
  - Informative `alt` text for images, clear heading hierarchy (`<h1>` to `<h3>`), and breadcrumb navigation.

---

### Practical 3: Responsive UI Design using CSS2.0/CSS3.0, CSS Grid and Flexbox
- **Course Outcome:** `CO2`
- **Scope & Objectives:** Mobile-first, responsive user interface developed with pure Vanilla CSS, utilizing CSS Grid and Flexbox layouts without external heavyweight CSS frameworks.
- **Key Features Implemented:**
  - **CSS Custom Properties (Design Tokens):** Centralized theme palette in `css/style.css` supporting both Light Mode and Dark Mode (`--bg-primary`, `--text-primary`, `--primary-color`, `--card-bg`, `--border-color`).
  - **CSS Grid Layouts:** Multi-column responsive card grids (`grid-template-columns: repeat(auto-fit, minmax(280px, 1fr))`) for events, statistics, and timetables.
  - **CSS Flexbox:** Alignment for navigation header, action buttons, breadcrumbs, and pagination controls.
  - **Responsive Media Queries:** Fluid breakpoints (`@media (max-width: 992px)`, `@media (max-width: 768px)`, `@media (max-width: 480px)`) ensuring seamless display on mobile phones, tablets, and desktops.

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
    - Password: Minimum 8 characters with at least one uppercase letter, one digit, and one special character.
    - Confirm Password: Strict equality match with primary password.
  - **Live Password Strength Meter:** Interactive multi-segment strength bar (Weak, Moderate, Strong) with a dynamic criteria checklist.
  - **HTML5 Canvas CAPTCHA:** Custom alphanumeric CAPTCHA drawn directly onto an HTML5 `<canvas>` with randomized angles, colored distortion lines, background noise, and a refresh button.
  - **Real-Time UX Error Hints:** Instant feedback displayed on `keyup` and `blur` events with accessible error text.

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
  - **Real-Time Debounced Search:** Client-side search bar filtering records dynamically as the user types.
  - **Category Filter Pills & Sorting:** Instant filtering by event categories (Technical, Cultural, Sports, Academic) and sorting by date, title, or seat availability.
  - **Client-Side Pagination:** Modular page slicing (e.g. 6 items per page) with Previous, Next, and numbered page buttons.
  - **Dependent Cascading Dropdowns:** Country selection automatically populates relevant States, which in turn populates Cities on `register.html`.

---

### Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage
- **Course Outcome:** `CO1, CO5`
- **Scope & Objectives:** Developed secure server-side form handling using PHP, performing server validation, sanitization, and persistent storage into both JSON and CSV files without database dependency.
- **Key Features Implemented:**
  - **HTTP POST Request Handlers:**
    - `php/process_register.php`: Processes student registration submissions.
    - `php/process_contact.php`: Processes contact helpdesk inquiries.
    - `php/process_feedback.php`: Processes student ratings and feedback submissions.
  - **Server-Side Sanitization & Validation:**
    - Strict `POST` method verification.
    - Input sanitization via `htmlspecialchars()`, `trim()`, and `stripslashes()`.
    - Type and format validation using `filter_var($email, FILTER_VALIDATE_EMAIL)` and server-side Regex matching.
  - **Safe & Atomic File Storage:**
    - Appends records to structured JSON files (`data/registrations.json`) using `LOCK_EX` to prevent race conditions.
    - Concurrently writes records into CSV files (`data/registrations.csv`) using `fputcsv()`.
  - **Submissions Data Viewer:**
    - `admin.html`: Client-side interface to browse and inspect stored JSON records.
    - `php/submissions.php`: Server-rendered PHP page presenting registration tables with formatted dates and status badges.

---

## 🗂️ Project Directory Structure

```text
StudentHub/
│
├── index.html                    # Main landing portal with hero carousel & widgets
├── dashboard.html                # Student dashboard with timetable & metrics
├── register.html                 # Student registration with Regex validation & Canvas CAPTCHA
├── events.html                   # Dynamic campus events explorer with search & filter
├── profile.html                  # Student academic profile & CGPA breakdown
├── attendance.html               # Subject-wise attendance tracker & leave application
├── assignments.html              # Academic assignments, lab reports & upload modal
├── results.html                  # Semester examination results & grades
├── timetable.html                # Weekly lecture and laboratory schedule
├── faq.html                      # Interactive collapsible FAQ accordion
├── feedback.html                 # Student ratings & feedback form
├── about.html                    # Institutional background & department details
├── contact.html                  # Campus contact details & helpdesk inquiry form
├── login.html                    # Portal authentication with role switcher
├── settings.html                 # User preferences & theme settings
├── admin.html                    # Practical 7: Admin portal & submissions viewer
│
├── css/
│   └── style.css                 # Master modern design system (Grid, Flexbox, Light/Dark tokens)
│
├── js/
│   └── main.js                   # Master JavaScript logic (DOM, Carousel, CAPTCHA, Fetch API)
│
├── php/
│   ├── process_register.php      # Practical 7: PHP registration processor (JSON & CSV write)
│   ├── process_contact.php       # Practical 7: PHP contact form processor
│   ├── process_feedback.php      # Practical 7: PHP feedback & star rating processor
│   ├── submissions.php           # Practical 7: Server-rendered CSV/JSON records viewer
│   └── api.php                   # Lightweight JSON API endpoint router
│
├── data/
│   ├── events.json               # 16+ Campus events dataset (P6)
│   ├── students.json             # 16+ Student directory dataset (P6)
│   ├── faqs.json                 # Categorized FAQ dataset (P4 & P6)
│   ├── notices.json              # Campus announcements dataset (P6)
│   ├── locations.json            # Country -> State -> City dependent hierarchy (P6)
│   ├── registrations.json        # Practical 7: JSON registration records
│   ├── registrations.csv         # Practical 7: CSV registration records
│   ├── contacts.json / .csv      # Practical 7: Contact inquiries storage
│   └── feedbacks.json / .csv     # Practical 7: User feedback storage
│
├── docs/
│   ├── Practical_List_ITUE203_WDF_2026_27.pdf  # Course syllabus & practical outline
│   ├── Requirement analysis.docx               # Practical 1 documentation
│   ├── Site Map and Wire frame.docx            # Practical 1 sitemap & wireframes
│   └── Website preview.docx                    # Project UI screenshots
│
└── README.md                     # Comprehensive project documentation
```

---

## 💻 Technologies & Tools Used

- **Markup & Semantics**: HTML5 (Semantic tags, Skip links, Canvas API, ARIA accessibility attributes).
- **Styling & Layout**: Vanilla CSS3 (Custom CSS Properties/Variables, CSS Grid, Flexbox, Media Queries, Dark/Light palettes).
- **Client-Side Scripting**: Modern JavaScript (ES6+, DOM Manipulation, Event Listeners, Fetch API, Promises, Canvas 2D Context, LocalStorage).
- **Server-Side Processing**: PHP 7.4+ / 8.x (HTTP POST processing, Data sanitization, `flock`, `fputcsv`, JSON encoding/decoding).
- **Data Formats**: JSON (`.json`) and CSV (`.csv`) file-based persistence.
- **Fonts & Visual Assets**: Google Fonts (`Outfit`, `Plus Jakarta Sans`), SVG iconography, and Unicode symbols.
- **Environment & Tools**: Visual Studio Code, Live Server, XAMPP / Apache, Browser Developer Tools.

---

## 🚀 How to Run & Test the Application

### Option 1: Static Frontend Testing (Practicals 1 to 6)
1. Open the project folder in **Visual Studio Code**.
2. Right-click on [`index.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/index.html) and click **"Open with Live Server"**.
3. You can explore:
   - Dynamic Hero Slider on [`index.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/index.html).
   - Real-time client-side validation and Canvas CAPTCHA on [`register.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/register.html).
   - Live JSON Fetching, searching, filtering, and pagination on [`events.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/events.html) and [`faq.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/faq.html).
   - Theme toggle (Light/Dark mode) across any page.

### Option 2: Full-Stack PHP Form Processing (Practical 7)
1. Copy or clone the `WDF` folder into your local web server root (e.g. `C:\xampp\htdocs\StudentHub\`).
2. Start the **Apache** service in the **XAMPP Control Panel**.
3. Open your web browser and navigate to:
   ```text
   http://localhost/StudentHub/
   ```
4. Test PHP form processing:
   - Fill and submit the registration form at `register.html`.
   - Submit a message via `contact.html` or a rating via `feedback.html`.
5. View saved records:
   - Navigate to [`admin.html`](file:///c:/Users/Yuvraj/Documents/sem%20-3/Sem-3/WDF/admin.html) or open `http://localhost/StudentHub/php/submissions.php` to verify that submitted entries are successfully saved in `data/registrations.json` and `data/registrations.csv`.

---

## 👨‍🎓 Student Details

- **Name:** Yuvraj Parmar  
- **Enrollment Number:** `25DCE070`  
- **Program:** B.Tech Computer Engineering  
- **Semester:** 3rd Semester (Odd 2026-27)  
- **Subject:** Web Development Frameworks (`ITUE203`)  
- **Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
