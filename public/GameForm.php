<?php
require_once '../templates/header.php';
require_once '../src/config/db.connection.php';
require_once '../src/controllers/GameController.php';

$gameController = new GameController($pdo);

$id = $_GET['id'] ?? null;
$game = null;

// Als we een game bewerken
if ($id) {
    $game = $gameController->getGameById($id);
}

// FORM SUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titel = $_POST['titel'];
    $genre_id = $_POST['genre_id'];
    $release_datum = $_POST['release_datum'];
    $platform_ids = $_POST['platforms'] ?? [];

    if ($id) {
        $gameController->updateGame($id, $titel, $genre_id, $release_datum, $platform_ids);
    } else {
        $gameController->createGame($titel, $genre_id, $release_datum, $platform_ids);
    }

    header("Location: index.php");
    exit;
}

// genres ophalen
$genres = $pdo->query("SELECT * FROM genres")->fetchAll();

// platforms ophalen
$platforms = $pdo->query("SELECT * FROM platforms")->fetchAll();

?>

<h2><?= $id ? "Game Bewerken" : "Game Toevoegen" ?></h2>

<form method="POST">

    <label>Titel</label><br>
    <input type="text" name="titel"
        value="<?= $game['titel'] ?? '' ?>" required>
    <br><br>

    <label>Genre</label><br>
    <select name="genre_id">
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre['id'] ?>"
                <?= ($game['genre_id'] ?? '') == $genre['id'] ? 'selected' : '' ?>>
                <?= $genre['naam'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Release datum</label><br>
    <input type="date" name="release_datum"
        value="<?= $game['release_datum'] ?? '' ?>">
    <br><br>

    <label>Platforms</label><br>

    <?php
    $selectedPlatforms = [];

    if ($id) {
        $stmt = $pdo->prepare("SELECT platform_id FROM game_platform WHERE game_id = ?");
        $stmt->execute([$id]);
        $selectedPlatforms = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    ?>

    <?php foreach ($platforms as $platform): ?>
        <label>
            <input type="checkbox"
                   name="platforms[]"
                   value="<?= $platform['id'] ?>"
                   <?= in_array($platform['id'], $selectedPlatforms) ? 'checked' : '' ?>>
            <?= $platform['naam'] ?>
        </label><br>
    <?php endforeach; ?>

    <br>

    <button type="submit">
        <?= $id ? "Update Game" : "Create Game" ?>
    </button>

</form>

<?php require_once '../templates/footer.php'; ?>