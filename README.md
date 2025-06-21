# Car-Rental-App
This is a Car Rental Web Application using Laravel-12 and Inertia Vue-3. This Web Application allows users to browse available cars, select a car, and book it for a specified rental period. The system ensures that cars are available for the chosen dates before confirming the booking. It includes role-based access control, where administrators can manage cars and rentals, while customers can view their bookings, rental history, cancel bookings if needed, and update profile information.
--------------------------------------------------------------------------------------------------------
 

## This project will have two main interfaces:

1. Admin Dashboard - for managing cars, rentals, and customers.
2. User Interface - for users to browse available cars, make bookings, and view rental history.

 

## Requirements:

#### Part 1: Admin Dashboard 
The Admin should be able to perform the following tasks:

1. Manage Cars: Add, edit, and delete car details. Each car should have the following properties: Car Name, Brand, Model, Year of Manufacture, Car Type (SUV, Sedan, etc.), Daily Rent Price, Availability Status (Available/Not Available), Car Image

2. Manage Rentals: View and manage (CRUD) all car rentals, including: Rental ID, Customer Name, Car Details (Name, Brand), Rental Start Date and End Date, Total Cost, Rental Status (Ongoing, Completed, Canceled)

3. Manage Customers: View and manage (CRUD) customer details: Customer Name, Email, Phone Number, Address, Rental History

4. Dashboard Overview: Show important statistics like: Total number of cars, Number of available cars, Total number of rentals, Total earnings from rentals



#### Part 2: User Interface
Users should be able to:

1. Browse Cars: View available cars with filters such as car type, brand, and daily rent price.

2. Make a Booking: Select a car, choose the rental start and end date, and book the car. The system should Ensure that the selected car is available for the chosen period.
 
3. Manage Bookings: After logging in as a customer, users should be able to: View their current and past bookings, Cancel a booking (only if the rental has not started yet).

4. User Authentication: 
 - · Implement a basic authentication system for users.
 - · Allow users to sign up, log in, and log out.
 - · Use middleware to protect routes (e.g., only logged-in users can book cars or view their booking history).

 

#### Part 3: Database Design
Database Design: Design the database schema for the car rental system, including tables for:
1. users Table (admin, customers): <br>
id (BIGINT) <br>
name (STRING) <br>
email (STRING) <br>
password (STRING) <br>
role (STRING) [admin/customer] <br>
created_at (TIMESTAMP) <br>
updated_at (TIMESTAMP) <br>


2. Cars Table:
id (BIGINT)
name (STRING)
brand (STRING)
model (STRING)
year (INTEGER)
car_type (STRING)
daily_rent_price (DECIMAL)
availability (BOOLEAN)
image (STRING)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)


3. Rentals Table:
id (BIGINT)
user_id (BIGINT)
car_id (BIGINT)
start_date (DATE)
end_date (DATE)
total_cost (DECIMAL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)


#### Part 4: Controllers

1. Admin Controllers:
CarController (Admin/CarController.php)
RentalController (Admin/RentalController.php)
CustomerController (Admin/CustomerController.php)

2. Frontend Controllers:
PageController (Frontend/PageController.php)
CarController (Frontend/CarController.php)
RentalController (Frontend/RentalController.php)
CustomerController (Frontend/CustomerController.php)


#### Part 4: Models

1. User (User.php):
isAdmin(): A method to check if the user is an admin.
isCustomer(): A method to check if the user is a customer.
rentals(): Defines a hasMany relationship with the Rental model, indicating that a user can have multiple rentals.

2. Car (Car.php):
rentals(): Defines a hasMany relationship with the Rental model, indicating that a car can have multiple rentals.

3. Rental (Rental.php):
car(): Defines a belongsTo relationship with the Car model, indicating that a rental is associated with one car.
user(): Defines a belongsTo relationship with the User model, indicating that a rental is associated with one user.


### Email System: 
When a car is rented, a detail of that rental should be sent to the customer's email and also sent an email to the admin that a car is rented by which customer.