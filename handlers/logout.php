<?php
require_once '_config_session.php';

unset($_SESSION["username"]);
unset($_SESSION["email"]);
header("Location: ../index.php");
die();
