<?php 
if (!isset($_POST['email'])) {
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: index.html");
		echo '<script>window.location = "index.html";</script>';
		break;
	}
}
?>