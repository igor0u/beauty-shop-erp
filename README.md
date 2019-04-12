# Beauty Shop ERP

## Overview

Beauty Shop ERP is an open source php based enterprise management system designed for beauty salons (hair salons, SPA, etc.).

### Features

* Clients directory
* Staff directory
* Service catalog 
* Client orders management in a timeline-view

### Requirements

1. HTTP web server Ex. Nginx, Apache, IIS.
2. PHP 7.1+   
3. MariaDB 10.1.38 (this version uses in development of ERP)

## Installation

1. Copy this repo into a public accessible folder on your server.
2. Create an empty database.
3. Run the SQL statements in the app/_install folder.
4. Edit the app/models/Database.php, change these lines
````php
    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'pass';
    private $dbname = 'db';
````
to your database credentials.
4. Edit the index.php, remove or comment these lines
````php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
````
to your database credentials.

## Usage

### Schedule (Visits)

To add new visit to the schedule you need at least one added employee and one added service.
To add new visit select the date in the header of timeline and click on the cross of employee column and time row.
Fill the modal form:
* for new clients fill top part of the form, 'name' is only required, new entry in clients directory will be created
* for existing clients start typing name, surname, patronymic or phone number in any field of top part of the form
* select the appropriate client from the list that appears below the field
start typing name of service and select the appropriate service  from the list that appears below the field, this is required
* you can change employee in the same way as a service select
* set the quantity of service
* if service measurement unit is 'procedure' You also can change service duration or end time
* for add another service to visit click 'Add Service' and fill the appeared row
* Click 'Add Visit'

To manage visit click on it in timeline.

### Services

* To add new services you need to create at least added one service category - click 'Add Category'.
* If You already have needed category, click 'Add Service' - all fields in opened form are required.
* To manage service category double click on this category in right bar.
* To manage service click on the row with this service in the table.

### Staff

* To add new employee you need to create at least added one position - go to 'Positions' tab and click 'Add Position'.
* If You already have needed position, in the 'Staff' tab click 'Add Employee' - 'Name' and 'Position' are required.
* To manage position click on the row with this position in the table in 'Positions' tab.
* To manage employee click on the row with this employee in the table in 'Staff' tab.

### Clients

* To add new client click 'Add Client' - 'Name' are required.
* To manage client click on the row with his name in the table.

## Todos

### Security

* validating and filtering input data
* add links in the database
* add authorization

### Features

* add staff workshift management
* add salary calculating
* add more clients data (personal discount, notes)

### Code smell

* refactor js-functions and some database requests according to DRY