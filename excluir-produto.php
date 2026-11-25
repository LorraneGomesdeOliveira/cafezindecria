<?php
    require "src/autenticacao.php";
    exigirLogin();
require "src/conexao-bd.php";
require "src/Modelo/produtos.php";  
require "src/Repositorio/produtoRepositorio.php";

$produtoRepositorio = new produtoRepositorio($pdo);
$produtoRepositorio->deletar($_POST['id']);

header("Location: admin.php");
    
