<?php
require_once '../templates/header.php';
if(isset($_POST['topBar_Page'])) {
    header("location: topbar.php");
    exit();
}
?>

<form method="post">
    <button type="Submit" name=topBar_Page>Go back</button>
</form>