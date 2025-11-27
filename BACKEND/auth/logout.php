<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../FRONTEND/admin/login_admin.html");
exit;
?>
