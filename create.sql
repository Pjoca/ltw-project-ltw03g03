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
