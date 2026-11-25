<?php

require "src/conexao-bd.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$erro = '';

try {
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL
        )"
    );

    $emailInicial = getenv('ADMIN_EMAIL');
    $senhaInicial = getenv('ADMIN_PASSWORD');

    if (($emailInicial === false) !== ($senhaInicial === false)) {
        throw new RuntimeException('Defina ADMIN_EMAIL e ADMIN_PASSWORD juntos.');
    }

    if ($emailInicial !== false) {
        if ($emailInicial === '' || $senhaInicial === '') {
            throw new RuntimeException('ADMIN_EMAIL e ADMIN_PASSWORD nao podem ficar vazios.');
        }

        $consulta = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $consulta->execute([$emailInicial]);

        if (!$consulta->fetch()) {
            $inserir = $pdo->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
            $inserir->execute([$emailInicial, password_hash($senhaInicial, PASSWORD_DEFAULT)]);
        }
    }
} catch (PDOException $exception) {
    $erro = 'Nao foi possivel preparar o acesso no banco de dados.';
}

if (isset($_SESSION['usuario_id'])) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha o e-mail e a senha.';
    } elseif (!$erro) {
        $consulta = $pdo->prepare("SELECT id, email, senha FROM usuarios WHERE email = ?");
        $consulta->execute([$email]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        $senhaValida = $usuario && (
            password_verify($senha, $usuario['senha']) ||
            hash_equals($usuario['senha'], $senha)
        );

        if ($senhaValida) {
            if (password_needs_rehash($usuario['senha'], PASSWORD_DEFAULT) === true ||
                strncmp($usuario['senha'], '$', 1) !== 0) {
                $atualizar = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $atualizar->execute([password_hash($senha, PASSWORD_DEFAULT), $usuario['id']]);
            }

            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            header('Location: admin.php');
            exit;
        }

        $erro = 'E-mail ou senha incorretos.';
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/form.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="icon" href="img/icone-grao.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
  <title>Cafezin de cria - Login</title>
</head>
<body>
<main>
  <section class="container-admin-banner">
    <img src="img/logo-grao.png" class="logo-admin" alt="logo-grao">
    <h1>Login Cafezin</h1>
    <img class="ornaments" src="img/ornaments-coffee.png" alt="ornaments">
  </section>
  <section class="container-form">
    <form action="login.php" method="post">
      <?php if ($erro !== ''): ?>
        <p class="mensagem-login" role="alert"><?= htmlspecialchars($erro) ?></p>
      <?php endif; ?>

      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Digite o seu e-mail" required autocomplete="email">

      <label for="password">Senha</label>
      <input type="password" id="password" name="senha" placeholder="Digite a sua senha" required autocomplete="current-password">

      <input type="submit" class="botao-cadastrar" value="Entrar">
    </form>
  </section>
</main>
</body>
</html>
