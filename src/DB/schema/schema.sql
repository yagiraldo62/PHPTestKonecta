DROP USER if EXISTS 'k'@'localhost';
DROP DATABASE if EXISTS  pruebakonecta;

CREATE DATABASE pruebakonecta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'k'@'localhost' identified by 'konecta';
GRANT ALL on pruebakonecta.* to 'k'@'localhost';

use pruebakonecta;

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    pass VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE categories (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    active INT NOT NULL Default 1,
    PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    reference VARCHAR(100) NOT NULL,
    price INT Not NULL,
    weight INT Not NULL,
    category INT Not NULL,
    stock INT Not NULL,
    created_at DATETIME Not NULL DEFAULT CURRENT_TIMESTAMP,
    last_sale_at DATETIME Default NULL,
    active INT NOT NULL Default 1,
    PRIMARY KEY (id),
    FOREIGN KEY (category)
        REFERENCES categories(id)
) ENGINE=INNODB;


CREATE TABLE product_sale (
    id INT NOT NULL AUTO_INCREMENT,
    product INT NOT NULL,
    quantity INT NOT NULL,
    price INT NOT NULL,
    created_at DATETIME Not NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (product)
        REFERENCES products(id)
) ENGINE=INNODB;