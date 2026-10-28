<?php
/**
 * enquiry.php
 * -----------
 * This is the "backend" for the enquiry form on the website.
 *
 * WHAT HAPPENS, STEP BY STEP:
 *   1. The browser's JavaScript (the #form "submit" listener inside
 *      the <script> tag in index.html) sends the form's data to this
 *      file as JSON, using fetch().
 *   2. This file reads and decodes that JSON.
 *   3. It checks the data is valid (name and phone are not empty).
 *   4. It saves the enquiry as one new row in the MySQL "enquiries"
 *      table.
 *   5. It sends back a small JSON reply so the website's JavaScript
 *      knows whether it worked, and can show a message to the visitor.
 */

// Pull in the database connection ($conn) that config.php sets up.
require 'config.php';

// Every reply from this file is JSON, not an HTML page — this header
// tells the browser (and our own JavaScript fetch() call) that.
header('Content-Type: application/json');

// Only accept POST requests (form submissions). Visiting this file's
// URL directly in a browser sends a GET request, which we reject —
// this file is only meant to be called by the website's own form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // HTTP 405 = "method not allowed"
    die(json_encode(["success" => false, "error" => "Only POST requests are allowed."]));
}

// The front-end sends JSON in the request body (not a normal HTML
// form submission), so PHP's usual $_POST array is empty here — we
// have to read the raw request body ourselves and decode it.
$raw  = file_get_contents('php://input');  // the raw JSON text sent by fetch()
$data = json_decode($raw, true);           // turn that JSON text into a PHP associative array

// Pull out each expected field. "?? ''" means "use an empty string
// if this key is missing" — this stops the script from crashing on
// unexpected/missing data; it just fails the validation check below
// instead. trim() removes accidental leading/trailing spaces.
$name    = trim($data['name']    ?? '');
$phone   = trim($data['phone']   ?? '');
$need    = trim($data['need']    ?? '');
$message = trim($data['message'] ?? '');

// ---- Basic validation ----
// Name and phone are required; "need" and "message" are optional.
if ($name === '' || $phone === '') {
    http_response_code(400); // HTTP 400 = "bad request"
    die(json_encode(["success" => false, "error" => "Name and phone number are required."]));
}

// ---- Save the enquiry into the database ----
// A "prepared statement" is used instead of pasting the values
// directly into the SQL text. The ? characters are placeholders;
// mysqli_stmt_bind_param() fills them in SAFELY afterwards. This is
// what stops "SQL injection" — e.g. someone typing
//   '); DROP TABLE enquiries; --
// into the name field and damaging the database.
$sql  = "INSERT INTO enquiries (name, phone, need, message) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// "ssss" tells MySQL that all four values being bound are strings
// (s = string). bind_param fills the four "?" placeholders above,
// in the same order, with $name, $phone, $need, $message.
mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $need, $message);

// Actually run the INSERT. Returns true on success, false on failure.
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    // Default HTTP status is 200 OK — tell the front-end it worked.
    echo json_encode(["success" => true, "message" => "Enquiry saved. We will contact you soon."]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Could not save the enquiry. Please try again."]);
}

// Tidy up: close the prepared statement and the database connection.
mysqli_stmt_close($stmt);
mysqli_close($conn);
