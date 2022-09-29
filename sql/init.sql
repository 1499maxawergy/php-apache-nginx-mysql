CREATE DATABASE IF NOT EXISTS appDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED WITH mysql_native_password BY 'toor';
GRANT SELECT,UPDATE,INSERT ON appDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE appDB;
CREATE TABLE IF NOT EXISTS users (
    ID INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL,
    password VARCHAR(256) NOT NULL,
    email VARCHAR(64) NOT NULL,
    ticket_id INT(11) NULL,
    PRIMARY KEY (ID)
);

INSERT INTO users (username, password, email) VALUES ("Maxawergy", "$1$LgKNOvfK$zk5dmsUyzksmrPFjlxGNs1", "maxawergy@yandex.ru");