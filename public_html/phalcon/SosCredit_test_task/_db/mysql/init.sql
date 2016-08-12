DROP DATABASE IF EXISTS sos_credit_test_task;
CREATE DATABASE sos_credit_test_task;
USE sos_credit_test_task;

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    id INT(11) UNSIGNED NOT NULL auto_increment,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    text VARCHAR(200) NOT NULL,
    add_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

INSERT INTO messages(name, phone, email, text)
VALUES
 ('Ann', '+380441234567', 'ann@gmail.gmail.com', 'Hellow, world!'),
 ('Bill', '+380443334445', 'bil@gmail.gmail.com', 'Hi, world!');


