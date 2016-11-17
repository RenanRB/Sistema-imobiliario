<?php
header("Content-Type: text/html; charset=UTF-8",true);
$pdo = new PDO('mysql:host=localhost;dbname=sistema_imobiliario', 'root', '');
$pdo->exec("set names utf8");

$stmt = $pdo->prepare("select email from alterar_senha where link = :codigo");
$stmt->bindValue(':codigo', $_POST['codigo']);

if($stmt->execute()){
	if($stmt->rowCount() > 0){
		$result = $stmt->fetch(PDO::FETCH_OBJ);

		$stmt = $pdo->prepare("UPDATE usuarios set senha = ? WHERE email = ?");
		$stmt->bindParam(1, md5(sha1($_POST['senha'])));
		$stmt->bindParam(2, $result->email);
		$stmt->execute();
		
		echo '<script>location = "index.html";</script>';
		
	} else {
		echo "Usuário não encontrado ou não há uma solicitação de troca de senha";
	}
} else {
	echo "Ocorreu algum erro na execução";
}

?>
