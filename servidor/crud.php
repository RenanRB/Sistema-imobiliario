<?php
header("Content-Type: text/html; charset=UTF-8",true);
//require_once("autenticador.php");
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=sistema_imobiliario', 'root', '');
$pdo->exec("set names utf8");

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
	$stmt = $pdo->prepare("select id from enderecos where cep = :cep");
	$stmt->bindValue(':cep', $_POST['cep']);
	$run = $stmt->execute();

	if ($stmt->rowCount() > 0) {
		$row = $stmt->fetch(PDO::FETCH_OBJ);
		$idEndereco = $row->id;
	} else {
		$stmt = $pdo->prepare("insert into enderecos (cep, estado, cidade, bairro, rua) values (?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $_POST['cep']);
		$stmt->bindParam(2, $_POST['estado']);
		$stmt->bindParam(3, $_POST['cidade']);
		$stmt->bindParam(4, $_POST['bairro']);
		$stmt->bindParam(5, $_POST['rua']);
		$run = $stmt->execute();

		$idEndereco = $pdo->lastInsertId();
	}

	$stmt = $pdo->prepare("INSERT INTO imoveis
		(nome, suites, quartos, area_privativa, area_total, vaga_garagem, numero, caracteristicas, informacoes_adicionais, id_endereco)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

	$stmt->bindParam(1, $_POST['nome']);
	$stmt->bindParam(2, $_POST['suites']);
	$stmt->bindParam(3, $_POST['quartos']);
	$stmt->bindParam(4, $_POST['area_privativa']);
	$stmt->bindParam(5, $_POST['area_total']);
	$stmt->bindParam(6, $_POST['vaga_garagem']);
	$stmt->bindParam(7, $_POST['numero']);
	$stmt->bindParam(8, $_POST['caracteristicas']);
	$stmt->bindParam(9, $_POST['informacoes_adicionais']);
	$stmt->bindParam(10, $idEndereco);

	$run = $stmt->execute();

} elseif (isset($_GET['acao']) && $_GET['acao'] == 'listarImoveis') {

	$stmt = $pdo->prepare("select id, nome, caracteristicas from imoveis where id_usuario = :id_usuario");
	$stmt->bindValue(':id_usuario', $_SESSION['id']);
	$stmt->execute();

	$htmlImoveis = '';

	while($row = $stmt->fetch(PDO::FETCH_OBJ)){
		$htmlImoveis .= '<div class="row box-imovel-busca col-md-12" id="imovel'.$row->id.'">
			<div class="informacoes-busca col-md-9 col-sm-9 col-xs-12">
				<center><div class="img-busca col-sm-3 col-xs-12">
					<img class="img-busca" src="./img/logo.jpg" />
				</div></center>
				<div class="info-imovel-busca col-sm-9 col-xs-12">
					<p>' . $row->nome . '</p>
					<p>' . $row->caracteristicas . '</p>
				</div>
			</div>
			<div class="informacoes-busca col-md-3 col-sm-3 col-xs-12">

				<button type="button" class="btn-busca" onclick="editarImovel(' . $row->id . ');">Editar</button>
				<button type="button" class="btn-busca" onclick="excluirImovel(' . $row->id . ');" data-toggle="modal" data-target="#excluir_imovel">Excluir</button>
			</div>
		</div>';
	}

	echo $htmlImoveis;
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluirImovel') {

	$stmt = $pdo->prepare("delete from imoveis where id = :id_imovel");
	$stmt->bindValue(':id_imovel', $_POST['id_imovel']);
	$run = $stmt->execute();

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'buscaEditarImovel') {

	$stmt = $pdo->prepare("select i.*, e.cep, e.estado, e.cidade, e.bairro, e.rua
												from imoveis as i left join enderecos as e on(i.id_endereco = e.id)
												where i.id = :id_imovel");
	$stmt->bindValue(':id_imovel', $_POST['id_imovel']);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($results);

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'editarImovel') {

	$stmt = $pdo->prepare("select * from enderecos where cep = :cep");
	$stmt->bindValue(':cep', $_POST['cep']);
	$run = $stmt->execute();

	if ($stmt->rowCount() > 0) {
		$row = $stmt->fetch(PDO::FETCH_OBJ);
		$idEndereco = $row->id;

		if ($row->estado != $_POST['estado'] || $row->cidade != $_POST['cidade'] || $row->bairro != $_POST['bairro'] || $row->rua != $_POST['rua']) {
			$stmt = $pdo->prepare("UPDATE enderecos set estado = ?, cidade = ?, bairro = ?, rua = ? WHERE id = ?");
			$stmt->bindParam(1, $_POST['estado']);
			$stmt->bindParam(2, $_POST['cidade']);
			$stmt->bindParam(3, $_POST['bairro']);
			$stmt->bindParam(4, $_POST['rua']);
			$stmt->bindParam(5, $idEndereco);
			$run = $stmt->execute();
		}
	} else {
		$stmt = $pdo->prepare("insert into enderecos (cep, estado, cidade, bairro, rua) values (?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $_POST['cep']);
		$stmt->bindParam(2, $_POST['estado']);
		$stmt->bindParam(3, $_POST['cidade']);
		$stmt->bindParam(4, $_POST['bairro']);
		$stmt->bindParam(5, $_POST['rua']);
		$run = $stmt->execute();

		$idEndereco = $pdo->lastInsertId();
	}

	$stmt = $pdo->prepare("UPDATE imoveis set nome = ?, suites = ?, quartos = ?, area_privativa = ?,
		 										area_total = ?, vaga_garagem = ?, numero = ?, caracteristicas = ?,
												informacoes_adicionais = ?, id_endereco = ? WHERE id = ?");
	$stmt->bindParam(1, $_POST['nome']);
	$stmt->bindParam(2, $_POST['suites']);
	$stmt->bindParam(3, $_POST['quartos']);
	$stmt->bindParam(4, $_POST['area_privativa']);
	$stmt->bindParam(5, $_POST['area_total']);
	$stmt->bindParam(6, $_POST['vaga_garagem']);
	$stmt->bindParam(7, $_POST['numero']);
	$stmt->bindParam(8, $_POST['caracteristicas']);
	$stmt->bindParam(9, $_POST['informacoes_adicionais']);
	$stmt->bindParam(10, $idEndereco);
	$stmt->bindParam(11, $_POST['id_imovel']);
	$run = $stmt->execute();

} elseif (isset($_POST['acao']) && $_POST['acao'] == 'perfil') {

	$dados = Array();
	if (isset($_SESSION['nome']) && isset($_SESSION['email'])) {
		array_push($dados, $_SESSION['nome'], $_SESSION['email']);
	}
	echo json_encode($dados);

}
?>
