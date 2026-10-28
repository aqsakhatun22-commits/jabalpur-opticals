<?php
/**
 * config.php
 * ----------
 * This file does ONE job: open a connection to the MySQL database
 * so the other PHP files (enquiry.php, view_enquiries.php) can use
 * it. Every other PHP file starts with  require 'config.php';
 * so they all share this same $conn connection.
 *
 * CHANGE THESE FOUR VALUES to match your own setup before you
 * upload the site — either your local XAMPP/WAMP settings, or the
 * database details your web host / college gives you.
 */

$DB_HOST = "localhost";          // almost always "localhost"
$DB_USER = "root";               // your MySQL username (phpMyAdmin login) — on XAMPP this is "root"
$DB_PASS = "";                   // your MySQL password — blank by default on XAMPP
$DB_NAME = "jabalpur_opticals";  // must match the database name created by sql/schema.sql

// mysqli_connect() opens the connection to MySQL using the 4
// settings above, and returns a "connection handle" we call $conn.
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// If the connection failed (wrong password, database not created
// yet, MySQL not running, etc.) stop everything here and show the
// error as JSON, instead of letting the rest of the script run
// against a connection that doesn't exist.
if (!$conn) {
    http_response_code(500); // HTTP 500 = "server error"
    die(json_encode([
        "success" => false,
        "error"   => "Database connection failed: " . mysqli_connect_error()
    ]));
}

// Make sure text with special characters (accents, symbols) is
// stored and read back correctly.
mysqli_set_charset($conn, "utf8mb4");
