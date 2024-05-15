# Project Requirements for Second-Hand Marketplace Website

## Objective
Develop a website that facilitates the buying and selling of pre-loved items. The platform aims to provide a seamless experience for users to list, browse, and transact easily, with robust search and filter capabilities, detailed item descriptions, and a user feedback system.

## Key Features
### General
- [X] Register a new account.
- [X] Log in and out.
- [X] Upload profile photo.
- [X] Edit their profile, including their name, username, password, and email.

### Sellers
- [ ] List new items, providing details such as category, brand, model, size, and condition, along with images. (Bruno)
- Track and manage their listed items. (Bruno)
  - [ ] History of items
  - [ ] Add Delete items from history list 
- [X] Respond to inquiries from buyers regarding their items and add further information if needed.
- [ ] Print shipping forms for items that have been sold. (Mansur)

### Buyers
- [X] Browse items using filters like category, price, and condition.
  - [X] Barra da pesquisa (para site inteiro)
  - [X] testar categoria fora de filtro
  - [X] ligar filtros
- [X] Engage with sellers to ask questions or negotiate prices.
- [X] Add items to a wishlist or shopping cart.
- [X] Proceed to checkout with their shopping cart (simulate payment process). (Mansur)

### Admins
- [X] Elevate a user to admin status.
- [X] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [X] Oversee and ensure the smooth operation of the entire system.
- [ ] Statistics for the number of transactions, new users, and new items. (Rubem)

## Technology Stack
- **HTML**: For structuring web content.
- **CSS**: Styling web pages, focusing on mobile-first, responsive design.
- **PHP**: Server-side scripting and database interactions.
- **JavaScript**: Client-side scripting for enhanced interactivity.
- **Ajax/JSON**: Asynchronous web page updates.
- **PDO/SQL**: Database interactions using SQLite.

## Additional Features
- **Rating and Review System**: A feedback mechanism for users to rate products and sellers.
- **Promotional Features**: Options for sellers to feature their products on the main page through paid promotions.
- [ ] **Analytics Dashboard**: A tool for sellers to track performance metrics and sales data. (Rubem)
- **Multi-Currency Support**: A system to handle transactions in various currencies.
- **Item Swapping**: A feature allowing users to exchange items instead of monetary transactions.
- [X] **API Integration**: Develop a robust API to allow third-party applications to interact with the platform. (Rubem)
- **Dynamic Promotions**: Implement a system for time-limited discounts, bundle deals (e.g., buy two, get one free), and quantity-based discounts (e.g., 10% off on purchases over a certain amount).
- **User Preferences**: Allow users to set and filter searches based on personal preferences such as size, condition, gender-specific items, etc.
- [ ] **Shipping Costs**: Calculate shipping costs depending on location (crude estimate). (Mansur)
- [X] **Real-Time Messaging System**: A secure and efficient communication channel for buyers and sellers.
- [x] **Check other users profile** (Rubem)
