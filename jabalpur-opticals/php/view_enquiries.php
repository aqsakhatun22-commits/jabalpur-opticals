<?php
/**
 * view_enquiries.php — the shop keeper's page: every enquiry, newest first.
 * PASSWORD PROTECTED: require 'auth.php' sends anyone not logged in to login.php.
 * Buttons: "Download Excel" (export_csv.php) and "Log out" (logout.php).
 */

require 'auth.php';     // gatekeeper: must come first, stops strangers
require 'config.php';   // database connection ($conn)

// Ask MySQL for every row of "enquiries", most recent first (ORDER BY ... DESC).
$result = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY created_at DESC");
$total  = mysqli_num_rows($result);   // how many rows came back
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- fit phone screens -->
<title>Enquiries — Jabalpur Opticals</title>
<style>
  body{font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#0c0a06;color:#efe8d8;padding:2rem;margin:0} /* dark page */
  .top{display:flex;flex-wrap:wrap;gap:1rem;align-items:center;justify-content:space-between;margin-bottom:1.4rem} /* title left, buttons right */
  h1{color:#f7e2a6;font-size:1.6rem;margin:0}
  .sub{color:#a39b87;margin:.2rem 0 0}
  .btns{display:flex;gap:.7rem}
  .btn{display:inline-block;padding:.7rem 1.2rem;border-radius:9px;text-decoration:none;font-weight:600;font-size:.95rem;border:1px solid #e6b24c;color:#f7e2a6} /* outlined button */
  .btn.solid{background:linear-gradient(120deg,#f7e2a6,#e6b24c);color:#1a1204;border-color:transparent} /* gold filled button */
  table{width:100%;border-collapse:collapse;background:rgba(255,255,255,.03);border-radius:10px;overflow:hidden} /* the data table */
  th,td{padding:.7rem .9rem;border-bottom:1px solid rgba(230,178,76,.18);text-align:left;font-size:.92rem;vertical-align:top} /* cells */
  th{color:#f7e2a6;background:rgba(230,178,76,.08)} /* heading cells */
  tr:last-child td{border-bottom:0}
  .wrap{overflow-x:auto} /* lets the table scroll sideways on a phone */
  .empty{color:#a39b87}
</style>
</head>
<body>
  <div class="top">
    <div>
      <h1>Enquiries received</h1>
      <p class="sub"><?php echo $total; ?> total, newest first.</p> <!-- print how many enquiries exist -->
    </div>
    <div class="btns">
      <a class="btn solid" href="export_csv.php">Download Excel</a> <!-- downloads a .csv file -->
      <a class="btn" href="logout.php">Log out</a>
    </div>
  </div>

  <?php if ($total === 0): ?> <!-- nothing saved yet -->
    <p class="empty">No enquiries yet. Submit the form on the website to see one appear here.</p>
  <?php else: ?>
    <div class="wrap">
    <table>
      <tr><th>#</th><th>Name</th><th>Phone</th><th>Looking for</th><th>Message</th><th>Received</th></tr>
      <?php
        $i = 1;                                        // row counter shown in the "#" column
        while ($row = mysqli_fetch_assoc($result)) {   // loop: take one row at a time until none are left
            echo "<tr>";                               // start a table row
            echo "<td>" . $i++ . "</td>";              // row number (then add 1)
            // htmlspecialchars() prints text safely: anything a visitor typed can never run as HTML.
            echo "<td>" . htmlspecialchars($row['name'])    . "</td>";
            echo "<td>" . htmlspecialchars($row['phone'])   . "</td>";
            echo "<td>" . htmlspecialchars($row['need'])    . "</td>";
            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";                              // end the row
        }
      ?>
    </table>
    </div>
  <?php endif; ?>

  <?php mysqli_close($conn); ?> <!-- close the database connection -->
</body>
</html>
