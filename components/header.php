<div class="header">
    <header>
        <a href="home">
            <h1>Vitajte v eshope</h1>
        </a>
    </header>
    <div class="header-nav-container">
        <nav>
            <button class="profile">
                <a href="profile.php">
                    <?php echo $_SESSION["username"] ?>
                </a>
            </button>
            <button>
                <?php
                if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
                    echo "<a href='handlers/logout.php'>Odhlasiť</a>";
                } else {
                    echo "<a href='login.php'>Prihlasiť</a>";
                }
                ?>
            </button>
        </nav>
        <div class="s_cart">
            <?php if ($_SESSION["totalPieces"] > 0) {
                echo "<div class='cart-icon-bubble-pieces'>";
                echo $_SESSION["totalPieces"];
                echo "</div>";
            } ?>
            <img src="img/shopping-cart.png" class="shopping-cart-image">
        </div>
    </div>
</div>