<?php
/**
 * export_csv.php — downloads all enquiries as a .csv file.
 * CSV = plain text table (values separated by commas). Excel and Google Sheets open it directly.
 * Only logged-in users can use it (auth.php checks first).
 */

require 'auth.php';     // gatekeeper: not logged in -> redirected to login.php
require 'config.php';   // database connection ($conn)

// These headers tell the browser: "this is a file to DOWNLOAD, not a page to show".
header('Content-Type: text/csv; charset=utf-8');                                        // file type
header('Content-Disposition: attachment; filename="enquiries-' . date('Y-m-d') . '.csv"'); // file name includes today's date

$out = fopen('php://output', 'w');   // "php://output" = write straight into the download

fwrite($out, "\xEF\xBB\xBF");        // 3 invisible bytes (BOM) so Excel reads the file as UTF-8 (correct special characters)

fputcsv($out, ['No.', 'Name', 'Phone', 'Looking for', 'Message', 'Received']); // first row = column headings

// safe(): protects against "CSV injection". Text starting with = + - or @ could run as an Excel formula;
// putting a ' in front turns it into harmless plain text.
function safe($v) {
    $v = (string)$v;                                           // make sure it is text
    return preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v;      // starts with a risky character? add ' in front
}

$result = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY created_at DESC"); // all rows, newest first
$i = 1;                                                        // row number counter
while ($row = mysqli_fetch_assoc($result)) {                   // loop through each saved enquiry
    fputcsv($out, [                                            // write one CSV line
        $i++,                                                  // number
        safe($row['name']),                                    // name (made safe)
        safe($row['phone']),                                   // phone
        safe($row['need']),                                    // what they wanted
        safe($row['message']),                                 // message
        $row['created_at']                                     // date/time received
    ]);
}

fclose($out);                  // finish the download
mysqli_close($conn);           // close the database connection
