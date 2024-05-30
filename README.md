CRUD Operations with Multiple Images

This project demonstrates how to perform CRUD (Create, Read, Update, Delete) operations with multiple image uploads using PHP and MySQL.
============ Instructions ============
1. Create the Database

    Create a new MySQL database (e.g., pamz).
    Import the SQL schema provided in SQL/crud_db.sql into the newly created database. This will set up the necessary tables and initial data.

2. Configure the Application

    Open the config.php file in a text editor.
    Update the database connection settings with your MySQL credentials:

    php

    define('DB_HOST', 'your_database_host');
    define('DB_USERNAME', 'your_database_username');
    define('DB_PASSWORD', 'your_database_password');
    define('DB_NAME', 'your_database_name');

3. Test the Application

    Ensure your web server (e.g., Apache) and MySQL server are running.
    Open the index.php file in your web browser by navigating to the appropriate URL (e.g., http://localhost/path_to_your_project/index.php).
    Test the CRUD functionality by adding, editing, viewing, and deleting products with multiple images.

Project Structure

    config.php: Configuration file for database connection and upload settings.
    DB.class.php: Database class containing methods for CRUD operations.
    index.php: Main page to list and manage products.
    addEdit.php: Page to add or edit a product.
    postAction.php: Script to handle form submissions and CRUD operations.
    ajax_request.php: Script to handle AJAX requests, such as image deletion.
    uploads/: Directory to store uploaded images.
    SQL/crud_db.sql: SQL file to set up the database schema.

Technologies Used

    PHP: Server-side scripting language.
    MySQL: Relational database management system.
    Bootstrap: Front-end framework for responsive design.
    JavaScript: For AJAX requests and dynamic functionality.

Notes

    Make sure the uploads/ directory is writable by the web server.
    For security reasons, ensure proper validation and sanitization of user inputs in a production environment.
