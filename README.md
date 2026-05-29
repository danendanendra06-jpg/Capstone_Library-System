# Library Management System - Capstone Project

## Description
A modern full-stack library management system featuring a CodeIgniter 4 Admin dashboard, a Spring Boot RESTful API, and a Flutter mobile application for members.

## Tech Stack
- **Web Admin**: CodeIgniter 4 (PHP 8+), Bootstrap 5
- **Backend API**: Spring Boot (Java 21), Spring Security, JWT
- **Mobile App**: Flutter (Clean Architecture)
- **Database**: MySQL

## Folder Structure
- `admin-ci4/`: CodeIgniter 4 web application.
- `api-spring/`: Spring Boot REST API.
- `mobile_flutter/`: Flutter mobile application.

## Installation & Setup

### Database Setup
1. Ensure MySQL is running.
2. Create a database named `library_db`.
3. In the `admin-ci4` directory, configure your `.env` with database credentials.
4. Run migrations:
   ```bash
   cd admin-ci4
   php spark migrate
   ```

### 1. CodeIgniter 4 Admin
1. Navigate to `admin-ci4`.
2. Start the development server:
   ```bash
   php spark serve
   ```
3. Access the dashboard at `http://localhost:8080/`.

### 2. Spring Boot API
1. Navigate to `api-spring`.
2. Run the application:
   ```bash
   ./mvnw spring-boot:run
   ```
3. The API will run on `http://localhost:8081`.

### 3. Flutter Mobile App
1. Navigate to `mobile_flutter`.
2. Ensure you have an emulator running or device connected.
3. Run the app:
   ```bash
   flutter run
   ```

## Default Accounts
- **Admin**: Email: `admin@library.com` | Password: `password123`
- **Member**: Register via the Flutter app or CI4 Admin.

## Important Notes for Demo
- The CI4 dashboard relies on session authentication.
- The Flutter app communicates with the Spring Boot API using JWT tokens.
- Ensure all three services (MySQL, CI4, Spring Boot) are running before testing the Flutter app.
