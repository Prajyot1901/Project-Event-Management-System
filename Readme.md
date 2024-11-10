# Event Management System

This Event Management System is a comprehensive web application that facilitates the organization, management, and participation in events. The system enables admins to manage auditoriums and approve event requests, while users can request auditoriums for events and participants can join and provide feedback.

## Technologies Used

This project is developed using the following technologies:

- *Frontend*: HTML, CSS
- *Backend*: PHP
- *Database*: MySQL

The frontend (HTML and CSS) is used to create a user-friendly interface, while PHP handles backend operations and MySQL serves as the database for storing information.

## Project Features

1. *Admin Management*: Admins can register on the platform, manage auditoriums, and oversee event and participant requests.
2. *Auditorium Management*: Admins can add auditoriums to the platform, which can then be allocated to approved events.
3. *Event Management*: Users can register events, and upon admin approval, are assigned an auditorium if available.
4. *Participant Management*: Participants can register for events and are subject to approval by the admin.
5. *Feedback System*: Participants can leave feedback regarding the auditorium used for the event.

## Database Structure

The project consists of the following database tables:

- *Admin*: Stores admin information and login credentials.
- *Auditorium*: Stores details about auditoriums available for event allocation.
- *AdminAuditorium*: Manages the association between admins and their registered auditoriums.
- *Events*: Contains event details requested by users.
- *AdminEvent*: Manages event approvals and links events with admins.
- *Participant*: Stores participant information and registration details.
- *ParticipantEvent*: Manages participant event requests and approvals.
- *Feedback*: Stores feedback from participants about auditoriums used for events.

## Project Workflow

1. *Admin Registration*: An individual can register as an admin on the platform. The admin then has the option to register one or more auditoriums.
   
2. *Event Registration*: A user who wants to organize an event registers their event on the platform. This event request goes to the admin, who has the option to approve or reject the request. Upon approval, the admin can allocate an auditorium for the event.

3. *Participant Registration*: A participant who wishes to join an event can register as a participant. This request is sent to the admin for approval. The admin can either approve or disapprove the participant's registration for the event.

4. *Feedback Submission*: After participating in an event, participants can leave feedback regarding the auditorium, helping maintain quality standards.

## Installation and Setup

To set up this project on your local machine:

### Step 1: Clone the Repository

   bash
   git clone https://github.com/Prajyot1901/Project-Event-Management-System.git
  

### Step 2: Import the Database

1. Open *phpMyAdmin* and select your MySQL server.
2. Go to the *Import* tab.
3. Choose the EventManagementsystem.sql file located in the database folder of the cloned repository.
4. Click *Go* to import the database.

### Step 3: Configure Database Connection in PHP

1. Open the PHP files that require database connections (e.g., config.php or db_connection.php).
2. Update the database credentials as follows:

    php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "EventManagementsystem";
    

### Step 4: Start XAMPP Services

1. Open XAMPP and start *Apache* and *MySQL*.

### Step 5: Run the Project

1. Move the cloned project folder to XAMPP's htdocs directory.
2. Access the project at http://localhost/EventManagementSystem in your browser.

### Usage

- *Admin Access*: Log in as an admin to manage auditoriums, approve events, and handle participant requests.
- *User Access*: Users can request events, view available auditoriums, and manage their event status.
- *Participant Access*: Participants can register for events and leave feedback on auditoriums.

### Contribution

Contributions are welcome! If you’d like to make improvements or add features, please fork this repository and create a pull request.


This project is designed to streamline event management processes by providing a centralized platform for admins, users, and participants. We welcome any feedback and suggestions for future improvements.
