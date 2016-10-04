<?php
require_once("autenticador.php");

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
} elseif (isset($_POST['acao']) && $_POST['acao'] == 'login') {
	$getfile = file_get_contents('../dados/usuarios.json');
	$jsonfile = json_decode($getfile, true);
	$usuarios = $jsonfile['usuarios'];
	
	foreach ( $usuarios as $chave => $e ) {
		if ($e['email'] == $_POST['email']) {
			if ($e['senha'] == $_POST['senha']) {
				session_start();
				$_SESSION['id'] = $chave;
				$_SESSION['nome'] = $e['nome'];
				$_SESSION['email'] = $e['email'];
				echo "ok";
			} else {
				echo "senhaInvalida";
			}
		}
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
	$usuario = $usuarios[$_SESSION['id']];
	
	if (!isset($usuario['imoveis'])) {
		$usuario['imoveis'] = Array();
	}
	
	array_push($usuario['imoveis'], Array('nome' => $_POST['nome'],
								'suites' => $_POST['suites'],
								'quartos' => $_POST['quartos'],
								'area_privativa' => $_POST['area_privativa'],
								'area_total' => $_POST['area_total'],
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
}

?>