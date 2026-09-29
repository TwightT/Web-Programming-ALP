# Sistem Manajemen Inventaris UMKM
A simple secure web application built to manage inventory stock while maintaining a strict audit trail and secure user authentication.

## Features
* **CRUD:** Add, edit, and delete inventory items.
* **Simple UI:** Clean and easy-to-use user interface.
* **Database:** Data is automatically saved and integrated with an MySQL database.
* **Automatic Price Formatting:** Price formatting and data validation built-in.

## Key Features
* **Firebase Authentication:** Dedicated Login and Register pages supporting Email/Password authentication via Firebase Auth SDK.
* **Audit Logging:** Automatically tracks and logs all user changes. Every Insert, Edit, and Delete action is recorded to maintain a complete history of database modifications.

## Security Measures
We take data protection seriously. This project includes:
* **Prepared Statements:** All SQL queries use prepared statements to completely prevent SQL injection attacks.
* **Input Validation:** Strict user input validation to ensure data integrity and block malicious data before it reaches the server.
* **Hashed Credentials:** Passwords are never stored as plain text.

## Prerequisites
To run this project locally, you will need:
* Web Server with PHP support (e.g., XAMPP, WAMP, or PHP Built-in Server).
* Composer (for managing dependencies such as `kreait/firebase-php`).
* Web browser (Chrome, Firefox, Microsoft Edge, etc.).
* Firebase Account with an active Firebase Realtime Database and Firebase Authentication enabled.

## Installation & Setup
1. **Clone or download** the project files into your web server's root directory (e.g., `htdocs` for XAMPP).
2. **Install Dependencies**: Run `composer install` inside the project root directory to fetch the required Firebase SDK packages.
3. **Set up Firebase**: Open the [Firebase Console](https://console.firebase.google.com/), create a new project, and enable **Firebase Realtime Database** and **Firebase Authentication** (Email/Password sign-in method).
4. **Download Credentials**: Generate a new Private Key (Service Account JSON file) under *Project Settings > Service Accounts* and place it inside your project directory as `firebase_credentials.json` (or update the filename reference in configuration files).
5. **Configure Connection**: Update `koneksi_database.php` with your Firebase Realtime Database URL and the path to your downloaded Service Account JSON file.
6. **Register / Create User**: Access `http://localhost/your-project-folder/register.php` in your browser to register a new account, or create an administrator user directly inside the Firebase Authentication console.
