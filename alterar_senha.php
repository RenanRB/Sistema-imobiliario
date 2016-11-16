<?php
	// http://www.thiengo.com.br
	// Por: Vinícius Thiengo
	// Em: 25/11/2013
	// Versão: 1.0
	// cliente.php
	include('./servidor/webservice/lib/nusoap.php');


	$cliente = new nusoap_client('http://localhost/sistema-imobiliario/servidor/webservice/servidor.php?wsdl');


	$parametros = array('email'=> $_GET['email']);


	$resultado = $cliente->call('alterarSenha', $parametros);

	echo ($resultado);

?>
