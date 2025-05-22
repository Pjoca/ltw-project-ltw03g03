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
    service_id INTEGER,
    proposed_price REAL,
    delivery_days INTEGER,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE SET NULL
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
INSERT INTO Users (name, username, password, email, role) VALUES
('Alice Johnson', 'alicej', 'hashedpassword1', 'alice@example.com', 'client'),
('Bob Smith', 'bobsmith', 'hashedpassword2', 'bob@example.com', 'freelancer'),
('Charlie Brown', 'charlieadmin', 'hashedpassword3', 'charlie@example.com', 'admin'),
('Peter Parker', 'notSpiderman', 'ilovemj', 'ihatejjj@example.com', 'freelancer');

-- Insert categories
INSERT INTO Categories (name) VALUES ('Web Development'), ('Graphic Design'), ('Writing'), ('Marketing'), ('Photography');

-- Insert services
INSERT INTO Services (user_id, category_id, title, description, price, delivery_time) VALUES
(1, 1, 'Build a responsive website', 'I will create a fully responsive website.', 500.00, 7),
(2, 2, 'Logo Design', 'I will design a unique logo for your brand.', 100.00, 3),
(4, 5, 'Spider Man Photos', 'Amazing photo of Spiderman saving the day!', '3000', 3);

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

