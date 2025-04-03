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

