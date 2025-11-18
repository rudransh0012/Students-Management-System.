# Student Management System

A simple web-based Student Management System built with **PHP**, **MySQL (XAMPP)**, and **Bootstrap 5**.

This system lets you manage students and their daily attendance from a clean, modern UI. Login has been disabled so the app is directly accessible.

---

## Features

- **Dashboard**
  - Overview cards: Total, Male, and Female students.

- **Students Module**
  - Add new students (Roll No, Name, Email, Gender, Class).
  - Edit existing student details.
  - Delete students.
  - Search by name, email, or roll number.
  - Responsive table with clean UI.

- **Attendance Module**
  - Mark daily attendance for all students.
  - Status options: **Present / Absent / Leave**.
  - Change/update attendance for the same date (no duplicates).
  - View attendance report by date with colored status badges.

- **UI & Branding**
  - Bootstrap 5 design with custom CSS for a modern look.
  - Custom logo shown in the navbar (`logo.png`).

---

## Requirements

- **OS:** Windows (tested with XAMPP on Windows)
- **Server:** XAMPP (Apache + MySQL)
- **PHP:** Version bundled with your XAMPP (7.x/8.x)

---

## Project Structure (main files)

- `index.php` – Redirects to the dashboard.
- `dashboard.php` – Main dashboard with student statistics.
- `students.php` – List/search students.
- `student_add.php` – Add student form.
- `student_edit.php` – Edit student.
- `student_delete.php` – Delete student.
- `attendance_mark.php` – Mark attendance for a selected date.
- `attendance_report.php` – View attendance report for a date.
- `config.php` – App & database configuration constants.
- `db.php` – PDO connection to MySQL.
- `auth.php` – Authentication helpers (currently disabled; always allows access).
- `header.php` / `footer.php` – Layout wrapper and navbar with logo.
- `create_database.sql` – SQL script to create the database and tables.
- `assets/css/style.css` – Custom styles.
- `logo.png` – Application logo displayed in the navbar.

---

## Database Setup

Database name used in the project: **`student_management`**

### 1. Create the database and tables (new setup)

1. Start **XAMPP** and run **Apache** + **MySQL**.
2. Open your browser and go to: `http://localhost/phpmyadmin`.
3. Click the **Import** tab.
4. Choose the file:
   - `c:/xampp/htdocs/students management system/create_database.sql`
5. Click **Go**.

This will:

- Create database `student_management` (if it does not exist).
- Create tables: `users`, `students`.
- Insert a default admin user (login currently not used).
- Create the `attendance` table (if included in your script) or you can run this manually:

```sql
CREATE TABLE IF NOT EXISTS attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  attendance_date DATE NOT NULL,
  status ENUM('Present', 'Absent', 'Leave') NOT NULL DEFAULT 'Present',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_attendance_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY uq_attendance_student_date (student_id, attendance_date)
);
```

### 2. Check configuration

Open `config.php` and confirm:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'student_management');
define('DB_USER', 'root');
define('DB_PASS', '');
```

These values should match your MySQL setup (default XAMPP = user `root`, no password).

---

## How to Run the Project

1. **Start XAMPP services**
   - Open **XAMPP Control Panel**.
   - Click **Start** on **Apache**.
   - Click **Start** on **MySQL**.

2. **Open the application in a browser**

   Go to:

   ```text
   http://localhost/students%20management%20system/
   ```

   This will load `index.php`, which redirects to `dashboard.php`.

3. **Access main modules**

   - Dashboard: `Dashboard` link in navbar.
   - Students: `Students` link in navbar or go to

     ```text
     http://localhost/students%20management%20system/students.php
     ```

   - Attendance (mark):

     ```text
     http://localhost/students%20management%20system/attendance_mark.php
     ```

   - Attendance (report):

     ```text
     http://localhost/students%20management%20system/attendance_report.php
     ```

---

## Using the System

### Students

- Go to **Students** from the navbar.
- Click **Add Student** to create a new record.
- Use the **Search** box to filter by name, email, or roll no.
- Use **Edit** / **Delete** buttons in the table to manage records.

### Attendance

#### Mark Attendance

1. Open `Attendance` from navbar (or `/attendance_mark.php`).
2. Select a **Date** and click **Load**.
3. For each student, choose **Present / Absent / Leave**.
4. Click **Save Attendance**.

Attendance for the same date + student will be **updated** if you save again.

#### View Attendance Report

1. Open `/attendance_report.php` or click **View Attendance Report** from the attendance page.
2. Select a **Date**.
3. See each student with a status badge:
   - Green: Present
   - Red: Absent
   - Yellow: Leave
   - Grey: Not Marked (no record for that date)

---

## Authentication Note

Originally, the system had a login (`login.php`, `users` table, `auth.php`).

In the current version:

- `auth.php` functions (`is_logged_in`, `require_login`, etc.) are modified so that **all pages are accessible without login**.
- `index.php` redirects directly to `dashboard.php`.

If you later want to re-enable login, you can restore the original logic in `auth.php` and `login.php`.

---

## Customization

- **Logo**: Replace `logo.png` in the project root with your own image.
- **Colors / UI**: Adjust `assets/css/style.css` to change theme colors, fonts, and table styles.
- **Database**: Add more fields to `students` or extend `attendance` as needed.

---

## Troubleshooting

- **`Base table or view not found: 1146 ... attendance`**
  - Ensure the `attendance` table exists in the `student_management` database (see SQL above).

- **Cannot connect to database**
  - Check `config.php` DB settings.
  - Ensure MySQL is running.

- **404 Not Found**
  - Check that the folder name under `htdocs` is exactly `students management system`.
  - URL must match that folder name.

---

Happy coding and managing your students!
