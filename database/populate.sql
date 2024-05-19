/*******************************************************************************
   Populate Tables
********************************************************************************/

-- Insert sample data into Users table
INSERT INTO Users (username, password, email, phonenumber, image_path, banned, admin_flag, address,created_at)
VALUES 
    ('john_doe', '$2y$10$dGGYn8udsQus681UbEHBy.Et1G.DLdxbi/VpMHh1HRp3zx3takxeu', 'john.doe@example.com', '123-456-7890', '/data/profile_img/john_doe.jpeg', 0, 0, '123 Main St, Hometown',strftime('%s', 'now')),
    ('jane_smith', '$2y$10$dGGYn8udsQus681UbEHBy.Et1G.DLdxbi/VpMHh1HRp3zx3takxeu', 'jane.smith@example.com', '234-567-8901', '/data/profile_img/jane_smith.jpeg', 0, 1, '456 Elm St, Bigcity',strftime('%s', 'now'));

-- Insert sample data into Categories table
INSERT INTO Categories (name)
VALUES 
    ('Electronics'),
    ('Clothing'),
    ('Cars'),
    ('Books'),
    ('Furniture'),
    ('Jewelry'),
    ('Toys'),
    ('Sports Equipment'),
    ('Home Appliances'),
    ('Music Instruments'),
    ('Health & Beauty'),
    ('Tools & Hardware'),
    ('Pet Supplies'),
    ('Gardening Supplies'),
    ('Art & Craft Supplies'),
    ('Office Supplies'),
    ('Baby Products'),
    ('Kitchenware'),
    ('Outdoor Gear'),
    ('Collectibles'),
    ('Antiques');


-- Insert sample data into Size table
INSERT INTO Size (name)
VALUES 
    ('Small'),
    ('Medium'),
    ('Large'),
    ('X-Small'),
    ('X-Large'),
    ('XX-Small'),
    ('XX-Large');

-- Insert sample data into Condition table
INSERT INTO Condition (name)
VALUES 
    ('New'),
    ('Used'),
    ('Refurbished'),
    ('Like New'),
    ('Very Good'),
    ('Good'),
    ('Fair'),
    ('Poor'),
    ('Damaged'),
    ('For Parts');


-- Insert sample data into Tags table
INSERT INTO Tags (name)
VALUES
    ('New'),
    ('Top seller'),
    ('Pay 1 buy 2'),
    ('Special deal'),
    ('Lower price'),
    ('Limited Edition'),
    ('Clearance'),
    ('Best Value'),
    ('Exclusive'),
    ('Free Shipping'),
    ('Discounted'),
    ('Promotion'),
    ('Bundle Deal'),
    ('Seasonal Sale'),
    ('Final Sale'),
    ('Featured'),
    ('Gift Idea'),
    ('Must-Have'),
    ('Popular'),
    ('Trending'),
    ('Sold');


-- Insert sample data into Brands table
INSERT INTO Brands (name)
VALUES 
    ('Apple'),
    ('Samsung'),
    ('Nike'),
    ('Adidas'),
    ('Sony'),
    ('Toyota'),
    ('Honda'),
    ('Ford'),
    ('BMW'),
    ('Mercedes-Benz'),
    ('Coca-Cola'),
    ('Pepsi'),
    ('Louis Vuitton'),
    ('Gucci'),
    ('Rolex'),
    ('Canon'),
    ('Panasonic'),
    ('LG'),
    ('Microsoft'),
    ('Amazon'),
    ('Google');


