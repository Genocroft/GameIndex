-- Tabel: genres
CREATE TABLE IF NOT EXISTS genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(50) NOT NULL
);

-- Tabel: games
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(100) NOT NULL,
    ontwikkelaar VARCHAR(100),
    uitgever VARCHAR(100),
    release_datum DATE,
    genre_id INT,
    rating DECIMAL(3,1), -- bv. 8.5
    CONSTRAINT fk_genre FOREIGN KEY (genre_id) REFERENCES genres(id)
);

-- Tabel: platforms
CREATE TABLE IF NOT EXISTS platforms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(50) NOT NULL
);

-- Tabel: game_platform (veel-op-veel relatie)
CREATE TABLE IF NOT EXISTS game_platform (
    game_id INT,
    platform_id INT,
    PRIMARY KEY(game_id, platform_id),
    CONSTRAINT fk_game FOREIGN KEY (game_id) REFERENCES games(id),
    CONSTRAINT fk_platform FOREIGN KEY (platform_id) REFERENCES platforms(id)
);

-- Seed: genres
INSERT INTO genres (naam) VALUES
('Action'),
('Adventure'),
('RPG'),
('Simulation'),
('Strategy'),
('Sports');

-- Seed: platforms
INSERT INTO platforms (naam) VALUES
('PC'),
('PlayStation 5'),
('Xbox Series X'),
('Nintendo Switch');

-- Seed: games
INSERT INTO games (titel, ontwikkelaar, uitgever, release_datum, genre_id, rating) VALUES
('The Legend of Zelda: Breath of the Wild', 'Nintendo', 'Nintendo', '2017-03-03', 2, 9.8),
('Cyberpunk 2077', 'CD Projekt Red', 'CD Projekt', '2020-12-10', 3, 7.5),
('FIFA 23', 'EA Sports', 'EA Sports', '2022-09-27', 6, 8.0),
('Microsoft Flight Simulator', 'Asobo Studio', 'Xbox Game Studios', '2020-08-18', 4, 9.0);

-- Seed: game_platform
INSERT INTO game_platform (game_id, platform_id) VALUES
(1, 4), -- Zelda -> Nintendo Switch
(2, 1), -- Cyberpunk -> PC
(2, 2), -- Cyberpunk -> PlayStation 5
(2, 3), -- Cyberpunk -> Xbox Series X
(3, 1), -- FIFA 23 -> PC
(3, 2), -- FIFA 23 -> PlayStation 5
(3, 3), -- FIFA 23 -> Xbox Series X
(4, 1); -- Flight Simulator -> PC