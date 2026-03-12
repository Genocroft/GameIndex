<?php
require_once '../templates/header.php';
if(isset($_POST['Server_Page'])) {
    header("location: server.php");
    exit();
}
?>

<div class="containerUpperBarRight">
    <div class="logo">Logo</div>
    <div class="title">Sonreta</div>    
    <div class="helpContainer">
        <div class="help">Help</div>
    </div>  
    <div class="containerUpperBarLeft">
        <div class="servers">
            <form method="post">
                <button type="Submit" name="Server_Page">Server page</button>
            </form>
        </div>
        <div class="account">Account</div>
        <div class="username">Username</div>
        <div class="icon">Icon</div>
    </div>
</div>

<?php
//require_once '../templates/footer.php';
?>