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
