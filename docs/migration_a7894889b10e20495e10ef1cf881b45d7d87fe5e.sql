CREATE VIEW v AS SELECT p.id AS id, s.name AS title FROM pad_subject as s, pad as p WHERE s.id = p.subject_id;
ALTER TABLE pad ADD title VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) NOT NULL;
UPDATE pad, v SET pad.title = v.title WHERE pad.id = v.id;
DROP VIEW v;
ALTER TABLE pad DROP FOREIGN KEY FK_9D894EE523EDC87;
DROP TABLE pad_subject;
CREATE FUNCTION TITLE_SLUG(title VARCHAR(256)) RETURNS VARCHAR(256)
RETURN LOWER(REPLACE(REPLACE(title, 'è', 'e'), ' ', '-'));
UPDATE pad SET pad.slug = TITLE_SLUG(pad.title);
DROP FUNCTION TITLE_SLUG;