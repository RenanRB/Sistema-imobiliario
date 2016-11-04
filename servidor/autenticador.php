<?php
if (!isset($_POST['email']) || (isset($_POST['acao']) && $_POST['acao'] != 'perfil')) {
	session_start();
	if(!isset($_SESSION['id'])) {
		header("Location: index.html");
		echo '<script>window.location = "index.html";</script>';
		break;
	}
}
?>
