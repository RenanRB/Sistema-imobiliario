<?php

require_once("autenticador.php");

$pdo = new PDO('mysql:host=localhost;dbname=sistema_imobiliario', 'root', '');


if (isset($_GET['acao']) && $_GET['acao'] == 'editar') {

	$stmt = $pdo->prepare("select id, email, nome, dt_nascimento from usuarios where id = :id");
	$stmt->bindValue(':id', $_GET['id_usuario']);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
	$results['dt_nascimento'] = implode("/", array_reverse(explode("-", $results['dt_nascimento'])));
	echo json_encode($results);

} elseif(isset($_GET['acao']) && $_GET['acao'] == 'listar') {
	$stmt = $pdo->prepare("select id, email, nome from usuarios");
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
	if ($_POST['senha'] != ''){
		$stmt = $pdo->prepare("UPDATE usuarios set nome = ?, email = ?, dt_nascimento = ?, senha = ? WHERE id = ?");
		$stmt->bindParam(1, $_POST['nome']);
		$stmt->bindParam(2, $_POST['email']);
		$stmt->bindParam(3, implode("-", array_reverse(explode("/", $_POST['dtNascimento']))));
		$stmt->bindParam(4, md5(sha1($_POST['senha'])));
		$stmt->bindParam(5, $_POST['id']);
	} else {
		$stmt = $pdo->prepare("UPDATE usuarios set nome = ?, email = ?, dt_nascimento = ? WHERE id = ?");
		$stmt->bindParam(1, $_POST['nome']);
		$stmt->bindParam(2, $_POST['email']);
		$stmt->bindParam(3, implode("-", array_reverse(explode("/", $_POST['dtNascimento']))));
		$stmt->bindParam(4, $_POST['id']);
	}

	$stmt->execute();

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {

	$stmt = $pdo->prepare("delete from usuarios where id = :id");
	$stmt->bindValue(':id', $_POST['id_usuario']);
	$stmt->execute();

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {

	$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, dt_nascimento, senha, ativo, nivel) VALUES (?, ?, ?, ?, 1, 0)");
	$stmt->bindParam(1, $_POST['nome']);
	$stmt->bindParam(2, $_POST['email']);
	$stmt->bindParam(3, implode("-", array_reverse(explode("/", $_POST['dtNascimento']))));
	$stmt->bindParam(4, md5(sha1($_POST['senha'])));
	$stmt->execute();

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'login') {

	$stmt = $pdo->prepare("select id, email, nome from usuarios where email = :login and senha = :senha");
	$stmt->bindValue(':login', $_POST['email']);
	$stmt->bindValue(':senha', md5(sha1($_POST['senha'])));
	if($stmt->execute()){
		if($stmt->rowCount() > 0){
			$result = $stmt->fetch(PDO::FETCH_OBJ);

			session_start();

			$_SESSION['id'] = $result->id;
			$_SESSION['nome'] = $result->nome;
			$_SESSION['email'] = $result->email;
			echo "ok";
		} else {
			echo "senhaInvalida";
		}
	} else {
		echo "probConexao";
	}

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'cadastrarImovel') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);

	$usuarios = $jsonfile['usuarios'];
	$usuario = $usuarios[$_SESSION['id']];

	if (!isset($usuario['imoveis'])) {
		$usuario['imoveis'] = Array();
	}

	array_push($usuario['imoveis'], Array('nome' => $_POST['nome'],
								'suites' => $_POST['suites'],
								'quartos' => $_POST['quartos'],
								'area_privativa' => $_POST['area_privativa'],
								'area_total' => $_POST['area_total'],
								'vaga_garagem' => $_POST['vaga_garagem'],
								'cep' => $_POST['cep'],
								'estado' => $_POST['estado'],
								'cidade' => $_POST['cidade'],
								'bairro' => $_POST['bairro'],
								'rua' => $_POST['rua'],
								'numero' => $_POST['numero'],
								'caracteristicas' => $_POST['caracteristicas'],
								'informacoes_adicionais' => $_POST['informacoes_adicionais']));

	$usuarios[$_SESSION['id']] = $usuario;
	$response['usuarios'] = $usuarios;

	$fp = fopen('../dados/usuarios.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);
} elseif (isset($_GET['acao']) && $_GET['acao'] == 'listarImoveis') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	$usuarios = $jsonfile['usuarios'];

	$htmlImoveis = '';

	foreach ( $usuarios as $chave => $e ) {
		if(isset($e['imoveis'])) {
			foreach ($e['imoveis'] as $chaveImovel => $ee) {
				$htmlImoveis .= '<div class="row box-imovel-busca col-md-12" id="imovel'.$chaveImovel.$chave.'">
					<div class="informacoes-busca col-md-9 col-sm-9 col-xs-12">
						<center><div class="img-busca col-sm-3 col-xs-12">
							<img class="img-busca" src="./img/logo.jpg" />
						</div></center>
						<div class="info-imovel-busca col-sm-9 col-xs-12">
							<p>' . $ee["nome"] . '</p>
							<p>' . $ee["caracteristicas"] . '</p>
						</div>
					</div>
					<div class="informacoes-busca col-md-3 col-sm-3 col-xs-12">

						<button type="button" class="btn-busca" onclick="editarImovel(' . $chaveImovel . ', ' . $chave . ');">Editar</button>
						<button type="button" class="btn-busca" onclick="excluirImovel(' . $chaveImovel . ', ' . $chave . ');" data-toggle="modal" data-target="#excluir_imovel">Excluir</button>
					</div>
				</div>';
			}
		}
	}

	echo $htmlImoveis;
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluirImovel') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);

	$usuarios = $jsonfile['usuarios'];
	unset($usuarios[$_POST['id_usuario']]['imoveis'][$_POST['id_imovel']]);
	$response['usuarios'] = $usuarios;

	$fp = fopen('../dados/usuarios.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'buscaEditarImovel') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);

	$usuarios = $jsonfile['usuarios'];
	echo json_encode($usuarios[$_POST['id_usuario']]['imoveis'][$_POST['id_imovel']]);
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'editarImovel') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);

	$usuarios = $jsonfile['usuarios'];

	$usuarios[$_POST['id_usuario']]['imoveis'][$_POST['id_imovel']] = Array(
								'nome' => $_POST['nome'],
								'suites' => $_POST['suites'],
								'quartos' => $_POST['quartos'],
								'area_privativa' => $_POST['area_privativa'],
								'area_total' => $_POST['area_total'],
								'vaga_garagem' => $_POST['vaga_garagem'],
								'cep' => $_POST['cep'],
								'estado' => $_POST['estado'],
								'cidade' => $_POST['cidade'],
								'bairro' => $_POST['bairro'],
								'rua' => $_POST['rua'],
								'numero' => $_POST['numero'],
								'caracteristicas' => $_POST['caracteristicas'],
								'informacoes_adicionais' => $_POST['informacoes_adicionais']);

	$response['usuarios'] = $usuarios;

	$fp = fopen('../dados/usuarios.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);

}
?>
