<?php include ("backend.php");

// lembrar do email do utilizador que deu check no 'Lermbrar-me'
$remember_email = Cookie::get('remember_me') ?? '';

if ($_POST) {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $lembrar = $_POST['lembrar'] ?? false;
    $_SESSION['last_email'] = $email;

    // Prepare and execute the first query for tbl_coordenador
    $registo = null;
    $tipo = '';
    if ($registo = (new Model('tbl_candidato'))->find('email', $email)) {
        $tipo = 'candidato';
    } elseif ($registo = (new Model('tbl_coordenador'))->find('email', $email)) {
        $tipo = 'coordenador';
    } elseif ($registo = (new Model('tbl_supervisor'))->find('email', $email)) {
        $tipo = 'supervisor';
    }
    // terminou as buscas, 
    if ($registo) {
        // encontrou as credinciais corretas, verificar o password:
        if (password_verify($password, $registo['password'])) {
            $_SESSION['usuario'] = [
                'id' => $registo["id"],
                'nome' => $registo["nome"],
                'email' => $registo["email"],
                'foto' => $registo['foto'],
                'tipo' => $tipo
            ];
            // remember-me
            if ($lembrar) {
                Cookie::set('remember_me', $email, 2);
            } else {
                Cookie::set('remember_me', '', 2);
            }

            // As informacoes do usuario logado estao na variavel session ['usuario'] que é um array que contem
            // os campos 'nome', 'email', 'foto' e 'tipo'.
            // para acessar essas informacoes so precisamos de $_SESSION['usuario']['c campo que queremos'];

            switch ($tipo) {
                case 'candidato':
                    redirect('index.php');
                    break;
                case 'coordenador':
                    redirect('coord');
                    break;
                case 'supervisor':
                    redirect('supervisor');
                    break;
            }
        } else {
            flashMessage("Erro: Credenciais incorretas.");
        }
    } else {
        flashMessage("Erro: Nenhum utilizador com este email foi econtrado.");
    }
    // post redirect get
    redirect('login.php');
}



?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <?php includeTemplate('meta') ?>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- font awesome -->

    <title>Login - iCUB</title>
    <link rel="stylesheet" href="<?= assets ?>/css/styles.css">

</head>

<body>

    <style>
        * {
            font-family: 'Montserrat', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .custom-text {
            color: #000000;
            /* Defina a cor desejada aqui */
            font-weight: bold;
            text-align: center;
        }

        .logo-img {
            width: 120px;
            /* Defina a largura desejada */
            height: auto;
            /* Mantém a proporção da imagem */
            display: block;
            /* Define a imagem como bloco */
            margin: auto;
            /* Define margens automáticas para centralizar horizontalmente */
        }

        .custom-button {
            background-color: #999999;
            /* Cor de fundo desejada */
            color: white;
            /* Cor do texto */
            font-weight: bold;
            text-align: center;
            display: block;
            /* Define o botão como bloco para ocupar toda a largura disponível */
            margin: 0 auto;
            /* Define margens automáticas para centralizar o botão horizontalmente */


        }

        /* Define o estilo do botão quando o mouse está sobre ele */
        .custom-button:hover {
            background-color: #32CD32;
            /* Cor de fundo desejada */
            color: white;
            /* Cor do texto */
            font-weight: bold;
            text-align: center;
            display: block;
            /* Define o botão como bloco para ocupar toda a largura disponível */
            margin: 0 auto;
            /* Define margens automáticas para centralizar o botão horizontalmente */


        }
    </style>

    <main class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php if ($erro = flashMessage()) { ?>
                    <div class="alert alert-danger small text-center    " role="alert">
                        <strong><?= $erro ?></strong>
                    </div>
                <?php } ?>
                <div class="card  px-1 py-1 mx-auto" style="width:21rem">
                    <a href="index.php" type="button" class="btn btn-outline-dark position-absolute rounded-circle"
                        style="left: -25%; top: 1em" aria-label="back" onCLick="">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="card-title custom-text mt-3">Autenticação</h2>
                    <img class="logo-img w-25" src="<?= assets ?>/img/logo.png" alt="Logo do iCUB">

                    <div class="card-body">

                        <form action="" class="was-validated d-flex flex-column justify-content-center" method="post">
                            <div class="mb-2">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" class="form-control form-control-sm" name="email" id="email"
                                    placeholder="Digite o seu Email" required
                                    value="<?= $_SESSION['last_email'] ?? $remember_email ?? '' ?>">
                                <!-- <div class="valid-feedback">Válido.</div> -->
                                <!-- <div class="invalid-feedback">Por favor, preencha este campo.</div> -->
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label">Palavra-passe:</label>
                                <input type="password" class="form-control form-control-sm" name="password"
                                    id="password" placeholder="Digite a sua Palavra-passe" required>
                                <!-- <div class="valid-feedback">Válido.</div> -->
                                <!-- <div class="invalid-feedback">Por favor, preencha este campo.</div> -->
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" name="lembrar" type="checkbox" id="lembrar" <?= $remember_email ? 'checked': ''; ?> />
                                <label class="form-check-label" for="lembrar">Lembrar-me</label>
                            </div>


                            <button type="submit" class="btn custom-button">LOGIN</button>
                            <div class="w-100 mt-4 text-center" style="font-size: 75%;">
                                <span class="text-muted">Não tens uma conta? <a class href="criar_conta.php">
                                        Criar conta</a></span>
                            </div>
                        </form>
                    </div>


                </div>

                <div class="text-center py-3">
                    <p style="font-size: 14px; color: #555;">© Universidade de Cabo Verde</p>
                </div>
            </div>

        </div>

    </main>

    <footer>
    </footer>


    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>
<?php $_SESSION['last_email'] = null; ?>