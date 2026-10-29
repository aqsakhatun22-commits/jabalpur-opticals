<?php
/**
 * config.php — DATABASE CONNECTION (the only file that talks to MySQL's door)
 * Every other PHP file starts with:  require 'config.php';
 * so they all share the same connection variable called $conn.
 * Change the values below to match XAMPP (local) or your live host.
 */

// ---- Settings you edit -------------------------------------------------
$DB_HOST = "localhost";          // where MySQL runs. XAMPP: "localhost". Free hosts often give something like sql123.infinityfree.com
$DB_USER = "root";               // MySQL username. XAMPP default is "root"
$DB_PASS = "";                   // MySQL password. XAMPP default is empty
$DB_NAME = "jabalpur_opticals";  // database name (created by sql/schema.sql)

// RESET KEY: a long secret only YOU know. reset_password.php needs it to
// set a new shop-keeper password. Change it to your own text (12+ characters).
$RESET_KEY = "123456789012";

// ---- Open the connection ----------------------------------------------
// mysqli_connect(host, user, password, database) tries to log in to MySQL.
// It returns a "connection" on success, or false on failure.
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// If the connection failed (wrong password, MySQL stopped, database missing)...
if (!$conn) {
    http_response_code(500); // send HTTP status 500 = "server error"
    // die() prints a message and STOPS the whole script, so nothing else runs without a database.
    die(json_encode([        // json_encode turns a PHP array into JSON text
        "success" => false,
        "error"   => "Database connection failed: " . mysqli_connect_error() // the reason MySQL gave
    ]));
}

// Make text (names, messages) save and load correctly, including special characters.
mysqli_set_charset($conn, "utf8mb4");
