-- First completely clean up existing FTS setup
DROP TRIGGER IF EXISTS services_ai;
DROP TRIGGER IF EXISTS services_au;
DROP TRIGGER IF EXISTS services_ad;
DROP TABLE IF EXISTS ServicesFTS;

-- Create the FTS table
CREATE VIRTUAL TABLE ServicesFTS USING fts5(
  title,
  description,
  content='Services',
  content_rowid='id',
  prefix='2 3 4 5',
  tokenize='porter unicode61'
);

-- Simple insert trigger
CREATE TRIGGER services_ai AFTER INSERT ON Services BEGIN
  INSERT INTO ServicesFTS(rowid, title, description)
  VALUES (NEW.id, NEW.title, NEW.description);
END;

-- Safe update trigger (without conditional logic)
CREATE TRIGGER services_au AFTER UPDATE ON Services BEGIN
  UPDATE ServicesFTS 
  SET title = NEW.title, 
      description = NEW.description
  WHERE rowid = NEW.id;
END;

-- Delete trigger
CREATE TRIGGER services_ad AFTER DELETE ON Services BEGIN
  DELETE FROM ServicesFTS WHERE rowid = old.id;
END;

-- Populate with existing data
INSERT INTO ServicesFTS(rowid, title, description)
SELECT id, title, description FROM Services;