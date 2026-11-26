
<?php

require_once __DIR__ . '/../config/database.php';

// Consultar todos os veículos
try {

    $stmt = $pdo->query("
        SELECT id, brand, model, year, fuel, image
        FROM vehicles
        ORDER BY id DESC
    ");

    $vehicles = $stmt->fetchAll();

} catch (PDOException $e) {

    error_log($e->getMessage());

    $vehicles = [];
    $error = "Não foi possível carregar os veículos.";

}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stand Automóvel</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header>

        <h1>Stand Automóvel</h1>

        <nav>
            <a href="index.php">Início</a>
            <a href="login.php">Iniciar Sessão</a>
            <a href="register.php">Registar</a>
        </nav>

    </header>

    <main>

        <section class="welcome">

            <h2>Encontra o teu próximo automóvel</h2>

            <p>
                Consulta os veículos disponíveis no nosso stand.
            </p>

        </section>

        <section class="vehicles">

            <h2>Os nossos veículos</h2>

            <?php if (isset($error)): ?>

                <p><?= htmlspecialchars($error) ?></p>

            <?php elseif (empty($vehicles)): ?>

                <p>Não existem veículos registados.</p>

            <?php else: ?>

                <div class="vehicle-list">

                    <?php foreach ($vehicles as $vehicle): ?>

                        <article class="vehicle-card">

                            <?php if (!empty($vehicle['image'])): ?>

                                <img
                                    src="../uploads/vehicles/<?= htmlspecialchars(basename($vehicle['image'])) ?>"
                                    alt="<?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']) ?>"
                                >

                            <?php endif; ?>

                            <h3>
                                <?= htmlspecialchars($vehicle['brand']) ?>
                                <?= htmlspecialchars($vehicle['model']) ?>
                            </h3>

                            <p>
                                Ano: <?= htmlspecialchars((string) $vehicle['year']) ?>
                            </p>

                            <p>
                                Combustível: <?= htmlspecialchars($vehicle['fuel']) ?>
                            </p>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </main>

    <footer>

        <p>&copy; <?= date('Y') ?> Stand Automóvel</p>

    </footer>

</body>
</html>
