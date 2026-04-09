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

// Favorite togglen
if (isset($_GET['fav'])) {
    $gameController->toggleFavorite($_GET['fav']);
    header("Location: index.php"); // refresh pagina
    exit;
}

$favorite = $_GET['favorite'] ?? '';
if ($favorite == '1') {
    $games = $gameController->getFavoriteGames();
} else {
    $games = $gameController->getAllGames();
}
?>

<h1>Game Index</h1>

<a href="GameForm.php">
    <button>Nieuwe Game Toevoegen</button>
</a>


<br><br>
<br><br>

<!-- 👇 HIER je filter formulier -->
<form method="get" style="margin-bottom:20px;">
    <select name="favorite" onchange="this.form.submit()">
        <option value="">Alle games</option>
        <option value="1" <?= ($favorite=='1')?'selected':'' ?>>
            Alleen favorieten ⭐
        </option>
    </select>
    <input 
   <input 
    type="text" 
    id="searchInput"
    placeholder="Zoek op titel..."
    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
>

</form>

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

    <tbody id="gameTable">
        <?php foreach ($games as $game): ?>
        <tr>
            <td><?= htmlspecialchars($game['id']) ?></td>
            <td><?= htmlspecialchars($game['titel']) ?></td>
            <td><?= htmlspecialchars($game['genre']) ?></td>
            <td><?= htmlspecialchars($game['release_datum']) ?></td>
            <td><?= htmlspecialchars(implode(', ', $game['platforms'])) ?></td>

            <td>
                <!-- Favorite ster -->
                <a href="index.php?fav=<?= $game['id'] ?>" style="text-decoration:none; font-size:20px;">
                    <?= $game['favorite'] ? '⭐' : '☆' ?>
                </a>

                |

                <a href="GameForm.php?id=<?= $game['id'] ?>">Edit</a> |

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
<script>
let timeout = null;

document.getElementById("searchInput").addEventListener("input", function() {
    let search = this.value;

    fetch("search.php?search=" + search)
        .then(response => response.text())
        .then(data => {
            document.getElementById("gameTable").innerHTML = data;
        });
});
</script>