<?php

session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email == "" || $password == "") {

        $erro = "Preencha todos os campos.";

    } else {

        $sql = "
            SELECT id, name, email, password, role
            FROM users
            WHERE email = ?
        ";

        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {

            $utilizador = $resultado->fetch_assoc();

            if (password_verify(
                $password,
                $utilizador["password"]
            )) {

                $_SESSION["user_id"] = $utilizador["id"];
                $_SESSION["name"] = $utilizador["name"];
                $_SESSION["role"] = $utilizador["role"];

                if ($utilizador["role"] == "admin") {

                    header("Location: admin/index.php");

                } else {

                    header("Location: user/profile.php");

                }

                exit;

            } else {

                $erro = "Email ou password incorretos.";

            }

        } else {

            $erro = "Email ou password incorretos.";

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Stand Automóvel</title>

</head>

<body>

    <h1>Iniciar sessão</h1>

    <?php if ($erro != ""): ?>

        <p>
            <?php echo htmlspecialchars($erro); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <label for="email">
            Email:
        </label>

        <br>

        <input
            type="email"
            id="email"
            name="email"
            required
        >

        <br><br>

        <label for="password">
            Password:
        </label>

        <br>

        <input
            type="password"
            id="password"
            name="password"
            required
        >

        <br><br>

        <button type="submit">
            Entrar
        </button>

    </form>

    <br>

    <a href="register.php">
        Criar uma conta
    </a>

    <br>

    <a href="index.php">
        Voltar
    </a>

</body>

</html>