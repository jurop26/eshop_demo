<?php

require_once '_config_session.php';

unset($_SESSION["admin_username"]);
unset($_SESSION["admin_email"]);
header("Location: ../admin.php");
die();
