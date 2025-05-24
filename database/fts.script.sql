-- Drop old FTS table if it exists
DROP TRIGGER IF EXISTS services_ai;
DROP TRIGGER IF EXISTS services_au;
DROP TRIGGER IF EXISTS services_ad;
DROP TABLE IF EXISTS ServicesFTS;

-- Create the FTS5 virtual table with proper configuration
CREATE VIRTUAL TABLE ServicesFTS USING fts5(
  title,
  description,
  poster_name,
  content='Services',
  content_rowid='id',
  prefix='2 3 4 5',
  tokenize='porter unicode61'  -- This helps with stemming
);

-- Populate the FTS table with existing data
INSERT INTO ServicesFTS(rowid, title, description, poster_name)
SELECT s.id, s.title, s.description, u.name
FROM Services s
JOIN Users u ON s.user_id = u.id;

-- Recreate your triggers (same as before)
CREATE TRIGGER services_ai AFTER INSERT ON Services BEGIN
  INSERT INTO ServicesFTS(rowid, title, description, poster_name)
  VALUES (new.id, new.title, new.description,
    (SELECT name FROM Users WHERE id = new.user_id));
END;

CREATE TRIGGER services_ad AFTER DELETE ON Services BEGIN
  DELETE FROM ServicesFTS WHERE rowid = old.id;
END;

CREATE TRIGGER services_au AFTER UPDATE ON Services BEGIN
  DELETE FROM ServicesFTS WHERE rowid = old.id;
  INSERT INTO ServicesFTS(rowid, title, description, poster_name)
  VALUES (new.id, new.title, new.description,
    (SELECT name FROM Users WHERE id = new.user_id));
END;
