<?php
include("db_connection.php");
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
