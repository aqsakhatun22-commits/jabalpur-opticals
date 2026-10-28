JABALPUR OPTICALS — website + enquiry form backend
=====================================================

WHAT'S IN THIS FOLDER
----------------------
  index.html                 the website itself (open this in a browser)
  images/                    put your shop photos here (see the .txt inside)
  php/config.php             database connection settings (edit this)
  php/enquiry.php            saves enquiry form submissions into MySQL
  php/view_enquiries.php     a simple page to see saved enquiries
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
