-- Employee Management System Database Schema
-- This file creates the database, tables, and sample data.
-- Replace the password hash below with the output from PHP password_hash('Lucas@2003', PASSWORD_DEFAULT).

CREATE DATABASE IF NOT EXISTS employeemanagement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE employeemanagement;

-- Users table for admin login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Departments table
CREATE TABLE IF NOT EXISTS departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Employees table with department reference in 3NF
CREATE TABLE IF NOT EXISTS employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    department_id INT NOT NULL,
    phone VARBINARY(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    salary VARBINARY(255) NOT NULL,
    hire_date DATE NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(department_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Sample data
INSERT INTO departments (department_name) VALUES
('Human Resources'),
('Finance'),
('Sales'),
('IT Support');

-- Sample admin user: password is Lucas@2003 (hashed with password_hash)
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$VDrIYFggCoZtXjOxasJMD.1un7ggcCTp6FgDLxCK5.SAtxWl./0QW');
