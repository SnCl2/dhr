# Manpower Hiring & Employee Management System

## Project Overview
This project is a custom-built **Manpower Hiring & Employee Management System** designed for recruitment agencies, staffing firms, and manpower providers. It provides:
1. A public-facing marketing website (Home, About, Services, Gallery, Contact Us, Career/Apply page, Employee & Admin Login entry points).
2. A secure **Admin Panel** for managing employees, generating bulk offer letters, tracking applicant inquiries, and controlling site content.
3. A secure **Candidate / Employee Self-Service Portal** allowing staff to update their profile, download official documents (Offer/Joining letters), and view bulletins.

---

## Technical Specifications
- **Framework**: Laravel 11.x (or latest stable)
- **Database**: SQLite (local database file)
- **Frontend / Styling**: TailwindCSS (or Bootstrap 5 as per PDF, but customized with high-end premium styling)
- **Authentication**: Built completely from scratch (No Laravel Breeze, Jetstream, or Fortify)
- **Build Tool**: npm (Vite)
- **Document Generation**: PDF generation support for offer letters and payslips using the `FPDF` library (custom internal and external formatting).

---

## Database Schema Design (SQLite)
The application will use separate database tables and Laravel Guards for Admin and Employee access to ensure complete separation of duties:

1. **`admins`**
   - `id` (Primary Key)
   - `name` (String)
   - `email` (String, Unique)
   - `password` (String)
   - `remember_token` (String, Nullable)
   - `created_at` & `updated_at`

2. **`departments`**
   - `id` (Primary Key)
   - `name` (String, Unique)
   - `created_at` & `updated_at`

3. **`designations`**
   - `id` (Primary Key)
   - `name` (String)
   - `created_at` & `updated_at`

4. **`employees`** (Matches Candidates & Hired Employees)
   - `id` (Primary Key)
   - `employee_id` (String, Unique) - e.g., EMP-2026-0001
   - `first_name` (String)
   - `last_name` (String)
   - `email` (String, Unique)
   - `phone` (String, Nullable)
   - `password` (String)
   - `status` (Enum/String: `pending_review`, `active`, `inactive`, `on_leave`, `terminated`)
   - `department_id` (Foreign Key -> departments, Nullable)
   - `designation_id` (Foreign Key -> designations, Nullable)
   - `joining_date` (Date, Nullable)
   - `salary` (Decimal, Nullable)
   - `is_password_changed` (Boolean, Default: false) - Enforces password change on first login.
   - `remember_token` (String, Nullable)
   - `created_at` & `updated_at`

5. **`offer_letter_templates`**
   - `id` (Primary Key)
   - `name` (String)
   - `subject` (String)
   - `type` (Enum/String: `internal`, `external`)
   - `content` (Text) - FPDF mapping JSON or Rich text/HTML content containing placeholders (e.g., `{first_name}`, `{salary}`)
   - `created_at` & `updated_at`

6. **`offer_letters`**
   - `id` (Primary Key)
   - `employee_id` (Foreign Key -> employees)
   - `template_id` (Foreign Key -> offer_letter_templates)
   - `pdf_path` (String) - Path to generated PDF file
   - `created_at` & `updated_at`

7. **`payslips`**
   - `id` (Primary Key)
   - `employee_id` (Foreign Key -> employees)
   - `month` (String) - e.g., "August 2026"
   - `basic_salary` (Decimal)
   - `allowances` (Decimal)
   - `deductions` (Decimal)
   - `net_salary` (Decimal)
   - `type` (Enum/String: `internal`, `external`)
   - `pdf_path` (String)
   - `created_at` & `updated_at`

8. **`inquiries`** (From Contact Us Form)
   - `id` (Primary Key)
   - `name` (String)
   - `email` (String)
   - `phone` (String, Nullable)
   - `subject` (String)
   - `message` (Text)
   - `status` (String: `unread`, `read`, `replied`)
   - `created_at` & `updated_at`

9. **`bulletins`** (Organizational Announcements)
   - `id` (Primary Key)
   - `title` (String)
   - `content` (Text)
   - `is_active` (Boolean, Default: true)
   - `created_at` & `updated_at`

10. **`site_content`** (For CMS Management)
    - `id` (Primary Key)
    - `key` (String, Unique) - e.g., 'home_banner_title', 'about_us_text'
    - `value` (Text)
    - `created_at` & `updated_at`


---

## Authentication System from Scratch
Since Breeze, Jetstream, or Fortify must **not** be used:
1. Define custom auth guards in `config/auth.php`:
   - `admin` guard: uses `session` driver and `admins` provider.
   - `employee` guard: uses `session` driver and `employees` provider.
2. Custom Controllers:
   - `AdminAuthController`: handles admin login, logout, and session lifecycle.
   - `EmployeeAuthController`: handles candidate registration, employee login, first-time password setup, and logout.
3. Custom Middleware:
   - `RedirectIfAuthenticatedAdmin`: redirects authenticated admins away from guest pages.
   - `RedirectIfAuthenticatedEmployee`: redirects authenticated employees away from guest pages.
   - `AuthenticateAdmin`: ensures admin is logged in.
   - `AuthenticateEmployee`: ensures employee is logged in and enforces `is_password_changed` redirect if false.

---

## Architectural & Coding Guidelines
- **Premium UI**: Use rich aesthetics, modern gradients, micro-animations, and responsive glassmorphism designs. Avoid default bootstrap/tailwind looks. All pages must look clean, professional, and visually engaging.
- **RESTful Controllers**: Keep controller actions standard (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`).
- **Validation**: Enforce strong server-side validation using Form Requests.
- **SQLite Optimization**: Enable foreign key constraints in SQLite migrations: `Schema::enableForeignKeyConstraints();`.
- **Security**: Prevent SQL Injection, XSS, and CSRF. Never render links or buttons pointing to the Admin Login portal on any public-facing pages (header, footer, mobile menu, etc.). Admins must access it directly by entering the `/admin/login` URL.
- **SEO**: Include title tags, meta descriptions, and semantic HTML hierarchy on public pages.
