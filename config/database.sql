CREATE DATABASE IF NOT EXISTS smart_attendence;

USE smart_attendence;

CREATE TABLE departments (
    id INT INT_NOT_FOUND PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE user (
    id INT INT_NOT_FOUND PRIMARY KEY,
    department_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(255) NOT NULL
);

CREATE TABLE performance (
    id INT INT_NOT_FOUND PRIMARY KEY,
    user_id INT NOT NULL,
    total_days INT NOT NULL,
    late_count INT NOT NULL,
    absent_count INT NOT NULL,
    attendance_percent FLOAT NOT NULL,
    performance_score FLOAT NOT NULL,
    month VARCHAR(255) NOT NULL
);

CREATE TABLE leave_requests (
    id INT INT_NOT_FOUND PRIMARY KEY,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(255) NOT NULL
);
CREATE TABLE positions (
    id INT INT_NOT_FOUND PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
CREATE TABLE employee_profiles (
    id INT INT_NOT_FOUND PRIMARY KEY,
    user_id INT NOT NULL,
    position_id INT,
    department_id INT NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL
);
CREATE TABLE attendance (
    id INT INT_NOT_FOUND PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME NOT NULL,
    check_out TIME NOT NULL,
    status VARCHAR(255) NOT NULL
);
CREATE TABLE overtime (
    id INT INT_NOT_FOUND PRIMARY KEY,
    employee_id INT NOT NULL,
    ot_date DATE NOT NULL,
    hours DECIMAL NOT NULL
);
CREATE TABLE noti(

);