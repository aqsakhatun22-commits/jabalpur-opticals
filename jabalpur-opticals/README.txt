JABALPUR OPTICALS — website + enquiry form backend
=====================================================

WHAT'S IN THIS FOLDER
----------------------
  index.html                 the website itself (open this in a browser)
  images/                    put your shop photos here (see the .txt inside)
  php/config.php             database connection settings (edit this)
  php/enquiry.php            saves enquiry form submissions into MySQL
  php/view_enquiries.php     password-protected page listing enquiries
  php/login.php, logout.php  shop keeper login / logout
  php/auth.php               gatekeeper that protects the pages
  php/export_csv.php         "Download Excel" (.csv) of all enquiries
  php/create_admin.php       ONE-TIME page to create the login, then delete it
  php/reset_password.php     "forgot password" page (needs your secret reset key)
  sql/schema.sql             run this once in phpMyAdmin to create the database


1. EDITING THE TEXT ON THE SITE
---------------------------------
Open index.html in a text editor (VS Code, Notepad++, etc).
Near the top of the <script> section, find:

    const SHOP = { ... };

Every word on the site — shop name, tagline, tab text, address,
phone number, opening hours, enquiry dropdown options — lives
inside this one object. Change the text between the quotation
marks, save the file, and refresh the page in your browser.


2. ADDING YOUR SHOP PHOTOS
-----------------------------
Inside SHOP, find the "gallery" list:

    gallery: [
      { src: "images/shop-1.jpg", alt: "Shop frontage", caption: "Shop front" },
      ...
    ]

Put your actual photo files inside the "images" folder with those
exact file names (shop-1.jpg, shop-2.jpg, shop-3.jpg). You can add
more entries to the list for more photos, or change the file names
— just make sure the "src" matches the real file name. Until a
photo exists, that card shows a neat placeholder instead of a
broken image, so you can leave this until last.


3. HOW THE ENQUIRY FORM + DATABASE WORKS
-------------------------------------------
  Visitor fills the form on the website
          |
          v
  JavaScript in index.html sends it to  php/enquiry.php
          |
          v
  enquiry.php checks the data and saves it into the
  MySQL table "enquiries" (created by sql/schema.sql)
          |
          v
  php/view_enquiries.php lets you see every enquiry that
  has been saved, newest first

Every PHP file has comments above each step explaining what it
does and why — that's the part to walk your teacher through to
show you understand it, not just copied it.


4. RUNNING IT ON YOUR OWN COMPUTER (XAMPP) — do this first
---------------------------------------------------------------
This lets you test the whole thing, including the database,
before putting it online.

  1. Install XAMPP (apachefriends.org) — includes Apache (the web
     server), MySQL (the database) and phpMyAdmin.
  2. Open the XAMPP Control Panel and click "Start" next to both
     Apache and MySQL.
  3. Copy this whole "jabalpur-opticals" folder into XAMPP's
     "htdocs" folder (usually C:\xampp\htdocs on Windows).
  4. Open your browser and go to  http://localhost/phpmyadmin
  5. Click "Import" along the top, choose the file sql/schema.sql
     from this folder, then click "Go". This creates the
     "jabalpur_opticals" database and the "enquiries" table.
  6. Open php/config.php in your editor and check the four
     settings at the top match XAMPP's defaults — they usually do
     already (host: localhost, user: root, password: blank).
  7. In your browser, go to:
         http://localhost/jabalpur-opticals/
     The site should load. Fill in the enquiry form and submit it.
  8. Go to:
         http://localhost/jabalpur-opticals/php/view_enquiries.php
     You should see the enquiry you just submitted — this is the
     page to show your teacher to prove the database is working.


