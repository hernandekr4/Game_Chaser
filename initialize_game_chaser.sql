-- Use or create the game_chaser database
CREATE DATABASE IF NOT EXISTS game_chaser;
USE game_chaser;

-- Drop existing tables to ensure a clean setup
DROP TABLE IF EXISTS videogame_platform;
DROP TABLE IF EXISTS platform;
DROP TABLE IF EXISTS videogame_genre;
DROP TABLE IF EXISTS videogame;
DROP TABLE IF EXISTS genre;
DROP TABLE IF EXISTS awards;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(50) NOT NULL UNIQUE,
    UserPassword VARCHAR(255) NOT NULL,
    UserRole ENUM('admin', 'user') DEFAULT 'user'
);

-- Create genre table
CREATE TABLE genre (
    GenreID INT AUTO_INCREMENT PRIMARY KEY,
    GenreName VARCHAR(100) NOT NULL UNIQUE
);

-- Create platform table
CREATE TABLE platform (
    PlatformID INT AUTO_INCREMENT PRIMARY KEY,
    PlatformName VARCHAR(100) NOT NULL UNIQUE
);

-- Create videogame table
CREATE TABLE videogame (
    GameID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    NumberofPlayers INT NOT NULL,
    ImageURL VARCHAR(255)
);

-- Create videogame_genre table
CREATE TABLE videogame_genre (
    GameID INT NOT NULL,
    GenreID INT NOT NULL,
    PRIMARY KEY (GameID, GenreID),
    FOREIGN KEY (GameID) REFERENCES videogame(GameID),
    FOREIGN KEY (GenreID) REFERENCES genre(GenreID)
);

-- Create videogame_platform table
CREATE TABLE videogame_platform (
    GameID INT NOT NULL,
    PlatformID INT NOT NULL,
    PRIMARY KEY (GameID, PlatformID),
    FOREIGN KEY (GameID) REFERENCES videogame(GameID),
    FOREIGN KEY (PlatformID) REFERENCES platform(PlatformID)
);

-- Create awards table
CREATE TABLE awards (
    AwardID INT AUTO_INCREMENT PRIMARY KEY,
    AwardName VARCHAR(255) NOT NULL,
    Issuer VARCHAR(255) NOT NULL
);

-- Populate genre table
INSERT INTO genre (GenreName) VALUES
('Action'), ('Adventure'), ('RPG'), ('Sports'), ('Strategy'),
('Shooter'), ('Puzzle'), ('Horror'), ('Simulation');

-- Populate platform table
INSERT INTO platform (PlatformName) VALUES
('PC'), ('PlayStation 5'), ('Xbox Series X'), ('Nintendo Switch'), ('Mobile');

-- Populate videogame table
INSERT INTO videogame (Name, NumberofPlayers, ImageURL) VALUES
('Halo Infinite', 4, 'game_chaser/images/halo_infinite.jpg'),
('The Legend of Zelda: Breath of the Wild', 1, 'game_chaser/images/zelda.jpg'),
('Minecraft', 8, 'game_chaser/images/minecraft.jpg');

-- Populate videogame_genre table
INSERT INTO videogame_genre (GameID, GenreID) VALUES
(1, 1), -- Halo Infinite: Action
(2, 2), -- Zelda BOTW: Adventure
(3, 5); -- Minecraft: Strategy

-- Populate videogame_platform table
INSERT INTO videogame_platform (GameID, PlatformID) VALUES
(1, 1), -- Halo Infinite on PC
(1, 2), -- Halo Infinite on PlayStation 5
(2, 4), -- Zelda BOTW on Nintendo Switch
(3, 1), -- Minecraft on PC
(3, 5); -- Minecraft on Mobile

-- Populate users table
INSERT INTO users (UserName, UserPassword, UserRole) VALUES
('admin', PASSWORD('adminpass'), 'admin'),
('user1', PASSWORD('userpass'), 'user');

-- Populate awards table
INSERT INTO awards (AwardName, Issuer) VALUES
('Game of the Year', 'The Game Awards'),
('Best Indie Game', 'IGN');
