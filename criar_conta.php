<?php include 'backend.php';

//* validar o email se ja existe: a requisicao é feita pelo ajax, ao validar a conta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkEmail'])) {
    $check = filter_input(INPUT_POST, 'checkEmail', FILTER_VALIDATE_EMAIL);
    $exists = (new Model('tbl_candidato'))->find('email', $check);
    if (!$exists) {
        $_SESSION['last_email'] = null;
        echo json_encode(true);
    } else {
        $_SESSION['last_email'] = $check;
        echo json_encode(false);
    }
    exit;
}

//* Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // capturar dados do formulario
    $nome = htmlspecialchars($_POST['nome']);
    $email = $_POST["email"];
    $sexo = $_POST["sexo"] == 'M' ? 'M' : ($_POST["sexo"] == 'F' ? 'F' : ' ');
    $data_nascimento = $_POST["data_nasc"];
    $nacionalidade = $_POST["nacionalidade"] ?? "desconhecido";
    $contacto = $_POST["telefone"] ?? "";
    $endereco = htmlspecialchars($_POST['endereco'] ?? "");
    $password = $_POST["password"];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    // $datahorario = date('Y-m-d H:i:s');

    // proceda à inserção dos dados
    $sentence = $conexion->prepare("INSERT INTO 
    `tbl_candidato` (`nome`, `sexo`, `contacto`, `endereco`, `email`, `password`, `nacionalidade`, `data_nascimento`) 
    VALUES (:nome, :sexo, :contacto, :endereco, :email, :password, :nacionalidade, :data_nascimento);",);
    // bind values
    $sentence->bindValue(":nome", $nome);
    $sentence->bindValue(":sexo", $sexo);
    $sentence->bindValue(":contacto", $contacto);
    $sentence->bindValue(":endereco", $endereco);
    $sentence->bindValue(":email", $email);
    $sentence->bindValue(":password", $passwordHash);
    $sentence->bindValue(":nacionalidade", $nacionalidade);
    $sentence->bindValue(":data_nascimento", $data_nascimento);

    // Executa a consulta SQL
    $sentence->execute();

    if ($sentence->rowCount() > 0) {
        $user = (new Model('tbl_candidato'))->find('email', $email);
        $_SESSION['usuario'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'foto' => $user['foto'],
            'tipo' => 'candidato'
        ];
        Cookie::set('remember_me', $email, 1);  // guardar no lembrar-me por 1 dia
        flashMessage("Registro feito! Bem-vindo, $nome", 'success');
        redirect('perfil');
    } else {
        flashMessage("Erro: Por favor tente novamente", 'error');
    }
    // post redirect get
    redirect('criar_conta.php');
}

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Cubo de Inovação ICUB" />
    <meta name="author" content="ICUB UNICV" />
    <title>Registrar Conta iCUB</title>

    <!-- Icone (favicon)-->
    <link rel="icon" type="image/x-icon" href="<?= assets ?? '/assets' ?>/img/icon.png" />
    <!-- google fonts-family (Montserrat, Poppins, Roboto, Source Code Pro, Ubuntu, Josefin Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- fontawesome para icone -->
    <script data-search-pseudo-elements="" defer=""
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?= assets ?? '/assets' ?>/css/bootstrap.css" rel="stylesheet" />

    <style>
        * {

            font-family: 'Montserrat';
        }

        .custom-button {
            background-color: #999;
            color: white;
            font-weight: bold;
            text-align: center;
            display: block;
            margin: 0 auto;
        }

        .custom-button:hover {
            background-color: #32CD32;
            color: white;
        }

        label.error {
            color: red;
            font-size: small;
        }
    </style>
</head>

<body>
    <main class="bg-light min-vh-100">
        <div id='cardcreate' class="d-block pt-5 pb-2">
            <div class="card my-3 p-1 mx-auto" style="width:25rem">
                <a href="index.php" type="button" class="btn btn-outline-dark position-absolute rounded-circle"
                    style="left: -25%; top: 1em" aria-label="back" onCLick="">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="card-title text-center pt-3">Criar Conta</h2>
                <img class="logo-img mx-auto w-25" src="<?= assets ?>/img/lgicub.png" alt="Logo do iCUB">
                <div class="card-body">
                    <!-- form -->
                    <form id="formulario" action="criar_conta.php" class="d-flex flex-column justify-content-center"
                        method="POST">
                        <div class="mb-3 mt-2 p-0 form-floating0">
                            <label for="nome" class="form-label form-label-sm mb-0 small">Nome:</label>
                            <input type="text" class="form-control form-control" name="nome" id="nome"
                                placeholder="Nome e Sobrenome" required>
                        </div>
                        <div class="mb-3 p-0 form-floatingx form-control-sm">
                            <label for="email" class="form-label form-label-sm mb-0">E-mail (institucional)</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="exemplo@student.unicv.edu.cv" required>
                        </div>
                        <div class="mb-3 form-goup">
                            <label for="password" class="form-label form-label-sm mb-0 small">Palavra-passe</label>
                            <input type="password" class="form-control " name="password" id="password"
                                placeholder="palavra-passe" required>
                            <input type="password" class="form-control mt-1" name="password2" id="password2"
                                placeholder="Confirmar Palavra-passe" required>
                        </div>
                        <div class="position-relative mb-3">
                            <hr class="w-75 mx-auto">
                            <span
                                class="small text-muted text-center d-inline-block  position-absolute start-50 top-50 translate-middle bg-body px-2">
                                Informações Pessoais
                            </span>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <label class="input-group-text form-label  mb-0">Sexo</label>
                                <div class="form-control justify-content-around">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="sexm">Masculino</label>
                                        <input class="form-check-input" type="radio" name="sexo" id="sexm" value="M"
                                            required />
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="sexf">Feminino</label>
                                        <input class="form-check-input" type="radio" name="sexo" id="sexf" value="F"
                                            required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label form-label-sm small mb-0">
                                Data de Nascimento</label>
                            <input type="date" class="form-control form-control" name="data_nasc" id="data"
                                placeholder="" max="<?= (new DateTime('17 years ago january 1st'))->format('Y-m-d') ?>"
                                required>
                        </div>
                        <div class="form-group mb-2">
                            <div class="mb-2">
                                <label for="nacional" class="form-label form-label-sm small mb-0">Nacionalidade
                                    (País)</label>
                                <select class="form-select" name="nacionalidade" id="nacional">
                                    <option>Selecione a sua nacionalidade</option>
                                    <!-- ajax para obter a lista de todos os paises este option sera preenchido automaticamente -->
                                </select>
                            </div>
                            <div class="mb-2 form-floating">
                                <input type="text" class="form-control form-control" name="endereco" id="endereco"
                                    placeholder="">
                                <label for="endereco" class="form-label form-label-sm small mb-0">
                                    Endereço (Morada)</label>
                            </div>
                            <div class="mb-2 form-floating">
                                <input type="tel" class="form-control form-control-sm" name="telefone" id="tel"
                                    placeholder>
                                <label for="tel" class="form-label form-label-sm small mb-0"> Telefone</label>
                            </div>
                        </div>
                        <div class="form-check mb-5">
                            <label class="form-check-label small " for="">
                                Concordo com os <a href="termos.php" target="_blank" tabindex="-1">termos</a>
                                e <a href="politicas.php" target="_blank" tabindex="-1">politicas</a> do iCUB.
                            </label>
                            <input name="accept" class="form-check-input" type="checkbox" value="" id="" />
                        </div>

                        <input type="submit" class="btn custom-button" name="registrar" value="CRIAR" id="sumbitForm" />

                        <div class="w-100 mt-4 text-center" style="font-size: 75%;">
                            <span class="text-muted">Já tens uma conta? <a class href="login.php">
                                    Faça Login</a></span>
                        </div>
                    </form>
                </div>


            </div>
            <div class="text-center py-3">
                <p style="font-size: 14px; color: #555;">© Universidade de Cabo Verde</p>
            </div>
        </div>
    </main>

    <?php if ($msg = flashMessage()) { ?>
        <script>
            Swal.fire({
                icon: "<?= flashStatus() ?>",
                title: "Criar conta",
                text: "<?= $msg ?>",
                confirmButtonText: "Fazer Login"
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.location.href = "login.php"; // Redireciona para a tela de login
                }
            });
        </script>
    <?php } ?>

    <script>
        /**
         **Codigo Para preencher o select com todos os paises
         */
        $(document).ready(function () {

            $("#sumbitForm").click(function (e) {
                // e.preventDefault();
                // var form = $('#formulario');
                // if (form.valid()) {
                //     form.submit();
                //     return true;
                // }
            })

            const countrySelect = $('#nacional');
            $.ajax({
                url: '<?= assets ?>/js/countriesJson_ptBR.json', // Replace with your preferred API URL
                dataType: 'json',
                async: true,
                success: function (data) {
                    data.forEach(function (country) {
                        const option = $('<option>');
                        option.val(country.nome);
                        option.text(country.nome);
                        option.attr('title', country.nomeFormal);
                        countrySelect.append(option);
                    });
                    $('#nacional option[value="Cabo Verde"]').prop('selected', true);
                },
                error: function (error) {
                    const option = $('<option>');
                    option.val('Cabo Verde');
                    option.text('Cabo Verde');
                    countrySelect.append(option);
                }, finish: function () {
                }
            });


            /**
             * Validacao do formulario
             * @author Erilando Carvalho
             * @@summary usando Jquery Validator
             */

            $.validator.addMethod("unicvEmail", function (value, element) {
                return this.optional(element) || /.+@.*unicv.edu.cv$/.test(value);
            }, "Por favor, insira um e-mail institucional da UNICV.");

            $.validator.addMethod("telephone", function (value, element) {
                let regex = /^(?:(\+|00)\d{1,3}\s)?\d{7,20}$/;  // eu que fiz isso hein, to bom kkkkkk
                return this.optional(element) || regex.test(value);
            }, "Insira um numero de telefone valido");

            $("#formulario").validate({
                rules: {
                    nome: {
                        required: true,
                        minlength: 5
                    },
                    email: {
                        required: true,
                        email: true,
                        unicvEmail: true,
                        remote: {
                            url: "",
                            type: "post",
                            data: {
                                checkEmail: function () {
                                    return $("#email").val();
                                }
                            }
                        }
                    },
                    accept: {
                        required: true
                    },
                    telefone: {
                        telephone: true
                    },
                    password: {
                        required: true,
                        minlength: 4
                    },
                    password2: {
                        required: true,
                        equalTo: '#password'
                    },
                    sexo: {
                        required: true
                    }
                },
                messages: {
                    nome: {
                        required: "Por favor, digite seu nome",
                        minlength: "O nome deve ter pelo menos 5 caracteres"
                    },
                    email: {
                        required: "Por favor, digite seu email",
                        email: "Por favor, digite um email válido",
                        remote: "Este email já foi registrado. <a href='login.php'>iniciar sesão</a>?"
                    },
                    password: {
                        required: 'Por favor, digite uma palavra-passe'
                    },
                    password2: {
                        required: 'por favor, confirme a sua palavra-passe',
                        equalTo: 'Confirme as palavra-passes, devem ser iguais'
                    },
                    data_nasc: {
                        required: "Por favor, informe sua data de nascimento",
                        max: "Você precisa term pelo menos 18 anos de idade"
                    },
                    telefone: {
                        digits: "<span class='ms-5 ps-3'>(Por favor, apenas números)</span>"
                    },
                    sexo: {
                        required: "seleciona seu género"
                    }
                }
            });

        });

    </script>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>