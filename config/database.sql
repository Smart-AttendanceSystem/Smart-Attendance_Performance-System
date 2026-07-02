CREATE DATABASE IF NOT EXISTS smart_attendence;

USE smart_attendence;

CREATE TABLE departments (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE user (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL,
    -- avatar VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(255) NOT NULL
);

CREATE TABLE notification_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    leave_request_alerts BOOLEAN DEFAULT TRUE,
    attendance_alerts BOOLEAN DEFAULT TRUE,
    performance_alerts BOOLEAN DEFAULT TRUE,
    password_reset_alerts BOOLEAN DEFAULT TRUE,
    email_notifications BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE performance (
   id INT  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_days INT NOT NULL,
    late_count INT NOT NULL,
    absent_count INT NOT NULL,
    attendance_percent FLOAT NOT NULL,
    performance_score FLOAT NOT NULL,
    month VARCHAR(255) NOT NULL
);

CREATE TABLE leave_requests (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(255) NOT NULL
);
CREATE TABLE positions (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
CREATE TABLE employee_profiles (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    position_id INT,
    department_id INT NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL
);
CREATE TABLE attendance (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME NOT NULL,
    check_out TIME NOT NULL,
    status VARCHAR(255) NOT NULL
);
CREATE TABLE overtime (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    ot_date DATE NOT NULL,
    hours DECIMAL NOT NULL
);
CREATE TABLE notifications (
    id INT  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    reference_id INT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES `user`(id)
);