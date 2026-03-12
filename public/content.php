<?php
require_once '../templates/header.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'Stats';

?>

<h1><?php echo ucfirst($page); ?></h1>

<div>
    <a href="content.php?page=crafting">Crafting</a>
    <a href="content.php?page=inventory">Inventory</a>
    <a href="content.php?page=objectives">Objectives</a>
    <a href="content.php?page=stats">Stats</a>
    <a href="content.php?page=map">Map</a>
</div>

<div>
    <a href="topbar.php">Go back</a>
</div>