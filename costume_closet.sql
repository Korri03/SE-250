-- Creates the database and the loans table, then adds the three sample rows.
-- Import this in phpMyAdmin (Import tab) or paste it into the SQL tab.
CREATE DATABASE IF NOT EXISTS costume_closet;
USE costume_closet;

CREATE TABLE IF NOT EXISTS loans (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  item      VARCHAR(60) NOT NULL,
  borrower  VARCHAR(60) NOT NULL,
  due_date  DATE,
  returned  TINYINT(1) DEFAULT 0   -- 0 = still out, 1 = returned
);

INSERT INTO loans (item, borrower, due_date) VALUES
  ('Witch hat',     'Maya',   '2026-11-02'),
  ('Vampire cape',  'Jordan', '2026-11-01'),
  ('Skeleton mask', 'Sam',    '2026-11-03');
