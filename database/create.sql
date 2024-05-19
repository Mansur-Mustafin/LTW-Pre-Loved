
/*******************************************************************************
   My Database - Version 0.1
   Script: create.sql
   Description: Creates and populates the My database.
   DB Server: Sqlite
   * to populate this database use populate.sql script
********************************************************************************/

DROP TABLE IF EXISTS ItemTags;
DROP TABLE IF EXISTS Tags;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Wishlist;
DROP TABLE IF EXISTS ShoppingCart;
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
    address TEXT,
    created_at INTEGER
);

CREATE TABLE Items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
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
    created_at INTEGER,
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
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (item_id) REFERENCES Items(id)
);

CREATE TABLE ShoppingCart (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    item_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (item_id) REFERENCES Items(id)
);

CREATE TABLE Transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller_id INTEGER NOT NULL,
    buyer_id INTEGER NOT NULL,
    item_id INTEGER NOT NULL,
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at INTEGER,
    FOREIGN KEY (seller_id) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (buyer_id) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (item_id) REFERENCES Items(id) ON DELETE SET NULL
);

CREATE TABLE Messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_time INTEGER,
    files TEXT,
    text TEXT NOT NULL,
    item_id_exchange INTEGER,                    -- Case if some body want to exchange the item 
    chat_id INTEGER NOT NULL,                    -- ID of Chat
    from_user_id INTEGER NOT NULL,               -- Who send the message
    to_user_id INTEGER NOT NULL,                 -- Who receive the message
    is_read INTEGER DEFAULT 0,                   -- Is message red
    FOREIGN KEY (chat_id) REFERENCES Chats(id),
    FOREIGN KEY (from_user_id) REFERENCES Users(id),
    FOREIGN KEY (to_user_id) REFERENCES Users(id)
);

CREATE TABLE Chats (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL,                       -- For what item that message was written
    from_user_id INTEGER NOT NULL,                  -- Who send the message
    to_user_id INTEGER NOT NULL,                    -- Who receive the message
    FOREIGN KEY (item_id) REFERENCES Items(id),
    FOREIGN KEY (from_user_id) REFERENCES Users(id),
    FOREIGN KEY (to_user_id) REFERENCES Users(id)
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


-- Its me :-)
INSERT INTO Users (username, password, email, image_path, banned, admin_flag,address,created_at)
VALUES 
('Mansur','$2y$10$dGGYn8udsQus681UbEHBy.Et1G.DLdxbi/VpMHh1HRp3zx3takxeu','mansur@gmail.com','/data/profile_img/a5ec03d3e184f620948b1295a0b73a89038263f5134a0ec49083ab05331e459b.png',0,1,'',strftime('%s', 'now')),
('rubem','$2y$10$dGGYn8udsQus681UbEHBy.Et1G.DLdxbi/VpMHh1HRp3zx3takxeu','rubem@gmail.com','/data/profile_img/john_doe.jpeg',0,1,'',strftime('%s', 'now'));