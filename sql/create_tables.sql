# Drop tables if they already exist (order matters)
DROP TABLE IF EXISTS lead_singer;
DROP TABLE IF EXISTS has_song;
DROP TABLE IF EXISTS purchase_item;
DROP TABLE IF EXISTS purchase;
DROP TABLE IF EXISTS return_item;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS purchase_return;

# (re) create tables
CREATE TABLE item (
    upc INT NOT NULL,
    title VARCHAR(64) NOT NULL,
    itemtype VARCHAR(32) NULL,
    category VARCHAR(32) NULL,
    company VARCHAR(32) NULL,
    releaseyear INT NULL,
    price REAL NOT NULL,
    stock INT NOT NULL,
    PRIMARY KEY (upc)
);

CREATE TABLE lead_singer (
    upc INT NOT NULL,
    sname VARCHAR(64) NOT NULL,
    PRIMARY KEY (upc, sname),
    FOREIGN KEY (upc)
		REFERENCES item (upc)
        ON DELETE cascade
        ON UPDATE cascade
);

CREATE TABLE has_song (
    upc INT NOT NULL,
    title VARCHAR(64) NOT NULL,
    PRIMARY KEY (upc, title),
    FOREIGN KEY (upc)
		REFERENCES item (upc)
		ON DELETE cascade
        ON UPDATE cascade
);

CREATE TABLE customer (
    cid VARCHAR(32) NOT NULL ,
    cpassword VARCHAR(32) NOT NULL,
    cname VARCHAR(64) NOT NULL,
    address VARCHAR(128) NOT NULL,
    phone VARCHAR(16) NOT NULL,
    PRIMARY KEY (cid)
);

CREATE TABLE purchase (
    receiptId INT NOT NULL AUTO_INCREMENT,
    pdate DATE NOT NULL,
    cid VARCHAR(32) NOT NULL,
    cardNumber CHAR(16) NOT NULL,
    expiryDate CHAR(4) NOT NULL,
    expectedDate DATE NULL,
    deliveredDate DATE NULL,
    PRIMARY KEY (receiptId),
    FOREIGN KEY (cid)
		REFERENCES customer (cid)
        ON DELETE cascade
        ON UPDATE cascade
);
       
CREATE TABLE purchase_item (
    receiptId INT NOT NULL,
    upc INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (receiptId, upc),
    FOREIGN KEY (upc)
		REFERENCES item (upc)
        ON DELETE cascade
        ON UPDATE cascade,
    FOREIGN KEY (receiptId)
		REFERENCES purchase (receiptId)
        ON DELETE cascade
        ON UPDATE cascade
);

