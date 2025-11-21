DROP DATABASE IF EXISTS testws;
CREATE DATABASE testws;
\c testws;

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100)
);

insert into users (name, email) values 
('Salohy', 'salohy@mail.com'),
('Nathalie', 'nathalie@mail.com'),
('Nomena', 'nomena@mail.com');