-- Insert sample data into Models table
INSERT INTO Models (brand_id, name)
VALUES 
    -- Apple
    (1, 'iPhone 13'),
    (1, 'iPhone 13 Pro'),
    (1, 'iPhone 13 Mini'),
    (1, 'MacBook Air'),
    (1, 'MacBook Pro'),
    (1, 'iPad Pro'),
    (1, 'iPad Mini'),
    -- Samsung
    (2, 'Galaxy S21'),
    (2, 'Galaxy S21 Ultra'),
    (2, 'Galaxy Z Fold 3'),
    (2, 'Galaxy Watch 4'),
    (2, 'Galaxy Buds Pro'),
    (2, 'Galaxy Book Pro'),
    -- Nike
    (3, 'Air Force 1'),
    (3, 'Air Max 90'),
    (3, 'Air Jordan 1'),
    (3, 'React Element 55'),
    (3, 'Blazer Mid'),
    (3, 'Air Max 97'),
    -- Adidas
    (4, 'Ultraboost'),
    (4, 'Superstar'),
    (4, 'NMD_R1'),
    (4, 'Stan Smith'),
    (4, 'Yeezy Boost 350'),
    (4, 'Adilette'),
    -- Sony
    (5, 'PlayStation 5'),
    (5, 'BRAVIA OLED'),
    (5, 'WH-1000XM4'),
    -- Toyota
    (6, 'Camry'),
    (6, 'Corolla'),
    (6, 'RAV4'),
    -- Honda
    (7, 'Civic'),
    (7, 'Accord'),
    (7, 'CR-V'),
    -- Ford
    (8, 'Mustang'),
    (8, 'F-150'),
    (8, 'Escape'),
    -- BMW
    (9, '3 Series'),
    (9, '5 Series'),
    (9, 'X5'),
    -- Mercedes-Benz
    (10, 'C-Class'),
    (10, 'E-Class'),
    (10, 'GLC'),
    -- Coca-Cola
    (11, 'Coca-Cola Original'),
    (11, 'Diet Coke'),
    (11, 'Coca-Cola Zero'),
    -- Pepsi
    (12, 'Pepsi-Cola'),
    (12, 'Diet Pepsi'),
    (12, 'Pepsi Max'),
    -- Louis Vuitton
    (13, 'Speedy'),
    (13, 'Neverfull'),
    (13, 'Keepall'),
    -- Gucci
    (14, 'GG Marmont'),
    (14, 'Soho Disco'),
    (14, 'Ace'),
    -- Rolex
    (15, 'Submariner'),
    (15, 'Datejust'),
    (15, 'Daytona'),
    -- Canon
    (16, 'EOS 5D Mark IV'),
    (16, 'PowerShot G7 X Mark III'),
    (16, 'EOS R5'),
    -- Panasonic
    (17, 'LUMIX GH5'),
    (17, 'LUMIX S5'),
    (17, 'LUMIX G9'),
    -- LG
    (18, 'OLED C1'),
    (18, 'NanoCell 90'),
    (18, 'UltraGear Gaming Monitor'),
    -- Microsoft
    (19, 'Surface Pro 7'),
    (19, 'Xbox Series X'),
    (19, 'Surface Laptop 4'),
    -- Amazon
    (20, 'Kindle Paperwhite'),
    (20, 'Echo Dot'),
    (20, 'Fire TV Stick'),
    -- Google
    (21, 'Pixel 6'),
    (21, 'Nest Hub'),
    (21, 'Chromebook Pixel');

