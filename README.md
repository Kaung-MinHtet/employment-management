# Project Name

Project for senior web developer coding test

---

## Table of Contents
- [Project Overview](#project-overview)
- [Versions](#versions)
- [Project Setup](#project-setup)
- [Postman file location](#postman-file)

---

## Project Overview

This project is a web application developed using **Laravel** for the backend and **React** (with **Inertia.js**) for the frontend. It allows users to manage companies and employees with features like user authentication, company and employee CRUD operations, and more.

---

## Versions

- **Laravel Version**: 11.31
- **PHP Version**: ^8.2
- **React Version**: ^18.2.0
- **Node.js Version**: 20.17.0
- **Inertia.js Version**: latest
- **Database**: SQLite

---

## Project Setup

### Prerequisites

Ensure that you have the following software installed:
- **PHP** (>= 8.2)
- **Composer** (for managing PHP dependencies)
- **Node.js** (>= 16.x) and **npm** or **Yarn**
- **MySQL** (or any other database that you plan to use)
- **Docker** (optional, if you want to run the project inside containers)

### Steps

#### 1. Clone the repository

```bash
git clone https://github.com/Kaung-MinHtet/employment-management.git
cd employment-management
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev


##### Open new terminal tab
```bash
php artisan serve

---
## Postman file location

Postman collection file is exists in the root project folder