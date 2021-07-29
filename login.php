<?php

//esse include é para conexão com seu banco de dados então precisa criar um arquivo e criar uma tabela no phpmyadmin	
include("conexao.php");

if(isset($_POST['email']) && strlen($_POST['email']) > 0 ){
	
	if(!isset($_SESSION))
		session_start();

	$_SESSION['email'] = $mysqli->escape_string($_POST['email']);
	$_SESSION['senha'] = md5(md5($_POST['senha']));

	$sql_code = "SELECT nome, senha, codigo, niveldeacesso FROM usuario WHERE email = '$_SESSION[email]'";
	$sql_query = $mysqli->query($sql_code) or die($mysqli->error);
	$dado = $sql_query->fetch_assoc();
	$total = $sql_query->num_rows;

	if($total == 0){
		$erro[] = "Este email nao pertence a nenhum usuario";
	}else{
		if($dado['senha'] == $_SESSION['senha']){
			$_SESSION['usuario'] = $dado['codigo'];
			$_SESSION['nome'] = $dado['nome'];
			$_SESSION['niveldeacesso'] = $dado['niveldeacesso'];
		}
		else{
			$erro[] = "Senha Incorreta";
		}
	}
//login redireciona para painel adm se for administrador você pode criar um arquivo administrador.php
	if(count($erro) == 0 || !isset($erro)){
		echo "<script> alert('Sucesso!'); location.href = 'somenteadm.php'; </script>"; 
	}

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<style>
    body {
      background-color: #a1c3f8;
    }
    form {
      background-color: #e7cfd5;
      width: 350px;
      height: 300px;
      box-sizing: border-box;
      padding: 50px 20px;
      text-align: center;
      display: block;
      margin: calc(50vh - 200px) auto;
      border-radius: 10px;
    }

    input {
      height: 20px;
      border-radius: 5px;
      border: 2px solid #a1c3f8;
    }

    h2 {
      font-family: sans-serif;
      color: #00235c;
    }

    a {
      text-decoration: none;
      color: #00235c;
    }

    input[type="submit"] {
      height: 30px;
      width: 70px;
      margin: auto;
      color: #ffffff;
      font-weight: bold;
      background-color: #61dfa4;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      transform: scale(1.2);
      transition: 0.5s;
    }

  </style>
</head>
<body>
	<?php if( count($erro) > 0){
			foreach ($erro as $msg) {
				echo "<p>$msg</p>";
			}
		}
	?>
	<form method="post" action="">
		<p><input value="<?php echo $_SESSION['email']; ?>" type="email" name="email" placeholder="Digite seu E-mail"></p>
		<p><input value="" type="password" name="senha" placeholder="Digite sua senha"></p>
//se tiver esquecido a senha vai ser redirecionado para o arquivo esqueceusuasenha.php
		<p><a href="esqueceuasenha.php">Esqueceu sua senha?</a></p>
		<p><input type="submit" value="Entrar"></p>
	</form>
</body>
</html>