-- Insert sample data into Items table
INSERT INTO Items (title, description, images, price, tradable, priority, user_id, category_id, size_id, condition_id, model_id, created_at)
VALUES 
    ('iPhone 13', 'Brand new iPhone 13 with 128GB storage', '["../assets/img/default_item.svg"]', 999.99, 1, 1, 1, 1, 3, 1, 1, strftime('%s', 'now')),
    ('Galaxy S21 Ultra', 'Samsung Galaxy S21 Ultra in excellent condition', '["../assets/img/default_item.svg"]', 1099.99, 1, 1, 2, 1, 3, 1, 7, strftime('%s', 'now')),
    ('Nike Air Force 1', 'Classic Nike Air Force 1 shoes in white color', '["../assets/img/default_item.svg"]', 89.99, 1, 1, 3, 2, 1, 1, 9, strftime('%s', 'now')),
    ('Adidas Ultraboost', 'Adidas Ultraboost running shoes in black color', '["../assets/img/default_item.svg"]', 129.99, 1, 1, 4, 2, 1, 1, 13, strftime('%s', 'now')),
    ('MacBook Pro', 'Brand new MacBook Pro with M1 chip and 16GB RAM', '["../assets/img/default_item.svg"]', 1499.99, 1, 1, 1, 1, 3, 1, 6, strftime('%s', 'now')),
    ('Galaxy Tab S7+', 'Samsung Galaxy Tab S7+ with 12.4" display and 256GB storage', '["../assets/img/default_item.svg"]', 849.99, 1, 1, 2, 1, 3, 1, 17, strftime('%s', 'now')),
    ('Nike Air Max 90', 'Nike Air Max 90 sneakers in red and black color', '["../assets/img/default_item.svg"]', 119.99, 1, 1, 3, 2, 1, 1, 8, strftime('%s', 'now')),
    ('Adidas Superstar', 'Adidas Superstar sneakers in white and black color', '["../assets/img/default_item.svg"]', 79.99, 1, 1, 4, 2, 1, 1, 11, strftime('%s', 'now')),
    ('iPhone 13 Pro', 'iPhone 13 Pro with triple-camera system and 256GB storage', '["../assets/img/default_item.svg"]', 1199.99, 1, 1, 1, 1, 3, 1, 2, strftime('%s', 'now')),
    ('Galaxy Z Fold 3', 'Samsung Galaxy Z Fold 3 with foldable AMOLED display', '["../assets/img/default_item.svg"]', 1799.99, 1, 1, 2, 1, 3, 1, 7, strftime('%s', 'now')),
    ('Nike Air Jordan 1', 'Nike Air Jordan 1 Retro High OG sneakers in black toe colorway', '["../assets/img/default_item.svg"]', 169.99, 1, 1, 3, 2, 1, 1, 10, strftime('%s', 'now')),
    ('Adidas NMD_R1', 'Adidas NMD_R1 sneakers in grey and white color', '["../assets/img/default_item.svg"]', 139.99, 1, 1, 4, 2, 1, 1, 12, strftime('%s', 'now')),
    ('MacBook Air', 'Brand new MacBook Air with M1 chip and 8GB RAM', '["../assets/img/default_item.svg"]', 999.99, 1, 1, 1, 1, 3, 1, 5, strftime('%s', 'now')),
    ('Galaxy A52', 'Samsung Galaxy A52 with 6.5" Super AMOLED display', '["../assets/img/default_item.svg"]', 399.99, 1, 1, 2, 1, 3, 1, 16, strftime('%s', 'now')),
    ('Nike React Element 55', 'Nike React Element 55 sneakers in volt colorway', '["../assets/img/default_item.svg"]', 129.99, 1, 1, 3, 2, 1, 1, 11, strftime('%s', 'now')),
    ('Adidas Stan Smith', 'Adidas Stan Smith sneakers in green and white color', '["../assets/img/default_item.svg"]', 89.99, 1, 1, 4, 2, 1, 1, 14, strftime('%s', 'now')),
    ('iPad Pro', 'Brand new iPad Pro with M1 chip and Liquid Retina XDR display', '["../assets/img/default_item.svg"]', 1099.99, 1, 1, 1, 1, 3, 1, 7, strftime('%s', 'now')),
    ('Galaxy Buds Pro', 'Samsung Galaxy Buds Pro with active noise cancellation', '["../assets/img/default_item.svg"]', 199.99, 1, 1, 2, 1, 3, 1, 18, strftime('%s', 'now')),
    ('Nike Blazer Mid', "Nike Blazer Mid '77 Vintage sneakers in sail colorway", '["../assets/img/default_item.svg"]', 99.99, 1, 1, 3, 2, 1, 1, 8, strftime('%s', 'now')),
    ('Adidas Yeezy Boost 350', 'Adidas Yeezy Boost 350 V2 sneakers in triple white colorway', '["../assets/img/default_item.svg"]', 219.99, 1, 1, 4, 2, 1, 1, 15, strftime('%s', 'now'));


