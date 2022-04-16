<?php
session_start();
session_destroy();
header("Location: ../HTML/login.html");
?>