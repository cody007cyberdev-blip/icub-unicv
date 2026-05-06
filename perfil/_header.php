<?php
// para obter as informacoes do perfil do candidato

$perfil = Database::prepare('SELECT * FROM tbl_candidato WHERE id = ?', [$_SESSION['usuario']['id']])->fetch();
$dataAtual = new DateTime('now');
$dataFormatada = $dataAtual->format('Y-m-d H:i:s');

$dataEntrada = new DateTime($perfil['data_entrada']);
$dataEntradaFormatada = $dataEntrada->format('Y-m-d');

if ($dataEntradaFormatada !== $dataAtual->format('Y-m-d')) {
    Database::execute("UPDATE tbl_candidato SET data_entrada = ? WHERE id = ?", [$dataFormatada, $perfil['id']]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php includeTemplate('meta') ?>
    <title>Perfil | ICUB</title>
    <link href="<?= assets ?>/css/profile.css" rel="stylesheet" />
</head>

<body class="">
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">

            <div class="container-xl px-4 ">
                <div class="page-header-content ">
                    <div class="row align-items-center justify-content-between py-4">
                        <div class="col-auto mt-0">
                            <h5 class="page-header-title align-middle">
                                <div class="page-header-icon"><i class="fas fa-user me-3"></i>
                                    Bem-vind<?= $perfil['sexo'] == 'F' ? 'a' : 'o' ?>,
                                    <?= $perfil['nome'] ?? 'User' ?>
                                </div>
                            </h5>
                        </div>
                        <div class="col-5 d-flex flex-end justify-content-end flex-row ">
                            <div class="ms-4">
                                <a href="<?= BASE_URL ?>" class="link-dark">Página Inicial</a>
                            </div>
                            <div class="ms-4">
                                <a href="<?= BASE_URL . '/candidatura.php' ?>" class="link-dark">Submeter Ideia</a>
                            </div>
                            <div class="ms-4">
                                <a class="link-dark ms-0" href="index.php">Perfil</a>
                            </div>
                            <div class="ms-5">
                                <a href="<?= BASE_URL . '/logout.php' ?>" class="link-dark">Sair</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <!-- banner -->
            <div class="my-4 p-5 bg-dark bg-gradient rounded-4" style="height: 10rem">
                <h1 class="display-3 text-light">
                    Bem Vindo, <?= $perfil['nome'] ?? '' ?>
                </h1>
            </div>
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                <a class="nav-link" href="ideias.php">Minhas Submissões</a>
                <a class="nav-link ms-0" href="team.php">Equipa</a>
                <a class="nav-link" href="security.php">Segurança</a>
            </nav>
            <hr class="mt-0 mb-4" />