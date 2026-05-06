<?php

// constante
const COORD = BASE_URL . '/coord';

////* descomentar o codigo abaixo para validar login
verificarAutenticacao('coordenador', true);
// Assuming session is already started
$nome = $_SESSION['usuario']['nome'] ?? 'Guest';
$foto = $_SESSION['usuario']['foto'] ?? '';
$email = $_SESSION['usuario']['email'] ?? '';

$perfil = Database::prepare('SELECT * FROM tbl_coordenador WHERE id = ?', [$_SESSION['usuario']['id']])->fetch();
$dataAtual = new DateTime('now');
$dataFormatada = $dataAtual->format('Y-m-d H:i:s');

$dataEntrada = new DateTime($perfil['data_entrada']);
$dataEntradaFormatada = $dataEntrada->format('Y-m-d');

if ($dataEntradaFormatada !== $dataAtual->format('Y-m-d')) {
    Database::execute("UPDATE tbl_coordenador SET data_entrada = ? WHERE id = ?", [$dataFormatada, $perfil['id']]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php includeTemplate('meta') ?> <!-- inclui todas as depencias, cdn etc -->
    <title>Coord | Icub-unicv</title>
    <link href="<?= assets ?>/css/admin-styles.css" rel="stylesheet" />
    <link href="<?= assets ?>/css/sb-admin.css" rel="stylesheet" />
    <script src="<?= assets ?>/js/remember.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        p,
        a,
        h1 {
            font-family: 'Poppins', 'Montserrat', Verdana, Geneva, Tahoma, sans-serif;
        }

        /* Para colocar o menu activo de acordo com a pagina */
        .actived {
            font-weight: 700;
        }
        label.error {
            color: red;
            font-size: small;
        }
        /* by Edson vaz */
        .user-info {
            display: flex;
            align-items: center;
        }

        .user-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            /* Espaço entre a foto e o texto */
        }

        .user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Garante que a imagem preencha a área sem distorção */
        }
        
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark" style="background-color: #a90e2a">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" style="font-variant: small-caps;" href="<?= COORD ?>/">
                    <img class="img-fluid me-2" width="50" src="<?= assets ?>/img/lloogoo.png" alt="logo">
                   SISTEMA ICUB
                </a> 

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0 d-flex align-items-center">
            <!-- nome e email -->
            <a class="nav-link text-light">
                <b><?= $nome ?></b>
                <span class="d-none d-md-inline-block text-light-subtle">(<?= $email ?>)</span>
            </a>
            <ul class="nav-item dropdown d-flex ">
                <!-- icone de notificacao -->
                <!-- <a href="" class="nav-link me-4" title="não há notificações">
                    <i class="fas fa-envelope-circle-check position-relative">
                        <span
                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">Novas Notificações</span>

                        </span>
                    </i>
                </a> -->
                <!-- icone de conta -->
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <!-- <img class="img-fluid rounded-circle " width="42" src="< fotoUsuario() ?>" alt="">  -->
                    <i class="bi bi-person-fill"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li class="dropdown-item px-0"><a class="dropdown-item" href="<?= COORD ?>/perfil.php">
                            <i class="fas fa-user me-2"></i> Meu Perfil</a></li>
                    <hr class="dropdown-divider">
                    <li class="dropdown-item px-0"><a class="dropdown-item" href="<?= BASE_URL ?>/logout.php">
                            <i class="fas fa-power-off me-2"></i>Sair</a></li>
                </ul>
                </li>
            </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav  accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu ">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Coordenação</div>
                        <a class="nav-link" href="<?=COORD?>/index.php#dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Registros</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-gear"></i></div>
                            Utilizadores
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?=COORD?>/candidatos.php">Candidatos</a>
                                <a class="nav-link" href="<?=COORD?>/supervisores.php">Supervisores</a>
                                <a class="nav-link" href="<?=COORD?>/coordenadores.php">Coordenadores</a>

                            </nav>
                        </div>
                            <a class="nav-link" href="<?=COORD?>/projetos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-cubes"></i></div>
                                Projetos
                            </a>
                            <a class="nav-link" href="<?=COORD?>/equipa.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Equipas
                            </a>
                            <a class="nav-link" href="<?= COORD ?>/candidaturas.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Submissão ideias
                            </a>
                        <div class="sb-sidenav-menu-heading">Website</div>
                        <a class="nav-link" href="<?= COORD ?>/gestaoativos.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-pager"></i></div>
                            Gestão de Ativos
                        </a>
                    </div>
                </div>
                <!-- o codigo style css foi colocado la em cima -->
                <div class="sb-sidenav-footer">
                    <a href='perfil.php' class="user-info d-flex align-items-center text-decoration-none text-dark">
                        <div class="user-photo rounded-circle overflow-hidden me-3">
                            <img src="<?= foto_coordenador($foto) ?>" alt="Foto do Usuário" class="w-100">
                        </div>
                        <div>
                            <div class="small text-muted">Coordenador</div>
                            <div class="user-name"><?= $nome ?></div>
                        </div>
                    </a>
                </div>

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>