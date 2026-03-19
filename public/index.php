<?php
require_once '../templates/header.php';
require_once '../src/config/db.connection.php';
require_once '../src/controllers/GameController.php';

$gameController = new GameController($pdo);

// Delete actie
if (isset($_GET['delete'])) {
    $gameController->deleteGame($_GET['delete']);
    header("Location: index.php");
    exit;
}

$games = $gameController->getAllGames();
?>

<h1>Game Index</h1>

<a href="GameForm.php">
    <button>Nieuwe Game Toevoegen</button>
</a>

<br><br>

<?php if (!empty($games)): ?>
<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Genre</th>
            <th>Release Datum</th>
            <th>Platforms</th>
            <th>Acties</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($games as $game): ?>
        <tr>
            <td><?= htmlspecialchars($game['id']) ?></td>
            <td><?= htmlspecialchars($game['titel']) ?></td>
            <td><?= htmlspecialchars($game['genre']) ?></td>
            <td><?= htmlspecialchars($game['release_datum']) ?></td>
            <td><?= htmlspecialchars(implode(', ', $game['platforms'])) ?></td>

            <td>
                <a href="GameForm.php?id=<?= $game['id'] ?>">
                    Edit
                </a>

                |

                <a href="index.php?delete=<?= $game['id'] ?>"
                   onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?')">
                    Delete
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>
<p>Geen games gevonden in de database.</p>
<?php endif; ?>

<?php
require_once '../templates/footer.php';
?>