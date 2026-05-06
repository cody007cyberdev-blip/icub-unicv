<?php

// constante
const SUPERV = BASE_URL . '/supervisor';

////* descomentar o codigo abaixo para validar login
verificarAutenticacao('supervisor', true);

$perfil = (new Model('tbl_supervisor'))->get($_SESSION['usuario']['id']);
$nome = $perfil['nome'] ?? 'Guest';
$email = $perfil['email'] ?? 'my@email';
$foto = $perfil['foto'] ?? '';
$perfil = Database::prepare('SELECT * FROM tbl_supervisor WHERE id = ?', [$_SESSION['usuario']['id']])->fetch();
$dataAtual = new DateTime('now');
$dataFormatada = $dataAtual->format('Y-m-d H:i:s');

$dataEntrada = new DateTime($perfil['data_entrada']);
$dataEntradaFormatada = $dataEntrada->format('Y-m-d');

if ($dataEntradaFormatada !== $dataAtual->format('Y-m-d')) {
    Database::execute("UPDATE tbl_supervisor SET data_entrada = ? WHERE id = ?", [$dataFormatada, $perfil['id']]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php includeTemplate('meta') ?>
    <!-- inclui todas as depencias, cdn etc -->
    <title>Supervisão | ICUB</title>
    <link href="<?= assets ?>/css/admin-styles.css" rel="stylesheet" />
    <link href="<?= assets ?>/css/sb-admin.css" rel="stylesheet" />
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
        <a class="navbar-brand ps-3" style="font-variant: small-caps;" href="<?= SUPERV ?>/">
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
                    <li class="dropdown-item px-0"><a class="dropdown-item" href="<?= SUPERV ?>/perfil.php">
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
            <nav class="sb-sidenav  accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu ">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Supervisão</div>
                        <a class="nav-link" href="<?= SUPERV ?>/index.php#dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-panorama"></i></div>
                            Painel Principal
                        </a>
                        <div class="sb-sidenav-menu-heading">Projetos</div>
                        <a class="nav-link" href="<?= SUPERV ?>/projetos.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-folder-tree"></i></div>
                            Gerir Projetos
                        </a>
                        <a class="nav-link" href="<?= SUPERV ?>/equipa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-folder-tree"></i></div>
                            Gerir Equipas
                        </a>

                    </div>
                </div>
                <!-- o codigo style css foi colocado la em cima -->
                <div class="sb-sidenav-footer">
                    <a href='perfil.php' class="user-info d-flex align-items-center text-decoration-none text-dark">
                        <div class="user-photo rounded-circle overflow-hidden me-3">
                            <img src="<?= foto_utilizador($foto) ?>" alt="Foto do Usuário" class="w-100">
                        </div>
                        <div>
                            <div class="small text-muted">Supervisor</div>
                            <div class="text-light fw-bold"><?= $nome ?></div>
                        </div>
                    </a>
                </div>

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>



                <?php if (isset($_GET['mensagem'])) { ?>
                <script>
                Swal.fire({
                    icon: "success",
                    title: "<?php echo $_GET['mensagem']; ?>"
                });
                </script>
                <?php } ?>