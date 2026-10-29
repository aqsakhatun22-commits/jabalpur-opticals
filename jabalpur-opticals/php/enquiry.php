<?php
/**
 * enquiry.php — receives the website's enquiry form and saves it in MySQL.
 * Flow: browser fetch() sends JSON -> this file validates it -> INSERT into
 * the "enquiries" table -> replies with JSON ({success:true} or an error).
 */

require 'config.php';                       // load the database connection ($conn)

header('Content-Type: application/json');   // our reply is JSON, not an HTML page

// Only accept POST (data being sent). Opening this URL in a browser is a GET, so we refuse it.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {    // what kind of request is this?
    http_response_code(405);                    // 405 = "method not allowed"
    die(json_encode(["success" => false, "error" => "Only POST requests are allowed."])); // stop with an error
}

// The page sends JSON, so PHP's normal $_POST is empty. Read the raw request body ourselves.
$raw  = file_get_contents('php://input');   // the raw text the browser sent
$data = json_decode($raw, true);            // turn the JSON text into a PHP array (true = array, not object)

// Pull out each field. "?? ''" means "if it is missing, use empty text" so PHP never crashes.
// trim() removes spaces at the start/end.
$name    = trim($data['name']    ?? '');    // visitor's name
$phone   = trim($data['phone']   ?? '');    // visitor's phone
$need    = trim($data['need']    ?? '');    // what they chose in the dropdown
$message = trim($data['message'] ?? '');    // optional message

// Validation: name and phone are required.
if ($name === '' || $phone === '') {        // either one empty?
    http_response_code(400);                // 400 = "bad request"
    die(json_encode(["success" => false, "error" => "Name and phone number are required."])); // stop with an error
}

// Prepared statement: the ? marks are placeholders filled in safely later.
// This stops SQL injection (someone typing SQL commands into the form to damage the database).
$sql  = "INSERT INTO enquiries (name, phone, need, message) VALUES (?, ?, ?, ?)"; // the SQL command
$stmt = mysqli_prepare($conn, $sql);                                              // send it to MySQL to prepare
mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $need, $message);           // "ssss" = four strings, filled in order
$ok = mysqli_stmt_execute($stmt);                                                 // run it: true if saved

if ($ok) {                                  // saved successfully
    echo json_encode(["success" => true, "message" => "Enquiry saved. We will contact you soon."]); // tell the page it worked
} else {                                    // something went wrong inside MySQL
    http_response_code(500);                // server error
    echo json_encode(["success" => false, "error" => "Could not save the enquiry. Please try again."]);
}

mysqli_stmt_close($stmt);                   // tidy up the statement
mysqli_close($conn);                        // close the database connection
