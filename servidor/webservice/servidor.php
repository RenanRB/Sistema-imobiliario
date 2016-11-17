<?php
	header("Content-Type: text/html; charset=UTF-8",true);
	include('lib/nusoap.php');


	$servidor = new nusoap_server();


	$servidor->configureWSDL('urn:Servidor');
	$servidor->wsdl->schemaTargetNamespace = 'urn:Servidor';

	function alterarSenha($email){
		$pdo = new PDO('mysql:host=localhost;dbname=sistema_imobiliario', 'root', '');
		$pdo->exec("set names utf8");
		
		$stmt = $pdo->prepare("select id from usuarios where email = :email");
		$stmt->bindValue(':email', $email);
		if($stmt->execute()){
			if($stmt->rowCount() > 0){
				$stmt = $pdo->prepare("INSERT INTO alterar_senha (email, link, data) VALUES (?, ?, CURDATE())");
				$stmt->bindParam(1, $email);
				$stmt->bindParam(2, md5($email.date('Y-m-d H:i:s')));
				$stmt->execute();
				
				 // emails para quem será enviado o formulário
				 /*  $emailenviar = "site@site.com";
				  $destino = $email;
				  $assunto = "Alteração de senha";

				  // É necessário indicar que o formato do e-mail é html
				  $headers  = 'MIME-Version: 1.0' . "\r\n";
					  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					  $headers .= 'From: Sistema imobiliario <$email>';
				  //$headers .= "Bcc: $EmailPadrao\r\n";
				  
				  $enviaremail = mail($destino, $assunto, $arquivo, $headers);
				  if($enviaremail){
					$mgm = "Para alterar sua senha clique neste link: http://localhost/Sistema-imobiliario/alterar_senha_fim.php?codigo=".md5($email.date('Y-m-d H:i:s'));
				  } else {
					return("Erro ao enviar email");
				  }*/
			  
				
				return("Verifique em seu e-mail o passo a passo para alteração de sua senha");
			}
			return("E-mail não encontrado");
		} 
		return("Erro na execução");
	}


	$servidor->register(
		'alterarSenha',
		array('nome'=>'xsd:string'),
		array('retorno'=>'xsd:string'),
		'urn:Servidor.exemplo',
		'urn:Servidor.exmeplo',
		'rpc',
		'encoded',
		'Apenas um exemplo utilizando o NuSOAP PHP.'
	);


	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$servidor->service($HTTP_RAW_POST_DATA);
?>
