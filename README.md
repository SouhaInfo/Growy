# 🌱 Growy

**Empowering Parents, Enriching Lives.**

Growy is a web platform for parents and caregivers. It brings together educational resources, courses and trusted services to support children's growth, from early childhood to adulthood.

The goal is simple: help parents connect, share experiences and find reliable support, so parenting becomes a shared adventure instead of a challenge.

---

## ✨ Features

### 📚 Courses
- Browse courses on child care and child development
- Follow lectures with video content
- Add courses to a cart and pay online

### 🤝 Contributions
- Parents, teachers and experts can share articles, videos and podcasts
- Each contribution is sorted by category for easy browsing
- Users can comment and give feedback
- Contributors can edit or delete their own submissions

### 📢 Announcements
- Find trusted services: babysitting, tutoring, sports coaching, school transportation
- Post your own announcement or a specific request
- Manage your announcements from your account

### 👤 User accounts
- Sign up, log in and log out
- Profile and settings pages

### 🛠️ Admin panel
- Manage users
- Add and manage courses and lectures
- Track payments

### 💬 Other
- Contact form (emails sent with PHPMailer)
- Newsletter subscription
- About us page
- Cookie consent

---

## 🧰 Tech Stack

| Layer | Technologies |
|-------|-------------|
| Frontend | HTML, CSS, JavaScript, jQuery |
| Backend | PHP |
| Database | MySQL (MySQLi) |
| Email | PHPMailer |

---

## 📁 Project Structure

```
Growy/
├── Assets/
│   ├── CSS/            # Stylesheets
│   ├── JS/             # Scripts (validation, cart, payment, comments...)
│   └── images/         # Images used on the site
├── Components/         # PHP pages and components (header, footer, courses, announcements...)
├── PHPMailer-master/   # PHPMailer library
├── Project Details/    # Site map, database diagram, project analysis
└── index.php           # Entry point
```

---

## 🚀 Getting Started

### Requirements
- A local PHP server such as [XAMPP](https://www.apachefriends.org/) or WAMP
- MySQL

### Installation

1. Clone the repository into your server folder (for example `htdocs` with XAMPP):
   ```bash
   git clone https://github.com/SouhaInfo/Growy.git
   ```
2. Start Apache and MySQL.
3. Create a MySQL database named `growy_db`, based on the database diagram in `Project Details/`.
4. Update your database credentials in `Components/db_connect.php`:
   ```php
   $host = 'localhost';
   $dbname = 'growy_db';
   $username = 'root';
   $password = '';
   ```
5. Open the site in your browser:
   ```
   http://localhost/Growy/
   ```

---
