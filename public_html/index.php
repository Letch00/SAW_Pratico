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

$sql = "SELECT * FROM vehicles";
$resultado = $ligacao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stand Automóvel</title>
</head>

<body>

    <h1>Stand Automóvel</h1>

    <h2>Veículos</h2>

    <?php

    if ($resultado->num_rows > 0) {

        while ($veiculo = $resultado->fetch_assoc()) {
    ?>

            <div>

                <h3>
                    <?php
                    echo htmlspecialchars($veiculo["brand"]);
                    ?>
                    <?php
                    echo htmlspecialchars($veiculo["model"]);
                    ?>
                </h3>

                <p>
                    Ano:
                    <?php
                    echo htmlspecialchars($veiculo["year"]);
                    ?>
                </p>

                <p>
                    Combustível:
                    <?php
                    echo htmlspecialchars($veiculo["fuel"]);
                    ?>
                </p>

                <p>
                    Quilómetros:
                    <?php
                    echo htmlspecialchars($veiculo["km"]);
                    ?>
                </p>

            </div>

    <?php
        }

    } else {

        echo "<p>Não existem veículos registados.</p>";

    }

    $ligacao->close();

    ?>

</body>

</html>