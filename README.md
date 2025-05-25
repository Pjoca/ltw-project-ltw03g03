# ltw-project-ltw03g03

## Project made by:

    - Pedro Paixão
    - Matheus Liotti
    - Afonso Araújo

## Features

**User:**
- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Freelancers:**
- [x] List new services, providing details such as category, pricing, delivery time, and service description, along with images or videos.
- [x] Track and manage their offered services.
- [x] Respond to inquiries from clients regarding their services and provide custom offers if needed.
- [ ] Mark services as completed once delivered.

**Clients:**
- [x] Browse services using filters like category, price, and rating.
- [x] Engage with freelancers to ask questions or request custom orders.
- [x] Hire freelancers and proceed to checkout (simulate payment process).
- [x] Leave ratings and reviews for completed services.

**Admins:**
- [x] Elevate a user to admin status.
- [ ] Introduce new service categories and other pertinent entities.
- [x] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [x] **Efficient Service Display:** Services are loaded in pages to optimize performance and user experience, avoiding the burden of loading all available services at once.
- [x] **Search Result Pagination:** Implemented a clear pagination system for search results, allowing users to navigate through multiple pages of filtered services.
- [x] **Advanced Full-Text & Fuzzy Search:** Combines SQLite fts5 with intelligent prefix matching and typo-tolerant queries to provide fast and highly relevant search results across all service content and user profiles.

## Running

    sqlite3 database/database.db < database/database.sql
    sqlite3 database/database.db < database/fts.script.sql
    php -S localhost:9000

## Credentials

- **Basic Account**: alicej/1234
- **Admin**: charlieadmin/hashedpassword3