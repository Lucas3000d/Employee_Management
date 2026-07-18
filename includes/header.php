<?php
if (!isset($_SESSION['user_id'])) {
    return;
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>dashboard.php">Employee Management</a>
        <div class="ms-auto">
            <a class="btn btn-outline-light btn-sm" href="<?php echo BASE_URL; ?>logout.php">Logout</a>
        </div>
    </div>
</nav>
