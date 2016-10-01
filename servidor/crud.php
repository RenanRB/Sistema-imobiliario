<?php
if (isset($_GET['acao']) && $_GET['acao'] == 'editar') {
	$idUsuario = $_GET['id_usuario'];
	
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	
	$jsonfileUser = $jsonfile["usuarios"];
	$usuario = $jsonfileUser[$idUsuario];
	echo json_encode($usuario);
	
} elseif(isset($_GET['acao']) && $_GET['acao'] == 'listar') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	$usuarios = $jsonfile['usuarios'];
	$retorno = Array();
	
	foreach ( $usuarios as $chave => $e ) { 
		array_push($retorno, Array("chave"=>$chave, "nome"=>$e['nome']));
	}
	
	echo json_encode($retorno);
	
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	
	$usuarios = $jsonfile['usuarios'];
	$usuarios[$_POST['id']]['nome'] = $_POST['nome'];
	$usuarios[$_POST['id']]['email'] = $_POST['email'];
	$usuarios[$_POST['id']]['dt_nascimento'] = $_POST['dtNascimento'];
	$usuarios[$_POST['id']]['senha'] = $_POST['senha'];
	$response['usuarios'] = $usuarios;
	
	$fp = fopen('../dados/usuarios.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	
	$usuarios = $jsonfile['usuarios'];
	unset($usuarios[$_POST['id_usuario']]);
	$response['usuarios'] = $usuarios;
	
	$fp = fopen('../dados/usuarios.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	
	$usuarios = $jsonfile['usuarios'];
	array_push($usuarios, Array('nome' => $_POST['nome'],
								'email' => $_POST['email'],
								'dt_nascimento' => $_POST['dtNascimento'],
								'senha' => $_POST['senha']));
	$response['usuarios'] = $usuarios;
	
	$fp = fopen('../dados/usuarios.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);
}

?>