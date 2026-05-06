<?php include_once 'backend.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>401 Error</title>
    <link href="<?= assets ?? 'assets' ?>/css/bootstrap.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="layoutError">
        <div id="layoutError_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center mt-4">
                                <h1 class="display-1">401</h1>
                                <img class="mb-4 img-error w-50" draggable="false"
                                    src="<?= assets ?>/img/undraw_security.svg" />
                                <p class="lead">Unauthorized</p>
                                <p>Accesso negado para aceder a esta página.</p>
                                <a href="#" class="btn btn-link link" onclick="history.back()">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Voltar
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($_GET['mensagem'] ?? '' != 'nologin'): ?>
                    <a href="<?= BASE_URL ?? '' ?>/login.php"
                        class="link d-block text-muted text-center text-decoration-none" onclick="history.back()"> Fazer
                        Login</a>
                <?php endif; ?>
            </main>
        </div>
    </div>
    </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>