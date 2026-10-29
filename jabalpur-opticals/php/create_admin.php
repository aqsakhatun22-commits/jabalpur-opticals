<?php
/**
 * create_admin.php — ONE-TIME SETUP page that creates the shop keeper's login.
 * 1) Open it once. 2) Choose username + password (8+ characters). 3) DELETE this file afterwards.
 * It creates the "admins" table if needed and stores ONLY the password hash.
 * Once one admin exists, this page locks itself (strangers can't add admins).
 */

require 'config.php';   // database connection ($conn)

// Create the admins table if it does not exist yet (IF NOT EXISTS = harmless if it does).
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS admins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,   -- unique number, added automatically
    username      VARCHAR(50)  NOT NULL UNIQUE,              -- login name, no duplicates
    password_hash VARCHAR(255) NOT NULL,                     -- the scrambled password (never the real one)
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP -- when it was created
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Count the admins that already exist.
$count = (int) mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM admins"))[0];

$msg  = '';       // message shown to the user
$done = false;    // becomes true once the admin is created

if ($count > 0) {                                   // an admin already exists...
    $msg = 'An admin already exists. For safety this page is locked — please delete create_admin.php from the server.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {  // ...otherwise, if the form was submitted:
    $username = trim($_POST['username'] ?? '');     // typed username
    $password = $_POST['password'] ?? '';           // typed password

    if ($username === '' || strlen($password) < 8) {    // check the rules
        $msg = 'Enter a username and a password of at least 8 characters.';
    } else {
        // password_hash() makes a salted bcrypt hash. PASSWORD_DEFAULT = PHP's current recommended method.
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO admins (username, password_hash) VALUES (?, ?)"); // safe INSERT
        mysqli_stmt_bind_param($stmt, "ss", $username, $hash);  // two strings
        mysqli_stmt_execute($stmt);                              // save it
        mysqli_stmt_close($stmt);

        $done = true;
        $msg = 'Admin created. Now DELETE create_admin.php from the server, then log in at login.php.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create admin — Jabalpur Opticals</title>
<style>
  body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#0c0a06;color:#efe8d8;margin:0;min-height:100vh;display:grid;place-items:center} /* dark, centred */
  .card{width:min(92vw,380px);padding:2rem;border:1px solid rgba(230,178,76,.3);border-radius:14px;background:rgba(255,255,255,.03)} /* the card */
  h1{margin:0 0 .3rem;color:#f7e2a6;font-size:1.4rem}
  p{color:#a39b87;font-size:.95rem}
  label{display:block;margin:1rem 0 .35rem;color:#a39b87;font-size:.92rem}
  input{width:100%;padding:.8rem 1rem;border-radius:9px;border:1px solid rgba(230,178,76,.3);background:rgba(255,255,255,.05);color:#efe8d8;font-size:1rem}
  button{margin-top:1.4rem;width:100%;padding:.85rem;border:0;border-radius:10px;font-weight:600;font-size:1rem;cursor:pointer;background:linear-gradient(120deg,#f7e2a6,#e6b24c);color:#1a1204}
  .msg{margin-top:1rem;color:#f7e2a6;font-size:.95rem}
  a{color:#f7e2a6}
</style>
</head>
<body>
  <div class="card">
    <h1>Create shop keeper login</h1>
    <p>One-time setup. Delete this file when done.</p>
    <?php if ($msg): ?><div class="msg"><?php echo htmlspecialchars($msg); ?><?php if ($done): ?> <a href="login.php">Go to login</a><?php endif; ?></div><?php endif; ?> <!-- show the message -->
    <?php if ($count === 0 && !$done): ?> <!-- show the form only while no admin exists yet -->
    <form method="post" autocomplete="off">
      <label for="u">Username</label>
      <input id="u" name="username" required>
      <label for="p">Password (8+ characters)</label>
      <input id="p" name="password" type="password" minlength="8" required>
      <button type="submit">Create admin</button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
