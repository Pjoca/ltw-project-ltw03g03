-- Drop tables if they exist to avoid conflicts
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Reviews;

PRAGMA foreign_keys = ON;

CREATE TABLE Users (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    role TEXT CHECK(role IN ('client','freelancer', 'admin')) DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Services (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    price REAL NOT NULL,
    delivery_time INTEGER NOT NULL,
    media TEXT, -- images/videos
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE Categories (
    id INTEGER PRIMARY KEY,
    name TEXT UNIQUE NOT NULL
);

CREATE TABLE Transactions (
    id INTEGER PRIMARY KEY,
    client_id INTEGER NOT NULL,
    freelancer_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    status TEXT CHECK(status IN ('pending', 'completed', 'cancelled')) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);


CREATE TABLE Messages (
    id INTEGER PRIMARY KEY,
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE Reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NOT NULL,
    rating INTEGER CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
);


-- Insert users
INSERT INTO Users (id, name, username, password, email, role, created_at) VALUES
(1, 'Alice Johnson', 'alicej', '$2y$10$4YH06nSxwMPbkk753G526uKTI1s/k1X/O7g5D5.rQ0BmkcIu5Ftmm', 'alice@example.com', 'client', '2025-04-10 13:38:25'),
(2, 'Bob Smith', 'bobsmith', '$2y$10$TQfFdla5BbDS8cgnZ1tEl.E7jF8fNYP5fQPTLnKfgUDHmi28Cnk6S', 'bob@example.com', 'freelancer', '2025-04-10 13:38:25'),
(3, 'Charlie Brown', 'charlieadmin', '$2y$10$/Fo2gfwzgjMxJzbXzDWzn.tm4U6ciA5M3lPB5dqc2SqwxaWYPNI8i', 'charlie@example.com', 'admin', '2025-04-10 13:38:25'),
(4, 'Peter Parker', 'notSpiderman', '$2y$10$gIp1WYv6KKZ3hwGHbu0aL.XMNJFeGeAlHpvsQ6J7M3q5wWabDuzBW', 'ihatejjj@example.com', 'freelancer', '2025-04-10 13:38:25'),
(5, 'João Martins', 'joca', '$2y$10$c2a/M9ZKHSKDgniEbS2vguRYUhgmjZH7Yc6/UAjmIHnjhXprHhtOW', 'joao.martins@gmail.com', 'client', '2025-04-18 19:13:31'),
(6, 'Ricardo', 'richard', '$2y$10$dhlqsxsFe6Fnqo.E7i5OWOgmzihqF1mB1gcYduUKWmVSwXpgcyEoi', 'rica@gmail.com', 'freelancer', '2025-04-18 19:18:46'),
(7, 'Ana', 'ana', '$2y$10$FpF5MR/szHheZaAxLK/rcu0gqS1TrI/U5M0URo0ocobMnUTgbEW0q', 'ana@gmail.com', 'client', '2025-04-18 19:56:18'),
(8, 'Master Sushi', 'master_sushi', '$2y$10$G3kBe6HVHjcWfW2nXR64re.5.0I72EQyIs9M0URo0ocobMnUTgbEW0q', 'up1234@up.pt', 'freelancer', '2025-05-24 00:55:46'),
(9, 'John Cooper', 'desperados', '$2y$10$sf0kx2LoLvbY/ctZa6cU1.QCBciRlfMHNIiysX/RChouHmUYNMywG', 'desperados@gmail.com', 'client', '2025-05-24 13:59:20');

-- Insert categories
INSERT INTO Categories (name) VALUES ('Web Development'), ('Graphic Design'), ('Writing'), ('Marketing'), ('Photography');

-- Insert services
INSERT INTO Services (user_id, category_id, title, description, price, delivery_time, media, created_at) VALUES
(1, 1, 'Build a responsive website', 'I will create a fully responsive website that looks great on all devices.', 500.0, 7, '/uploads/img_6831ec8cde64b7.36561038.png', '2025-04-10 13:38:25'),
(2, 2, 'Logo Design', 'I will design a unique and memorable logo for your brand identity.', 100.0, 3, NULL, '2025-04-10 13:38:25'),
(4, 5, 'Spider Man Photo', 'Amazing photo of Spiderman saving the day! Perfect for any fan.', 3000.0, 3, '/uploads/img_6831de8a09bdd0.95064331.png', '2025-04-10 13:38:25'),
(1, 1, 'Custom-Built, Responsive Website for Your Business or Portfolio', 'Looking to establish a strong online presence? I offer professional website development tailored to your brand, audience, and goals. Whether you need a sleek personal portfolio, a business landing page, or a fully functional e-commerce site, I can bring your vision to life with clean, responsive, and SEO-friendly code.', 600.0, 3, '/uploads/img_6831ee4269e5c8.59954883.jpg', '2025-05-15 14:21:07'),
(1, 2, 'Eye-Catching Graphic Design for Brands, Social Media & More', 'Need stunning visuals that make your brand stand out? I offer professional graphic design services tailored to your needs—whether it''s for social media, branding, marketing materials, or custom illustrations.', 120.0, 3, '/uploads/img_6831ee160973e6.28198541.jpg', '2025-05-15 14:30:28'),
(2, 1, 'Website Maker', 'I can create your website in just 2 days! Fast and efficient service.', 402.2, 10, NULL, '2025-05-16 18:31:31'),
(5, 5, 'GOAT', 'ANTONY THE BEST PLAYER OF THE WORLD - a premium photo for collectors!', 2000.0, 6, '/uploads/img_682bb90b9ff5a1.90374640.png', '2025-05-19 22:44:45'),
(8, 3, 'Versatile Writer for Hire – Clear, Compelling, Content', 'Looking for clear, compelling, and captivating content? I’m a versatile Writer for Hire with a passion for crafting words that connect. Whether you need persuasive copy, engaging blog posts, SEO-optimized articles, product descriptions, or creative storytelling, I deliver high-quality writing tailored to your audience and goals. With a strong command of tone, voice, and narrative, I bring ideas to life — turning complex topics into accessible, impactful content. Fast turnaround, attention to detail, and open communication are at the heart of every project I take on. Let’s transform your vision into words that work. Reach out to get started!', 4000.0, 59, '/uploads/img_683119e6e10506.60352832.png', '2025-05-24 00:59:18'),
(9, 4, 'Digital Marketing That Gets Results – Grow Your Brand Today', 'Looking to boost your online presence, reach more customers, and increase conversions? I offer tailored freelance marketing services designed to meet your goals—whether you''re a small business, startup, or solo entrepreneur. What I Offer: Social media strategy & content (Instagram, Facebook, LinkedIn, etc.) Paid ad management (Meta Ads, Google Ads). Email marketing campaigns that engage and convert. Branding consultation & messaging alignment. SEO-optimized content to improve your search rankings. Why Work With Me? With experience in helping businesses across industries, I combine strategy with creativity to deliver real, trackable growth. I believe in results, not just reports. Let''s discuss how I can elevate your brand through smart, cost-effective marketing.', 200.0, 5, '/uploads/img_6831d6b7018508.79142343.jpeg', '2025-05-24 14:22:28');

-- Insert transactions
INSERT INTO Transactions (client_id, freelancer_id, service_id, status) VALUES
(2, 1, 1, 'pending'),
(1, 2, 2, 'completed');

-- Insert messages
INSERT INTO Messages (sender_id, receiver_id, message) VALUES
(1, 2, 'Hi, I would like to hire you for a website project.'),
(2, 1, 'Sure, let me know the details.');

-- Insert reviews
INSERT INTO Reviews (transaction_id, rating, comment) VALUES
(2, 5, 'Great logo design, highly recommended!');

