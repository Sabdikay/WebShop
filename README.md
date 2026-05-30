# Web Shop Practical Project

This repository contains a practical web technologies project developed during a university practical class. The goal of the project was to build a basic web shop step by step, starting from static HTML pages and gradually extending it with CSS styling, JavaScript functionality, PHP-based dynamic pages, JSON product data, and shopping cart features.

The project was mainly created for learning the fundamentals of web development and understanding how frontend and backend technologies work together in a simple e-commerce application.

## Project Overview

The web shop includes the basic structure of an online store, such as a homepage, product category pages, product detail pages, login and registration forms, a customer area, seller information, and a shopping cart.

The project was developed in multiple stages:

1. Planning the website structure and creating the first HTML pages
2. Adding a common CSS stylesheet for a consistent layout
3. Implementing JavaScript features for form validation and client-side interaction
4. Converting static pages into PHP pages for server-side processing
5. Storing and loading product information using JSON files
6. Extending the shop with shopping cart and order management functionality

## Features

- Homepage with navigation to product categories, customer area, and seller information
- Product category pages showing available products
- Product detail pages with product information such as name, image, description, and price
- Login, registration, logout, and customer profile pages
- Client-side form validation using JavaScript
- Visual feedback for valid and invalid form inputs
- Dark mode / style switching functionality
- Product collection list functionality
- Price calculation including tax
- PHP-based page generation
- JSON-based product data storage
- Dynamic product loading using URL parameters
- Shopping cart page with selected items, quantities, prices, and total cost
- Option to update or remove items from the shopping cart
- Basic order handling and order status management
- Customer and administrator views for managing orders
- Discount logic for selected orders
- Additional freestyle features added as part of the final project stage

## Technologies Used

- HTML
- CSS
- JavaScript
- PHP
- JSON
- XAMPP / Apache
- MariaDB or MySQL, depending on the project version

## Project Structure

The exact file structure may differ depending on the final submitted version, but the project generally follows a structure similar to this:

```text
project-folder/
│
├── index.php
├── about.php
├── login.php
├── registration.php
├── logout.php
├── customer.php
├── shoppingCart.php
├── product.php
├── categoryList.php
│
├── css/
│   └── mystyle.css
│
├── js/
│   └── script.js
│
├── data/
│   └── products.json
│
└── images/
    └── product images  
