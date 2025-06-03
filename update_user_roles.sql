-- Drop existing user table if exists
DROP TABLE IF EXISTS user;

-- Create updated user table with role
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin account
INSERT INTO user (email, password, name, role) VALUES
('admin@admin.com', 'admin123', 'Administrator', 'admin'); 