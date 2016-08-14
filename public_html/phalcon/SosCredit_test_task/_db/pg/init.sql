DROP TABLE IF EXISTS messages;

CREATE TABLE messages (
 id SERIAL PRIMARY KEY,
 name varchar(100) NOT NULL CHECK (name <> ''),
 phone varchar (20),
 email varchar(100) NOT NULL CHECK (email <> ''),
 text varchar(200) NOT NULL CHECK (text <>''),
 add_date date not null default CURRENT_DATE
);

INSERT INTO messages(name, phone, email, text)
VALUES
 ('Ann', '+380441234567', 'ann@gmail.gmail.com', 'Hellow, world!'),
 ('Bill', '+380443334445', 'bil@gmail.gmail.com', 'Hi, world!');

