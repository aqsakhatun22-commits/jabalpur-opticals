-- ==========================================================
-- Jabalpur Opticals — database schema
-- ----------------------------------------------------------
-- Run this ONCE, in phpMyAdmin, to create the database and the
-- table that stores every enquiry submitted from the website's
-- enquiry form.
--
-- HOW TO RUN THIS FILE IN phpMyAdmin:
--   1. Open phpMyAdmin.
--   2. Click the "Import" tab along the top.
--   3. Click "Choose file" and select this schema.sql file.
--   4. Click "Go" at the bottom of the page.
--   That's it — the database and table are created.
-- ==========================================================

-- Create the database. If your hosting already gave you a
-- database name to use instead (common on shared hosting),
-- delete this CREATE DATABASE / USE pair and just run the
-- CREATE TABLE statement inside the database they gave you.
CREATE DATABASE IF NOT EXISTS jabalpur_opticals
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Tell MySQL to use that database for the statements below.
USE jabalpur_opticals;

-- The table that stores every enquiry sent from the website.
-- One row = one form submission.
CREATE TABLE IF NOT EXISTS enquiries (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,         -- unique row number, added automatically
  name       VARCHAR(100)  NOT NULL,                          -- visitor's name
  phone      VARCHAR(20)   NOT NULL,                          -- visitor's phone number
  need       VARCHAR(100)  NOT NULL,                          -- what they picked in the "looking for" dropdown
  message    TEXT          NULL,                              -- optional free-text message, can be left blank
  created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP  -- filled in automatically with the submission time
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
