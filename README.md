# Sistem Manajemen Inventaris UMKM
A simple secure web application built to manage inventory stock while maintaining a strict audit trail using PHP and Firebase.

## Features
* **CRUD:** Add, edit, and delete inventory items.
* **Simple UI:** Clean and easy-to-use user interface.
* **Cloud Database:** Data is automatically synchronized and stored using Firebase Realtime Database.
* **Automatic Price Formatting:** Price formatting and data validation built-in.

## Key Features
* **Secure Authentication:** Dedicated login page powered by Firebase Authentication.
* **Audit Logging:** Automatically tracks and logs all user changes. Every Insert, Edit, and Delete action is recorded to maintain a complete history of database modifications.

## Security Measures
We take data protection seriously. This project includes:
* **NoSQL Data Integrity:** Strict parameter checking to prevent undefined variable errors and malformed NoSQL requests.
* **Input Validation & Sanitization:** User input validation to ensure data integrity and prevent cross-site scripting (XSS).
* **Cloud Authentication:** User authentication and password security are handled directly via Firebase Authentication SDK.

## Prerequisites
To run this project locally, you will need:
* Web Server with PHP support (XAMPP, WAMP, or PHP Built-in Server).
* Code Editor (VS Code, Sublime Text, etc.).
* Web Browser (Chrome, Firefox, Microsoft Edge, etc.).
* Firebase Account.

## Installation & Setup
1. **Clone or download** the project files into your web server's root directory (e.g., `htdocs` for XAMPP).
2. **Set up Firebase**: Open the [Firebase Console](https://console.firebase.google.com/), create a new project, and enable **Firebase Realtime Database** and **Firebase Authentication** (Email/Password sign-in method).
3. **Download Credentials**: Generate a new Private Key (JSON file) under Firebase *Project Settings > Service Accounts* and place it inside your project directory.
4. **Configure Connection**: Update `koneksi_database.php` with your Firebase Database URL and the correct file path to your downloaded Service Account JSON file.
5. **Create Admin User**: Create your first administrator account directly inside the Firebase Authentication console, and set a `displayName` for logging purposes.
6. **Access the Application**: Open your web browser and navigate to `http://localhost/your-project-folder` to access the login page.