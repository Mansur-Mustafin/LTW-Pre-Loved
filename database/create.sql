
/*******************************************************************************
   My Database - Version 0.1
   Script: create.sql
   Description: Creates and populates the My database.
   DB Server: Sqlite
********************************************************************************/

DROP TABLE IF EXISTS ItemTags;
DROP TABLE IF EXISTS Tags;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Wishlist;
DROP TABLE IF EXISTS Items;
DROP TABLE IF EXISTS Condition;
DROP TABLE IF EXISTS Size;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Users;

/*******************************************************************************
   Create Tables
********************************************************************************/

CREATE TABLE Users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    phonenumber TEXT,
    image_path TEXT,
    banned BOOLEAN DEFAULT 0,                   -- Is banned user
    admin_flag BOOLEAN DEFAULT 0,               -- User is admin
    address TEXT
);

CREATE TABLE Items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    brand TEXT,
    model TEXT,
    description TEXT,
    images TEXT,
    price REAL NOT NULL,
    tradable INTEGER DEFAULT 0,
    priority INTEGER DEFAULT 1,
    user_id INTEGER NOT NULL,                   -- User who vending item
    category_id INTEGER,
    size_id INTEGER,
    condition_id INTEGER,
    model_id INTEGER,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE SET NULL,
    FOREIGN KEY (size_id) REFERENCES Size(id) ON DELETE SET NULL,
    FOREIGN KEY (condition_id) REFERENCES Condition(id) ON DELETE SET NULL,
    FOREIGN KEY (model_id) REFERENCES Models(id) ON DELETE SET NULL
);

CREATE TABLE Wishlist (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    item_id INTEGER NOT NULL,
    added_on DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (item_id) REFERENCES Items(id)
);


CREATE TABLE Transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller_id INTEGER NOT NULL,
    buyer_id INTEGER NOT NULL,
    item_id INTEGER NOT NULL,
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (buyer_id) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (item_id) REFERENCES Items(id) ON DELETE SET NULL
);


CREATE TABLE Messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    files TEXT,
    text TEXT NOT NULL,
    item_id_exchange INTEGER,                    -- Case if some body want to exchange the item 
    item_id INTEGER NOT NULL,                    -- For what item that message was written
    sender_id INTEGER NOT NULL,                  -- Who send the message
    FOREIGN KEY (item_id) REFERENCES Items(id),
    FOREIGN KEY (sender_id) REFERENCES Users(id)
);

CREATE TABLE Categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE Size (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE Condition (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE Tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE Brands (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE Models (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    brand_id INTEGER NOT NULL,
    name TEXT NOT NULL UNIQUE,
    FOREIGN KEY (brand_id) REFERENCES Brands(id) ON DELETE CASCADE
);

CREATE TABLE ItemTags (
    item_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    PRIMARY KEY (item_id, tag_id),
    FOREIGN KEY (item_id) REFERENCES Items(id) ON DELETE CASCADE,   
    FOREIGN KEY (tag_id) REFERENCES Tags(id) ON DELETE CASCADE
);

/*******************************************************************************
   Populate Tables
********************************************************************************/
-- Populate Users
INSERT INTO Users (username, password, email, phonenumber, image_path, banned, admin_flag, address)
VALUES 
('john_doe', 'pass123', 'john.doe@example.com', '123-456-7890', '/data/images/john_doe.png', 0, 0, '123 Main St, Hometown'),
('jane_smith', 'password', 'jane.smith@example.com', '234-567-8901', '/data/images/jane_smith.png', 0, 1, '456 Elm St, Bigcity');

-- Populate Categories
INSERT INTO Categories (name)
VALUES 
('Electronics'),
('Clothing'),
('Cars'),
('Books');

-- Populate Size
INSERT INTO Size (name)
VALUES 
('Small'),
('Medium'),
('Large');

-- Populate Condition
INSERT INTO Condition (name)
VALUES 
('New'),
('Used');

-- Populate Tags
INSERT INTO Tags (name)
VALUES 
('Pay 2 buy 1'),
('Limited Edition'),
('Rare');

-- Populate Brands
INSERT INTO Brands (name)
VALUES 
('BrandA'),
('BrandB'),
('BrandC');

-- Populate Models (assuming BrandA's id is 1)
INSERT INTO Models (brand_id, name)
VALUES 
(1, 'ModelX'),
(1, 'ModelY'),
(2, 'ModelA'),
(2, 'ModelB'),
(3, 'ModelQ'),
(3, 'ModelYR');


-- Populate Items
INSERT INTO Items (brand, model, description, images, price, user_id, category_id, size_id, condition_id)
VALUES 
('BrandA', 'ModelX', 'Description of item 1', '/images/item1.png', 19.99, 1, 1, 1, 1),
('BrandB', 'ModelY', 'Description of item 2', '/images/item2.png', 29.99, 2, 2, 2, 2);

-- Populate Wishlist
INSERT INTO Wishlist (user_id, item_id)
VALUES 
(1, 2),
(2, 1);

-- Populate Transactions
INSERT INTO Transactions (seller_id, buyer_id, item_id)
VALUES 
(1, 2, 1),
(2, 1, 2);

-- Populate Messages
INSERT INTO Messages (files, text, item_id_exchange, item_id, sender_id)
VALUES 
('/files/file1.pdf', 'Is this item still available?', NULL, 1, 2);

-- Populate ItemTags
INSERT INTO ItemTags (item_id, tag_id)
VALUES 
(1, 1),
(2, 2);
