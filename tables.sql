CREATE SCHEMA `bus_reservation` ;

USE bus_reservation;

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(45) UNIQUE NOT NULL,
    email VARCHAR(45) UNIQUE NOT NULL,
    username VARCHAR(45) UNIQUE NOT NULL,
    phoneNo VARCHAR(45) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  
    address VARCHAR(100) UNIQUE NOT NULL
    
);

CREATE TABLE reservation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(45) NOT NULL,
    busId VARCHAR(45) NOT NULL,
    seat_number VARCHAR(45) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    price_per_seat DECIMAL(10, 2) NOT NULL,
    book_date  VARCHAR(100) NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    payment_status VARCHAR(45) NOT NULL,
    booking_status VARCHAR(45) NOT NULL,
    FOREIGN KEY (busId) REFERENCES buses(busId)
);
ALTER TABLE reservation
ADD UNIQUE INDEX (booking_id);



  CREATE TABLE buses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    busId VARCHAR(100) NOT NULL,
    bus_name VARCHAR(100) NOT NULL,
    price_per_seat VARCHAR(100) NOT NULL,
    no_seats VARCHAR(100) NOT NULL,
    bus_image VARCHAR(100) NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL   
);



 
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(45) NOT NULL,
    amount VARCHAR(45) NOT NULL,
    payment_method VARCHAR(45) NOT NULL,
    payment_status VARCHAR(45) NOT NULL,
    payment_date VARCHAR(45) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES reservation(booking_id)
);


ALTER TABLE `bus_reservation`.`reservation` 
ADD COLUMN `username` VARCHAR(45) NOT NULL AFTER `booking_status`;

ALTER TABLE `bus_reservation`.`reservation` 
ADD COLUMN `username` VARCHAR(45) NOT NULL AFTER `booking_status`;
