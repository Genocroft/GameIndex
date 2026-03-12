<?php
class GameController {
    private $pdo;

    public function __construct($host, $db, $user, $pass) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // CREATE
    public function createGame($title, $genre_id, $release_year, $platform_ids = []) {
        $stmt = $this->pdo->prepare("INSERT INTO games (title, genre_id, release_year) VALUES (?, ?, ?)");
        $stmt->execute([$title, $genre_id, $release_year]);
        $game_id = $this->pdo->lastInsertId();

        if (!empty($platform_ids)) {
            $stmtPlatform = $this->pdo->prepare("INSERT INTO game_platform (game_id, platform_id) VALUES (?, ?)");
            foreach ($platform_ids as $pid) {
                $stmtPlatform->execute([$game_id, $pid]);
            }
        }

        return $game_id;
    }

    // READ ALL
    public function getAllGames() {
        $stmt = $this->pdo->query("
            SELECT g.id, g.title, g.release_year, gen.name AS genre
            FROM games g
            LEFT JOIN genres gen ON g.genre_id = gen.id
        ");
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($games as &$game) {
            $stmtPlatforms = $this->pdo->prepare("
                SELECT p.name 
                FROM platforms p
                JOIN game_platform gp ON p.id = gp.platform_id
                WHERE gp.game_id = ?
            ");
            $stmtPlatforms->execute([$game['id']]);
            $game['platforms'] = $stmtPlatforms->fetchAll(PDO::FETCH_COLUMN);
        }

        return $games;
    }

    // READ SINGLE
    public function getGameById($id) {
        $stmt = $this->pdo->prepare("
            SELECT g.id, g.title, g.release_year, gen.name AS genre
            FROM games g
            LEFT JOIN genres gen ON g.genre_id = gen.id
            WHERE g.id = ?
        ");
        $stmt->execute([$id]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$game) return null;

        $stmtPlatforms = $this->pdo->prepare("
            SELECT p.name 
            FROM platforms p
            JOIN game_platform gp ON p.id = gp.platform_id
            WHERE gp.game_id = ?
        ");
        $stmtPlatforms->execute([$id]);
        $game['platforms'] = $stmtPlatforms->fetchAll(PDO::FETCH_COLUMN);

        return $game;
    }

    // UPDATE
    public function updateGame($id, $title, $genre_id, $release_year, $platform_ids = []) {
        $stmt = $this->pdo->prepare("UPDATE games SET title = ?, genre_id = ?, release_year = ? WHERE id = ?");
        $stmt->execute([$title, $genre_id, $release_year, $id]);

        // Platforms bijwerken
        $this->pdo->prepare("DELETE FROM game_platform WHERE game_id = ?")->execute([$id]);
        if (!empty($platform_ids)) {
            $stmtPlatform = $this->pdo->prepare("INSERT INTO game_platform (game_id, platform_id) VALUES (?, ?)");
            foreach ($platform_ids as $pid) {
                $stmtPlatform->execute([$id, $pid]);
            }
        }

        return true;
    }

    // DELETE
    public function deleteGame($id) {
        $this->pdo->prepare("DELETE FROM game_platform WHERE game_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM games WHERE id = ?")->execute([$id]);
        return true;
    }
}