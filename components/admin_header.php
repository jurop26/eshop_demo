<div class="header">
    <header>
        <h5>Administrátor: <?php echo $_SESSION["admin_username"] ?></h5>
        <button>
            <?php
            if (isset($_SESSION["admin_username"]) && !empty($_SESSION["admin_username"])) {
                echo "<a href='handlers/admin_logout.php'>Odhlasiť</a>";
            } else {
                echo "<a href='admin.php'>Prihlasiť</a>";
            }
            ?>
        </button>
    </header>
</div>