<?php
// Verifica a autenticação e carrega os dados do usuário
// verificarAutenticacao();

$foto = fotoUsuario();
$caminho_imagen = assets . '/img';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php includeTemplate('meta') ?>
    <title>Sistema de Gestão De Website iCUB</title>
    <style>
        .navbar-nav .nav-link {
            color: black;
            /* Define a cor do texto para preto */
            position: relative;
            /* Define a posição como relativa para que os pseudo-elementos sejam posicionados em relação a este elemento */
        }

        .navbar-nav .nav-link::before {
            content: "";
            /* Adiciona um pseudo-elemento para criar o sublinhado */
            position: absolute;
            /* Posição absoluta em relação ao elemento pai */
            top: 118%;
            /* Alinha o pseudo-elemento abaixo do texto */
            left: 50%;
            /* Alinha o pseudo-elemento com o centro do link */
            width: 0;
            /* Define a largura inicial do sublinhado como 0 */
            height: 2px;
            /* Define a altura do sublinhado */
            background-color: #a90e2a;
            /* Define a cor do sublinhado */
            transition: width 0.3s ease-in-out, left 0.3s ease-in-out;
            /* Adiciona uma transição suave para a largura e posição */
            transform: translateX(-50%);
            /* Move o pseudo-elemento para o centro do link */

        }

        .navbar-nav .nav-link:hover::before {
            width: calc(100% + 20px);
            /* Ajusta a largura do sublinhado ao passar o mouse, com uma folga de 20px para cobrir todo o texto */

        }




        /* Estilo para o logo que funciona como botão */
        .logo-btn {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
        }

        .logo-btn img {
            width: 100px;
            /* Largura do logo */
            height: auto;
            /* Altura automática para manter a proporção */
            margin-right: 10px;
            /* Espaçamento entre o logo e o texto */
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-photo {
            width: 50px;
            /* Defina o tamanho da foto do usuário conforme necessário */
            height: 50px;
            /* Defina o tamanho da foto do usuário conforme necessário */
            border-radius: 50%;
            /* Para criar uma imagem de perfil circular */
            overflow: hidden;
            /* Para esconder partes da imagem fora do raio */
        }

        .user-name h5 {
            margin: 0;
            /* Remova o espaçamento ao redor do nome do usuário */
        }

        .header {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }

        .navbar {
            flex-grow: 1;
            justify-content: center;
            background-color: white;
        }
    </style>

</head>
</body>
<header>
    <div class="header">
        <!-- Logo no canto superior esquerdo como botão -->
        <a href="<?= url_base; ?>/coordenador" class="navbar-brand logo-btn">

            <img src="<?= $caminho_imagen; ?>/lgicub.png" alt="Logo iCUB">
        </a>

        <!-- Barra de navegação -->
        <nav class="navbar navbar-expand navbar-light bg-light bg-white">
            <!-- Botões de navegação centralizados -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_base; ?>candidaturas">CANDIDATURAS ></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_base; ?>candidatos">CANDIDATOS ></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_base; ?>supervisores">SUPERVISORES ></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_base; ?>projetos">PROJETOS ></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_base; ?>coordenador">MEU PERFIL ></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_base; ?>logout.php">SAIR ></a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Seção de informações do usuário -->
        <div class="user-info">
            <div class="user-photo rounded-circle overflow-hidden me-3">
                <img src="<?= $foto; ?>" alt="Foto do Usuário" class="w-100">
            </div>
            <div class="user-name">
                <h5><?php echo $_SESSION['usuario']['nome'] ?? 'Guest' ?></h5>
            </div>
        </div>
    </div>
</header>


<main class="container">

    <?php if (isset($_GET['mensagem'])) { ?>
        <script>
            Swal.fire({ icon: "success", title: "<?php echo $_GET['mensagem']; ?>"});
        </script>
    <?php } ?>