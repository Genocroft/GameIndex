<?php
require_once '../src/config/db.connection.php';
require_once '../src/controllers/GameController.php';

$gameController = new GameController($pdo);

$search = $_GET['search'] ?? '';

if ($search) {
    $games = array_filter(
        $gameController->getAllGames(),
        function ($game) use ($search) {
            return stripos($game['titel'], $search) !== false;
        }
    );
} else {
    $games = $gameController->getAllGames();
}

foreach ($games as $game) {
    echo "<tr>";
    echo "<td>{$game['id']}</td>";
    echo "<td>{$game['titel']}</td>";
    echo "<td>{$game['genre']}</td>";
    echo "<td>{$game['release_datum']}</td>";
    echo "<td>" . implode(', ', $game['platforms']) . "</td>";
    echo "<td>
        <a href='index.php?fav={$game['id']}'>
            " . ($game['favorite'] ? '⭐' : '☆') . "
        </a>
    </td>";
    echo "</tr>";
}