# Sistem Manajemen Inventaris UMKM
A simple secure web application built to manage inventory stock while maintaining a strict audit trail and secure user authentication.

## Features
* **CRUD:** Add, edit, and delete inventory items.
* **Simple UI:** Clean and easy-to-use user interface.
* **Database:** Data is automatically saved and integrated with an MySQL database.
* **Automatic Price Formatting:** Price formatting and data validation built-in.

## Key Features
* **Secure Authentication:** Dedicated login page with secure password hashing.
* **Audit Logging:** Automatically tracks and logs all user changes. Every Insert, Edit, and Delete action is recorded to maintain a complete history of database modifications.

## Security Measures
We take data protection seriously. This project includes:
* **Prepared Statements:** All SQL queries use prepared statements to completely prevent SQL injection attacks.
* **Input Validation:** Strict user input validation to ensure data integrity and block malicious data before it reaches the server.
* **Hashed Credentials:** Passwords are never stored as plain text.

## Prerequisites
To run this project locally, you will need:
* XAMPP.
* Any browser (chrome, firefox, microsoft edge, ect)

## Installation & Setup
1. Clone or download the project files into your web server's root directory (e.g., `htdocs`).
2. Open your MySQL management tool (like phpMyAdmin) and create a new database.
3. Import the provided `.sql` file in the `/query` folder to set up the necessary tables and triggers.
4. Run (`adduser.php`) in your local browser to create the first administrator user for logging in.
5. Open your web browser and navigate to `localhost/your-project-name-folder` to access the login page.
