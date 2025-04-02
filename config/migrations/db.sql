CREATE DATABASE microframework;

USE microframework;

CREATE TABLE users (
	id INT PRIMARY KEY AUTO_INCREMENT,
	NAME VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	PASSWORD VARCHAR(100) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  	updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );