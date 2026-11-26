<?php

$ligacao = new mysqli(
    "localhost",
    "root",
    "",
    "stand_automovel"
);

if ($ligacao->connect_error) {
    die("Erro na ligação à base de dados.");
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $passwordConfirm = $_POST["password_confirm"];

    if ($nome == "" || $email == "" || $password == "") {

        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = "O email introduzido não é válido.";

    } elseif ($password !== $passwordConfirm) {

        $erro = "As passwords não coincidem.";

    } else {

        // Verificar se o email já existe
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $erro = "Já existe uma conta com esse email.";

        } else {

            // Nunca guardar a password diretamente
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "
                INSERT INTO users (name, email, password)
                VALUES (?, ?, ?)
            ";

            $stmt = $ligacao->prepare($sql);
            $stmt->bind_param(
                "sss",
                $nome,
                $email,
                $passwordHash
            );

            if ($stmt->execute()) {

                $sucesso = "Conta criada com sucesso.";

            } else {

                $erro = "Não foi possível criar a conta.";

            }
        }

        $stmt->close();
    }
}

$ligacao->close();

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registo - Stand Automóvel</title>
</head>

<body>

    <h1>Registar</h1>

    <?php if ($erro != ""): ?>

        <p>
            <?php echo htmlspecialchars($erro); ?>
        </p>

    <?php endif; ?>

    <?php if ($sucesso != ""): ?>

        <p>
            <?php echo htmlspecialchars($sucesso); ?>
        </p>

        <a href="login.php">Iniciar sessão</a>

    <?php else: ?>

        <form method="POST">

            <label for="name">Nome:</label>
            <br>

            <input
                type="text"
                id="name"
                name="name"
                required
            >

            <br><br>

            <label for="email">Email:</label>
            <br>

            <input
                type="email"
                id="email"
                name="email"
                required
            >

            <br><br>

            <label for="password">Password:</label>
            <br>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

            <br><br>

            <label for="password_confirm">
                Confirmar password:
            </label>
            <br>

            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                required
            >

            <br><br>

            <button type="submit">
                Criar conta
            </button>

        </form>

    <?php endif; ?>

    <br>

    <a href="index.php">Voltar</a>

</body>

</html>