5. PUTTING IT ONLINE (live hosting) — do this once step 4 works
---------------------------------------------------------------
You need hosting that supports PHP and MySQL (most student-friendly
options: Hostinger, InfinityFree, 000webhost — or whatever hosting
your college/teacher gives you).

  1. Sign up for PHP + MySQL hosting and log in to its control
     panel (often called cPanel).
  2. In the control panel, find "MySQL Databases" and create a new
     database and a database user, and note down the database
     name, username, password and host it gives you.
  3. Open phpMyAdmin from the same control panel, select your new
     database, click "Import", choose sql/schema.sql, and click
     "Go" (same as step 4.5 above, just on the live server).
     Skip the CREATE DATABASE lines if your host already made the
     database for you — just run the CREATE TABLE part.
  4. Open php/config.php and replace the four values with the real
     ones your host gave you in step 2.
  5. Upload every file in this folder (index.html, images/, php/,
     sql/) to your hosting account, keeping the same folder
     structure — usually via the control panel's "File Manager",
     or an FTP program like FileZilla, into the "public_html" (or
     similar) folder.
  6. Visit your domain in a browser. Test the enquiry form, then
     check yourdomain.com/php/view_enquiries.php to confirm the
     submission was saved.
  7. Optional: point a custom domain name (e.g. jabalpuropticals.in)
     at the hosting account — your host's dashboard will have a
     "Domains" section with instructions for this.

That's the whole path from "files on your laptop" to "live
website with a working database" — steps 1-3 can be skipped if
you only need to demo it locally for your teacher.


6. SHOP KEEPER LOGIN + EXCEL DOWNLOAD
---------------------------------------
  1. After the site and database are set up, open:
         yoursite.com/php/create_admin.php
     (locally: http://localhost/jabalpur-opticals/php/create_admin.php)
  2. Choose a username and a password (8+ characters) and click
     "Create admin".
  3. DELETE php/create_admin.php straight afterwards.
  4. The shop keeper now opens  yoursite.com/php/login.php , signs in,
     and sees every enquiry. "Download Excel" saves a .csv file that
     opens in Excel or Google Sheets. "Log out" ends the session.

The password is stored as a bcrypt HASH (password_hash), never as
plain text. A hash is one-way: it cannot be turned back into the
password, so even someone who reads the database cannot see it.


7. WHERE THE SHOP KEEPER LOGS IN
----------------------------------
On the website there is a "Shop login" button in the top menu (after
the intro animation) and a "Shop keeper login" link in the footer at
the very bottom. Both open  php/login.php . After signing in he sees
the enquiries table and can download it as Excel.


8. FORGOT PASSWORD
--------------------
A hashed password cannot be read back, only replaced.
  1. Open php/config.php and change  $RESET_KEY  to your own long
     secret (12+ characters). Keep it private. Do this on the live
     server's config.php too.
  2. If the shop keeper forgets his password, open
         yoursite.com/php/reset_password.php
     enter your reset key, his username and a new password.
  3. Tell him the new password. Enquiries are not affected.
If $RESET_KEY is left unchanged, reset_password.php stays switched off.


NOTE: always open the site through  http://localhost/jabalpur-opticals/
(XAMPP running), never by double-clicking index.html or from inside a
zip/RAR preview. PHP only runs through the web server, so the login
page and enquiry form fail on file:// addresses.


9. HERO ZOOM ON PHONES (what was changed)
-------------------------------------------
The spectacles zoom is driven by scrolling. To make it show on every phone:
  - It is no longer switched off when the phone has "reduce motion" or
    battery saver turned on (that setting used to skip the whole effect).
    Only the endless decorative loops are turned off in that case.
  - The page no longer uses "overflow-x:hidden" in a way that breaks
    "position:sticky" on some phones (it uses overflow-x:clip).
  - If a phone browser still breaks sticky, the JavaScript detects it and
    pins the hero itself (functions checkPin / js-fixed in index.html).
  - Old-phone fallbacks added (no "inset" or "??" needed).
  - The hint says "Swipe up to step inside" on touch screens.
Test on a real phone: connect it to the SAME Wi-Fi as your computer and
open  http://YOUR-PC-IP/jabalpur-opticals/  (find the IP with "ipconfig").


10. COMMENTS
--------------
Every file now has comments explaining what each part and each function
does: index.html (HTML tags, every CSS rule, every JavaScript line) and all
PHP files. Read the comments from top to bottom to explain the code.
