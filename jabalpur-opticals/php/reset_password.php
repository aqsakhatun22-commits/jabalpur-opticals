<?php
/**
 * reset_password.php — "Forgot password" page.
 * A hashed password can never be read back, so "forgot password" means setting a NEW one.
 * This page needs the secret $RESET_KEY from config.php, which only the developer knows,
 * so a stranger cannot use it to take over the shop keeper's login.
 */

require 'config.php';   // gives us $conn and $RESET_KEY

// The key counts as "set" only if it is long enough AND was changed from the placeholder text.
$keyConfigured = isset($RESET_KEY)                                  // does the variable exist?
              && strlen($RESET_KEY) >= 12                           // at least 12 characters?
              && $RESET_KEY !== 'CHANGE-THIS-TO-A-LONG-SECRET';     // not the default text?

$msg  = '';       // message to show
$done = false;    // true after a successful reset

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $keyConfigured) {   // form submitted AND page is enabled
    $key      = $_POST['reset_key'] ?? '';      // key typed in
    $username = trim($_POST['username'] ?? ''); // which admin to reset
    $new      = $_POST['password'] ?? '';       // new password
    $confirm  = $_POST['confirm'] ?? '';        // new password again

    // hash_equals() compares two strings in constant time, so attackers can't guess the key by timing.
    if (!hash_equals($RESET_KEY, $key)) {
        sleep(2);                               // slow down key guessing
        $msg = 'Wrong reset key.';
    } elseif (strlen($new) < 8) {               // password too short?
        $msg = 'The new password must be at least 8 characters.';
    } elseif ($new !== $confirm) {              // the two typed passwords differ?
        $msg = 'The two passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);   // scramble the new password
        $stmt = mysqli_prepare($conn, "UPDATE admins SET password_hash = ? WHERE username = ?"); // replace the old hash
        mysqli_stmt_bind_param($stmt, "ss", $hash, $username);  // two strings, in order
        mysqli_stmt_execute($stmt);                              // run it
        $changed = mysqli_stmt_affected_rows($stmt);             // how many rows changed (1 = success, 0 = no such username)
        mysqli_stmt_close($stmt);

        if ($changed > 0) {
            $done = true;
            $msg  = 'Password changed. You can now log in with the new password.';
        } else {
            $msg = 'No admin with that username was found.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reset password — Jabalpur Opticals</title>
<style>
  body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#0c0a06;color:#efe8d8;margin:0;min-height:100vh;display:grid;place-items:center} /* dark, centred */
  .card{width:min(92vw,380px);padding:2rem;border:1px solid rgba(230,178,76,.3);border-radius:14px;background:rgba(255,255,255,.03)} /* the card */
  h1{margin:0 0 .3rem;color:#f7e2a6;font-size:1.4rem}
  p{color:#a39b87;font-size:.95rem}
  label{display:block;margin:1rem 0 .35rem;color:#a39b87;font-size:.92rem}
  input{width:100%;padding:.8rem 1rem;border-radius:9px;border:1px solid rgba(230,178,76,.3);background:rgba(255,255,255,.05);color:#efe8d8;font-size:1rem}
  input:focus{outline:none;border-color:#e6b24c}
  button{margin-top:1.4rem;width:100%;padding:.85rem;border:0;border-radius:10px;font-weight:600;font-size:1rem;cursor:pointer;background:linear-gradient(120deg,#f7e2a6,#e6b24c);color:#1a1204}
  .msg{margin-top:1rem;color:#f7e2a6;font-size:.95rem}
  a{color:#f7e2a6}
</style>
</head>
<body>
  <div class="card">
    <h1>Reset password</h1>
    <?php if (!$keyConfigured): ?> <!-- key not set: page is switched off -->
      <p>This page is switched off. Set your own long <code>$RESET_KEY</code> in <code>php/config.php</code> to enable it.</p>
    <?php else: ?>
      <p>Needs the secret reset key held by the website developer.</p>
      <?php if ($msg): ?><div class="msg"><?php echo htmlspecialchars($msg); ?><?php if ($done): ?> <a href="login.php">Go to login</a><?php endif; ?></div><?php endif; ?>
      <?php if (!$done): ?> <!-- hide the form after success -->
      <form method="post" autocomplete="off">
        <label for="k">Reset key</label>
        <input id="k" name="reset_key" type="password" required>
        <label for="u">Username</label>
        <input id="u" name="username" required>
        <label for="p">New password (8+ characters)</label>
        <input id="p" name="password" type="password" minlength="8" required>
        <label for="c">Repeat new password</label>
        <input id="c" name="confirm" type="password" minlength="8" required>
        <button type="submit">Change password</button>
      </form>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
