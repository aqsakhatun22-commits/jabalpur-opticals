<?php
/**
 * login.php — the shop keeper's login page.
 * The database stores a password HASH (a one-way scramble made by password_hash()).
 * To check a login, password_verify() hashes what was typed and compares it with the stored hash.
 */

require 'config.php';                       // database connection ($conn)

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']); // safer session cookie
session_start();                            // start/resume the session

if (!empty($_SESSION['admin_id'])) {        // already logged in?
    header('Location: view_enquiries.php'); // skip the form, go to the enquiries
    exit;
}

$error = '';                                // text of any error message to show

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    // runs only when the login form was submitted
    $username = trim($_POST['username'] ?? ''); // typed username (spaces removed)
    $password = $_POST['password'] ?? '';       // typed password (spaces kept: they may be part of it)

    // Look up this username safely (prepared statement = no SQL injection).
    $stmt = mysqli_prepare($conn, "SELECT id, password_hash FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);   // fill the ? with the username (s = string)
    mysqli_stmt_execute($stmt);                      // run the query
    mysqli_stmt_bind_result($stmt, $id, $hash);      // columns will land in $id and $hash
    $found = mysqli_stmt_fetch($stmt);               // true if a matching username exists
    mysqli_stmt_close($stmt);                        // done with the query

    // password_verify(typed, stored hash) -> true only if they match.
    if ($found && password_verify($password, $hash)) {
        session_regenerate_id(true);                 // new session id after login (stops "session fixation")
        $_SESSION['admin_id'] = $id;                 // remember: this visitor is logged in
        header('Location: view_enquiries.php');      // go to the enquiries page
        exit;
    }

    sleep(1);                                        // 1-second delay slows down password guessing
    $error = 'Wrong username or password.';         // same message for wrong user OR wrong password (reveals nothing)
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- fit phone screens -->
<title>Login — Jabalpur Opticals</title>
<style>
  body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#0c0a06;color:#efe8d8;margin:0;min-height:100vh;display:grid;place-items:center} /* dark page, form centred */
  form{width:min(92vw,360px);padding:2rem;border:1px solid rgba(230,178,76,.3);border-radius:14px;background:rgba(255,255,255,.03)} /* the login card */
  h1{margin:0 0 .3rem;color:#f7e2a6;font-size:1.5rem} /* title */
  p{margin:0 0 1.4rem;color:#a39b87;font-size:.95rem} /* sub-text */
  label{display:block;margin:1rem 0 .35rem;color:#a39b87;font-size:.92rem} /* field labels */
  input{width:100%;padding:.8rem 1rem;border-radius:9px;border:1px solid rgba(230,178,76,.3);background:rgba(255,255,255,.05);color:#efe8d8;font-size:1rem} /* text boxes */
  input:focus{outline:none;border-color:#e6b24c} /* gold border while typing */
  button{margin-top:1.4rem;width:100%;padding:.85rem;border:0;border-radius:10px;font-weight:600;font-size:1rem;cursor:pointer;background:linear-gradient(120deg,#f7e2a6,#e6b24c);color:#1a1204} /* gold sign-in button */
  .err{margin-top:1rem;color:#ff9c8a;font-size:.93rem} /* red-ish error text */
  .forgot{display:block;margin-top:1rem;color:#a39b87;font-size:.9rem;text-decoration:none} /* small links under the form */
  .forgot:hover{color:#f7e2a6}
</style>
</head>
<body>
  <form method="post" autocomplete="off"> <!-- method="post" sends the typed data to this same file -->
    <h1>Shop keeper login</h1>
    <p>Sign in to see customer enquiries.</p>
    <label for="u">Username</label>
    <input id="u" name="username" required autofocus> <!-- name="username" = key read by $_POST['username'] -->
    <label for="p">Password</label>
    <input id="p" name="password" type="password" required> <!-- type="password" hides the characters -->
    <button type="submit">Sign in</button>
    <?php if ($error): ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?> <!-- show the error if there is one (htmlspecialchars = safe output) -->
    <a class="forgot" href="reset_password.php">Forgot password?</a>
    <a class="forgot" href="../index.html">Back to website</a>
  </form>
</body>
</html>
