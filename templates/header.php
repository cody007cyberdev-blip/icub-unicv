<?php
////* descomentar o codigo abaixo para validar login
/// verificarAutenticacao('candidato', true);
// Assuming session is already started
$nome = $_SESSION['usuario']['nome'] ?? 'Guest';
$foto = $_SESSION['usuario']['foto'] ?? '';
$email = $_SESSION['usuario']['email'] ?? '';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php includeTemplate('meta') ?>
    <title>ICUB - UNICV</title>

    <style>
        .navbar-nav .nav-link {

            position: relative;
        }

        .navbar-nav .nav-link::before {
            content: "";
            position: absolute;
            bottom: 0;
            /* Ajuste conforme necessário para posicionar o sublinhado */
            left: 50%;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease-in-out, left 0.3s ease-in-out;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::before {
            width: 100%;
        }


        .nav-item {
            text-align: center;
            /* Centraliza o texto horizontalmente */
        }


        p,
        a,
        form {
            font-family: 'Montserrat';
        }

        p,
        a,
        h1 {
            font-family: 'Poppins', 'Montserrat', Verdana, Geneva, Tahoma, sans-serif;
        }

        label.error {
            color: red;
            font-size: small;
        }

        /* by Edson vaz */

        .user-info {
            display: flex;
            align-items: center;
            border: none !important;
            /* background-color: transparent !important; */
        }

        .user-photo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 0.8em;
            /* Espaço entre a foto e o texto */
        }

        .user-photo img {
            /* Garante que a imagem preencha a área sem distorção */
            width: auto;
            height: 100%;
            object-fit: cover;
        }

        .card:hover #img-news{
            transform: scale(1.075) rotateZ(-1deg);
            
        }
        #img-news{
            transition: 0.5s ease-out;
            min-height: 100%;
        }

        .custom-link {
            display: block;
            text-align: center; /* Centraliza o texto horizontalmente */
            text-decoration: none; /* Remove o sublinhado */
            width: 80%; /* Opcional: ajuste conforme necessário */
        }
        
        .custom-link br {
            display: block;
            margin: 0;
        }
        
    </style>

</head>

<body class="d-flex flex-column h-100 position-relative">



    <main class="flex-shrink-0">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg  navbar-dark py-1 align-middle position-sticky top-0"
            style="background: #a90e2a; z-index: 99">
            <div class="container px-4 align-middle">
                <a class="navbar-brand py-1" style="font-variant: small-caps;" href="index.php">
                    <img class="img-fluid me-2" width="50" src="<?= assets ?>/img/lloogoo.png" alt="logo">
                    ICUB UNICV
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        <li class="nav-item "><a class="nav-link" href="<?= BASE_URL ?>/index.php">Pagina Inicial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-link" href="<?= BASE_URL ?>/candidatura.php">
                                Submeter<br>Ideia
                            </a>
                        </li>
                        <li class="nav-item "><a class="nav-link" href="<?= BASE_URL ?>/#blogs">Novidades</a>
                        <li class="nav-item "><a class="nav-link" href="<?= BASE_URL ?>/#contacts">Contactos</a>
                        </li>
                        <li class="nav-item "><a class="nav-link" href="<?= BASE_URL ?>/about.php">Sobre nós</a></li>

                        <!-- Isso será removido em produção -->
                        <!-- <li class="nav-item dropdown mt-1">
                            <a class="nav-link dropdown-toggle " id="navbarDropdownPortfolio" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
                                <li><a class="dropdown-item " href="<?= BASE_URL ?>/coord">Coordenação</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/supervisor.php">Supervisor</a></li>
                            </ul>
                        </li> -->
                        <!-- / -->


                        <li class="nav-item rounded-3 px-2 px-2 ms-4 my-2 dropdown-center">
                            <?php if (verificarAutenticacao('candidato', false)): ?>
                                <a id="dropdownHover"
                                    class="nonav-link dropdown-toggle user-info btn btn-danger bg-gradient text-light rounded p-1"
                                    id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <div class="user-photo rounded-circle overflow-hidden">
                                        <img draggable="false" width="20%" src="<?= foto_candidato($foto) ?>" alt="">
                                    </div>
                                    <span
                                        class="text-white text-wrap"><?= explode(" ", $nome)[0] ?? 'Profile'; // pegar somente o primeiro nome ?></span>
                                </a>
                                <ul class="dropdown-menu mt-3 " id="profileHover" aria-labelledby="navbarDropdown">
                                    <li class="dropdown-item p-0 py-1">
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/perfil">
                                            <i class="fas fa-user me-2"></i> Meu Perfil
                                        </a>
                                    </li>
                                    <li class="dropdown-item p-0 py-1">
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/perfil/projects.php">
                                            <i class="fas fa-calendar-days me-2 "></i> Projetos
                                        </a>
                                    </li>
                                    <li class="dropdown-item p-0 py-1">
                                        <a class="dropdown-item small" href="<?= BASE_URL ?>/perfil/ideias.php">
                                            <i class="fas fa-address-card me-2 "></i> Minhas Submissões
                                        </a>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <li class="dropdown-item p-0">
                                        <a class="dropdown-item text-dark text-opacity-75"
                                            href="<?= BASE_URL ?>/logout.php">
                                            <i class="fas fa-power-off me-2"></i> Sair
                                        </a>
                                    </li>
                                </ul>
                            <?php elseif (verificarAutenticacao('coordenador', false)): ?>
                                <a class="nav-link btn btn-danger bg-gradient text-light rounded p-2"
                                    href="<?= BASE_URL ?>/coord">
                                    <i class="fas fa-user me-2"></i> Coordenação
                                </a>
                            <?php elseif (verificarAutenticacao('supervisor', false)): ?>
                                <a class="nav-link btn btn-danger bg-gradient text-light rounded p-2"
                                    href="<?= BASE_URL ?>/supervisor">
                                    <i class="fas fa-user me-2"></i> Supervisão
                                </a>
                            <?php else: ?>
                                <a class="nav-link btn btn-light bg-gradient rounded text-dark px-2"
                                    href="<?= BASE_URL ?>/login.php">
                                    <i class="fas fa-user me-2"></i> Entrar
                                </a>
                            <?php endif; ?>
                        </li>



                    </ul>
                </div>
            </div>
        </nav>

        <script>
            $(document).ready(function () {
                // $('#dropdownHover').hover(function(){
                //     $('#profileHover').addClass('show');
                // })
            });
        </script>