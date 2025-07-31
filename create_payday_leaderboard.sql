-- SQL script to create database and table for Payday Leaderboard
CREATE DATABASE payday_game;

USE payday_game;

CREATE TABLE leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(50) NOT NULL,
    score INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);