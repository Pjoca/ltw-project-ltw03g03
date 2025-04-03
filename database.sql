CREATE TABLE UserType (
    id SERIAL PRIMARY KEY,
    type_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE Users (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_type_id INT NOT NULL,
    FOREIGN KEY (user_type_id) REFERENCES UserType(id) ON DELETE CASCADE
);

CREATE TABLE Service (
    id SERIAL PRIMARY KEY,
    val DECIMAL(10,2) NOT NULL,
    img VARCHAR(255),
    category VARCHAR(100) NOT NULL,
    tempo INT NOT NULL, -- Time in minutes or hours
    descript TEXT,
    stat VARCHAR(50) CHECK (stat IN ('active', 'inactive', 'pending')) NOT NULL
);

CREATE TABLE Reviews (
    id SERIAL PRIMARY KEY,
    rating INT CHECK (rating BETWEEN 1 AND 5) NOT NULL,
    msg TEXT,
    sender_id INT NOT NULL,
    reader_id INT NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (reader_id) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE Messages (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    tempo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE Checkout (
    id SERIAL PRIMARY KEY,
    service_id INT NOT NULL,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    stat VARCHAR(50) CHECK (stat IN ('pending', 'completed', 'canceled')) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES Service(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);



-- Populating UserType
INSERT INTO UserType (type_name) VALUES ('Freelancer'), ('Client'), ('Admin');

-- Populating Users
INSERT INTO Users (nome, pass, email, user_type_id) VALUES
('Alice Smith', 'password123', 'alice@example.com', 1),
('Bob Johnson', 'securepass', 'bob@example.com', 2),
('Charlie Admin', 'adminpass', 'charlie@example.com', 3);

-- Populating Service
INSERT INTO Service (val, img, category, tempo, descript, stat) VALUES
(50.00, 'service1.jpg', 'Design', 120, 'Graphic design service', 'active'),
(30.00, 'service2.jpg', 'Writing', 90, 'Content writing service', 'active'),
(100.00, 'service3.jpg', 'Programming', 180, 'Full-stack development', 'pending');

-- Populating Reviews
INSERT INTO Reviews (rating, msg, sender_id, reader_id) VALUES
(5, 'Great service!', 2, 1),
(4, 'Good job, but can improve.', 1, 2);

-- Populating Messages
INSERT INTO Messages (content, sender_id, receiver_id) VALUES
('Hello, I am interested in your service.', 2, 1),
('Thanks for your interest! Let’s discuss details.', 1, 2);

-- Populating Checkout
INSERT INTO Checkout (service_id, user_id, total_amount, stat, payment_method) VALUES
(1, 2, 50.00, 'completed', 'Credit Card'),
(2, 1, 30.00, 'pending', 'PayPal');

