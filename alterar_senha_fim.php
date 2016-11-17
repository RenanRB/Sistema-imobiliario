<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Sistema imobiliario</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="./css/bootstrap.css" rel="stylesheet">
        <link href="./css/estilo.css" rel="stylesheet">
        <script src="./js/jquery-1.11.1.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="card card-container">
                <form class="form-signin" action="validar_nova_senha.php" method="POST">
                    <span id="reauth-email" class="reauth-email"></span>
					<input type="hidden" name="codigo" value="<?php echo $_GET['codigo']; ?>" />
                    Senha <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required autofocus>
                    Confirmar senha<input type="password" id="senhare" name="senhare" class="form-control" placeholder="Digite novamente sua" required>
                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Alterar senha</button>
                </form>
            </div>
        </div>
    </body>
</html>
