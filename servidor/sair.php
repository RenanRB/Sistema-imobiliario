<?php
session_start();
session_destroy();
header("Location: ../index.html");
echo '<script>window.location = "../index.html";</script>';
break;

?>