DROP TABLE IF EXISTS purchase_return;
CREATE TABLE purchase_return (
    retid INT NOT NULL AUTO_INCREMENT,
    retdate DATE NOT NULL,
    receiptId INT NOT NULL,
    PRIMARY KEY (retid),
    FOREIGN KEY (receiptID)
    REFERENCES purchase (receiptId)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE return_item (
    retid INT NOT NULL,
    upc INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (retid, upc),
    FOREIGN KEY (upc)
		REFERENCES item (upc)
        ON DELETE cascade
        ON UPDATE cascade,
    FOREIGN KEY (retid)
		REFERENCES purchase_return (retid)
        ON DELETE cascade
        ON UPDATE cascade
);

# Insert example values for testing
INSERT INTO customer (cid, cpassword, cname, address, phone) VALUES ("johnson", "abc123", "J Johnson", "123 Main St", "(555) 666-7777");
INSERT INTO customer (cid, cpassword, cname, address, phone) VALUES ("mitchell", "abc123", "M Mitchell", "123 Main St", "(555) 000-2222");
INSERT INTO customer (cid, cpassword, cname, address, phone) VALUES ("swarovski", "abc123", "S Swarovski", "123 Main St", "(555) 123-1234");
INSERT INTO customer (cid, cpassword, cname, address, phone) VALUES ("prado", "abc123", "P Prado", "123 Main St", "(555) 312-4312");
INSERT INTO customer (cid, cpassword, cname, address, phone) VALUES ("tokarski", "abc123", "T Tokarski", "123 Main St", "(555) 012-3456");

INSERT INTO item (upc, title, itemtype, category, company, releaseyear, price, stock) VALUES (1, "+", "CD", "Folk", "Asylum Records", 2011, 9.99, 15);
INSERT INTO lead_singer (upc, sname) VALUES (1, "Ed Sheeran");
INSERT INTO has_song (upc, title) VALUES (1, "The A Team");
INSERT INTO has_song (upc, title) VALUES (1, "You Need Me, I Don't Need You");
INSERT INTO has_song (upc, title) VALUES (1, "Lego House");
INSERT INTO has_song (upc, title) VALUES (1, "Drunk");
INSERT INTO has_song (upc, title) VALUES (1, "Small Bump");
INSERT INTO has_song (upc, title) VALUES (1, "Give Me Love");

INSERT INTO item (upc, title, itemtype, category, company, releaseyear, price, stock) VALUES (2, "Comedown Machine", "CD", " Indie Rock", "RCA", 2013, 12.99, 25);
INSERT INTO lead_singer (upc, sname) VALUES (2, "Julian Casablancas");
INSERT INTO lead_singer (upc, sname) VALUES (2, "Albert Hammond, Jr.");
INSERT INTO lead_singer (upc, sname) VALUES (2, "Nick Valensi");
INSERT INTO lead_singer (upc, sname) VALUES (2, "Nikolai Fraiture");
INSERT INTO lead_singer (upc, sname) VALUES (2, "Fabrizio Moretti");
INSERT INTO has_song (upc, title) VALUES (2, "All The Time");
INSERT INTO has_song (upc, title) VALUES (2, "Tap Out");
INSERT INTO has_song (upc, title) VALUES (2, "50/50");
INSERT INTO has_song (upc, title) VALUES (2, "Partners In Crime");

INSERT INTO item (upc, title, itemtype, category, company, releaseyear, price, stock) VALUES (3, "Suck It And See", "CD", " Psychedelic Rock", "Domino", 2011, 10.99, 20);
INSERT INTO lead_singer (upc, sname) VALUES (3, "Alex Turner");
INSERT INTO lead_singer (upc, sname) VALUES (3, "Jamie Cook");
INSERT INTO lead_singer (upc, sname) VALUES (3, "Nick O'Malley");
INSERT INTO lead_singer (upc, sname) VALUES (3, "Matt Helders");
INSERT INTO has_song (upc, title) VALUES (3, "Don't Sit Down 'Cause I've Moved Your Chair");
INSERT INTO has_song (upc, title) VALUES (3, "The Hellcat Spangled Shalalala");
INSERT INTO has_song (upc, title) VALUES (3, "Suck It and See");
INSERT INTO has_song (upc, title) VALUES (3, "Black Treacle");

INSERT INTO item (upc, title, itemtype, category, company, releaseyear, price, stock) VALUES (4, "Love Songs", "DVD", "Rock", "Rocket", 1995, 19.99, 15);
INSERT INTO lead_singer (upc, sname) VALUES (4, "Elton Hohn");
INSERT INTO has_song (upc, title) VALUES (4, "You Can Make History (Young Again)");
INSERT INTO has_song (upc, title) VALUES (4, "Sacrifice");
INSERT INTO has_song (upc, title) VALUES (4, "Your Song");
INSERT INTO has_song (upc, title) VALUES (4, "The One");
INSERT INTO has_song (upc, title) VALUES (4, "Blue Eyess");

INSERT INTO item (upc, title, itemtype, category, company, releaseyear, price, stock) VALUES (5, "Night Visions", "DVD", "Alternative Rock", "Interscope", 2012, 20.99, 20);
INSERT INTO lead_singer (upc, sname) VALUES (5, "Dan Reynolds");
INSERT INTO lead_singer (upc, sname) VALUES (5, "Daniel Wayne Sermon");
INSERT INTO lead_singer (upc, sname) VALUES (5, "Ben McKee");
INSERT INTO lead_singer (upc, sname) VALUES (5, "Daniel Platzman");
INSERT INTO has_song (upc, title) VALUES (5, "It's Time");
INSERT INTO has_song (upc, title) VALUES (5, "Radioactive");
INSERT INTO has_song (upc, title) VALUES (5, "Hear Me");
INSERT INTO has_song (upc, title) VALUES (5, "Demons");
INSERT INTO has_song (upc, title) VALUES (5, "On Top Of The World");

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (1, "2014-01-01", "johnson", "5555666677778888", "0916", "2014-01-04", "2014-01-02");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (1, 2, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (1, 1, 2);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (1, 5, 1);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (2, "2014-01-03", "mitchell", "5555666655556666", "0317", NULL, NULL);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (2, 3, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (2, 4, 1);


INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (3, "2014-01-18", "prado", "7777888877778888", "1116", "2014-01-20", "2014-01-19");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (3, 1, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (3, 2, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (3, 3, 1);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (4, "2014-02-02", "swarovski", "5555666644445555", "1017", NULL, NULL);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (4, 5, 2);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (5, "2014-02-09", "johnson", "5555666677778888", "0916", "2014-02-11", "2014-02-09");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (5, 3, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (5, 1, 2);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (5, 5, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (5, 4, 1);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (6, "2014-03-13", "tokarski", "5432234554322345", "0619", "2014-03-18", "2014-03-14");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (6, 2, 1);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (7, "2014-03-15", "swarovski", "5555666644445555", "1017", NULL, NULL);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (7, 5, 1);
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (7, 4, 2);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (8, "2014-03-17", "mitchell", "5555666655556666", "0317", "2014-03-20", "2014-03-17");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (8, 2, 1);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (9, "2014-03-29", "johnson", "5555666677778888", "0916", "2014-03-31", "2014-03-30");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (9, 1, 1);

INSERT INTO purchase (receiptId, pdate, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (10, "2014-04-02", "tokarski", "5432234554322345", "0619", "2014-04-05", "2014-04-03");
INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (10, 3, 1);

INSERT INTO purchase_return (retid, retdate, receiptId) VALUES (1, "2014-02-13", 4);
INSERT INTO return_item (retid, upc, quantity) VALUES (1, 5, 1);

INSERT INTO purchase_return (retid, retdate, receiptId) VALUES (2, "2014-03-17", 7);
INSERT INTO return_item (retid, upc, quantity) VALUES (2, 5, 1);
INSERT INTO return_item (retid, upc, quantity) VALUES (2, 4, 1);

INSERT INTO purchase_return (retid, retdate, receiptId) VALUES (3, "2014-04-11", 9);
INSERT INTO return_item (retid, upc, quantity) VALUES (3, 1, 1);