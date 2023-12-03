<div class="header">
    <header>
        <h5>Administrátor: <?php echo $_SESSION["username"] ?></h5>
        <button>
            <?php
            if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
                echo "<a href='handlers/logout.php'>Odhlasiť</a>";
            } else {
                echo "<a href='admin.php'>Prihlasiť</a>";
            }
            ?>
        </button>
    </header>
</div>