-- Insert sample data into ItemTags table
-- Insert tags for items into ItemTags table
INSERT INTO ItemTags (item_id, tag_id)
VALUES 
    -- iPhone 13
    (1, 1), -- New
    (1, 19), -- Popular
    (1, 3), -- Pay 1 buy 2
    -- Galaxy S21 Ultra
    (2, 2), -- Top seller
    (2, 18), -- Featured
    (2, 7), -- Best Value
    -- Nike Air Force 1
    (3, 5), -- Lower price
    (3, 10), -- Promotion
    (3, 14), -- Final Sale
    -- Adidas Ultraboost
    (4, 6), -- Limited Edition
    (4, 9), -- Exclusive
    (4, 13), -- Bundle Deal
    -- MacBook Pro
    (5, 11), -- Seasonal Sale
    (5, 15), -- Gift Idea
    (5, 17), -- Must-Have
    -- Galaxy Tab S7+
    (6, 4), -- Special deal
    (6, 8), -- Clearance
    (6, 12), -- Free Shipping
    -- Nike Air Max 90
    (7, 16), -- Discounted
    (7, 20), -- Trending
    (7, 2), -- Top seller
    -- Adidas Superstar
    (8, 3), -- Pay 1 buy 2
    (8, 9), -- Exclusive
    (8, 14), -- Final Sale
    -- iPhone 13 Pro
    (9, 4), -- Limited Edition
    (9, 8), -- Clearance
    (9, 12), -- Free Shipping
    -- Galaxy Z Fold 3
    (10, 10), -- Promotion
    (10, 13), -- Bundle Deal
    (10, 17), -- Must-Have
    -- Nike Air Jordan 1
    (11, 6), -- Limited Edition
    (11, 11), -- Seasonal Sale
    (11, 15), -- Gift Idea
    -- Adidas NMD_R1
    (12, 4), -- Special deal
    (12, 7), -- Best Value
    (12, 12), -- Free Shipping
    -- MacBook Air
    (13, 8), -- Clearance
    (13, 10), -- Promotion
    (13, 14), -- Final Sale
    -- Galaxy A52
    (14, 5), -- Lower price
    (14, 9), -- Exclusive
    (14, 20), -- Trending
    -- Nike React Element 55
    (15, 2), -- Top seller
    (15, 6), -- Limited Edition
    (15, 11), -- Seasonal Sale
    -- Adidas Stan Smith
    (16, 3), -- Pay 1 buy 2
    (16, 7), -- Best Value
    (16, 12), -- Free Shipping
    -- iPad Pro
    (17, 4), -- Special deal
    (17, 8), -- Clearance
    (17, 15), -- Gift Idea
    -- Galaxy Buds Pro
    (18, 1), -- New
    (18, 16), -- Discounted
    (18, 18), -- Featured
    -- Nike Blazer Mid
    (19, 2), -- Top seller
    (19, 5), -- Lower price
    (19, 10), -- Promotion
    -- Adidas Yeezy Boost 350
    (20, 3), -- Pay 1 buy 2
    (20, 11), -- Seasonal Sale
    (20, 17); -- Must-Have


-- Insert sample data into Wishlist table
INSERT INTO Wishlist (user_id, item_id) 
VALUES 
    (1, 3),  -- Nike Air Force 1
    (1, 7),  -- Nike Air Max 90
    (1, 11), -- Nike Air Jordan 1
    (1, 15), -- Nike React Element 55
    (1, 19); -- Nike Blazer Mid

-- Insert sample data into ShoppingCart table
INSERT INTO ShoppingCart (user_id, item_id) 
VALUES 
    (1, 4),  -- Adidas Ultraboost
    (1, 8),  -- Adidas Superstar
    (1, 12), -- Adidas NMD_R1
    (1, 16), -- Adidas Stan Smith
    (1, 20); -- Adidas Yeezy Boost 350

-- Insert sample data into Chat table
INSERT INTO Chats (item_id, from_user_id, to_user_id)
VALUES
    -- Message 1
    (1, 2, 1),
    -- Message 2
    (1, 3, 1);

-- Populate Messages table with sample messages
INSERT INTO Messages (text, chat_id, from_user_id, to_user_id, date_time)
VALUES 
    -- Message 1
    ("Hi", 1, 2, 1, strftime('%s', 'now')),
    -- Message 2
    ('Is this still available?', 2, 3, 1, strftime('%s', 'now')),
    -- Message 3
    ("Hi, I'm interested in buying this item.", 1, 2, 1, strftime('%s', 'now')),
    -- Message 4
    ("Ok, my brother.", 1, 1, 2, strftime('%s', 'now'));
