<?php
/**
 * auth.php — the "gatekeeper".
 * Put  require 'auth.php';  at the top of any page only the shop keeper may see.
 * If the visitor is not logged in, they are sent to login.php.
 * Logging in works through a SESSION: data kept on the server, linked to the
 * visitor by a cookie in their browser.
 */

// httponly: JavaScript can't read the session cookie (blocks some attacks).
// samesite: the cookie is not sent along with requests coming from other websites.
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);

session_start();                    // start or resume this visitor's session

if (empty($_SESSION['admin_id'])) { // no admin id stored = not logged in
    header('Location: login.php');  // redirect the browser to the login page
    exit;                           // STOP here, so the protected page never runs
}
