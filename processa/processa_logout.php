<?php
require_once '../includes/funcoes.php';

session_unset();
session_destroy();

redirecionar('../index.php', 'sucesso', 'Você foi desconectado(a) com sucesso.');
?>