<?php
class GameController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // CREATE
    public function createGame($titel, $genre_id, $release_datum, $platform_ids = []) {
        $stmt = $this->pdo->prepare("INSERT INTO games (titel, genre_id, release_datum) VALUES (?, ?, ?)");
        $stmt->execute([$titel, $genre_id, $release_datum]);
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
            SELECT g.id, g.titel, g.release_datum, g.favorite, gen.naam AS genre
            FROM games g
            LEFT JOIN genres gen ON g.genre_id = gen.id
        ");
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($games as &$game) {
            $stmtPlatforms = $this->pdo->prepare("
                SELECT p.naam 
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
            SELECT g.id, g.titel, g.release_datum, g.favorite, gen.naam AS genre
            FROM games g
            LEFT JOIN genres gen ON g.genre_id = gen.id
            WHERE g.id = ?
        ");
        $stmt->execute([$id]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$game) return null;

        $stmtPlatforms = $this->pdo->prepare("
            SELECT p.naam 
            FROM platforms p
            JOIN game_platform gp ON p.id = gp.platform_id
            WHERE gp.game_id = ?
        ");
        $stmtPlatforms->execute([$id]);
        $game['platforms'] = $stmtPlatforms->fetchAll(PDO::FETCH_COLUMN);

        return $game;
    }

    // UPDATE
    public function updateGame($id, $titel, $genre_id, $release_datum, $platform_ids = []) {
        $stmt = $this->pdo->prepare("UPDATE games SET titel = ?, genre_id = ?, release_datum = ? WHERE id = ?");
        $stmt->execute([$titel, $genre_id, $release_datum, $id]);

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

    // FAVORITE TOGGLE
    public function toggleFavorite($id) {
        $stmt = $this->pdo->prepare("SELECT favorite FROM games WHERE id = ?");
        $stmt->execute([$id]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        $newFav = $game['favorite'] ? 0 : 1;

        $stmt = $this->pdo->prepare("UPDATE games SET favorite = ? WHERE id = ?");
        $stmt->execute([$newFav, $id]);
    }
}