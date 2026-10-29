<?php
/**
 * logout.php — ends the shop keeper's session and returns to the login page.
 */
session_start();                  // resume the session so we can end it
$_SESSION = [];                   // empty all stored session data
session_destroy();                // delete the session on the server
header('Location: login.php');    // send the browser to the login page
exit;                             // stop the script
