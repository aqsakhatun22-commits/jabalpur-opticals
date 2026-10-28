<?php
/**
 * view_enquiries.php
 * -------------------
 * A very simple page that lists every enquiry stored in the MySQL
 * database, newest first. Open it in a browser at, for example:
 *     http://localhost/jabalpur-opticals/php/view_enquiries.php
 *
 * This is for YOU (the shop owner) to check enquiries, and is also
 * a handy page to show your teacher — it proves the data really is
 * being saved into MySQL by enquiry.php, not just disappearing.
 *
 * SECURITY NOTE: this page has no login, so anyone who finds the
 * URL can see it. That is fine for a college project / local demo,
 * but before using this for a real shop, add a login check at the
 * top of this file.
 */

require 'config.php'; // gives us the $conn database connection

// Ask MySQL for every row in "enquiries", most recent first.
$result = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Enquiries — Jabalpur Opticals</title>
<style>
  body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#0c0a06;color:#efe8d8;padding:2rem;margin:0}
  h1{color:#f7e2a6;font-size:1.6rem;margin:0 0 .3rem}
  p{color:#a39b87;margin:0 0 1.4rem}
  table{width:100%;border-collapse:collapse;background:rgba(255,255,255,.03);border-radius:10px;overflow:hidden}
  th,td{padding:.7rem .9rem;border-bottom:1px solid rgba(230,178,76,.18);text-align:left;font-size:.92rem;vertical-align:top}
  th{color:#f7e2a6;background:rgba(230,178,76,.08)}
  tr:last-child td{border-bottom:0}
  .empty{padding:1.2rem;color:#a39b87}
</style>
</head>
<body>
  <h1>Enquiries received</h1>
  <p><?php echo mysqli_num_rows($result); ?> total, newest first.</p>

  <?php if (mysqli_num_rows($result) === 0): ?>
    <p class="empty">No enquiries yet. Submit the form on the website to see one appear here.</p>
  <?php else: ?>
    <table>
      <tr><th>#</th><th>Name</th><th>Phone</th><th>Looking for</th><th>Message</th><th>Received</th></tr>
      <?php
        // Loop through every row MySQL returned and print one <tr> per row.
        // htmlspecialchars() escapes the data before printing it, which
        // stops anything a visitor typed in the form from being treated
        // as HTML — the same kind of protection esc() does in the
        // front-end JavaScript.
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . htmlspecialchars($row['name'])    . "</td>";
            echo "<td>" . htmlspecialchars($row['phone'])   . "</td>";
            echo "<td>" . htmlspecialchars($row['need'])    . "</td>";
            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";
        }
      ?>
    </table>
  <?php endif; ?>

  <?php mysqli_close($conn); ?>
</body>
</html>
