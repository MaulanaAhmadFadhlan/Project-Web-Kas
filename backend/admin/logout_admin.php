<?php
session_start();
session_destroy();
header("Location: ../../frontend/admin/login.html");
exit;
?>