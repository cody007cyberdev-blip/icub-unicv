<?php
include 'backend.php';

verificarAutenticacao();




// Obter as informações do perfil do candidato
$perfil = Database::prepare('SELECT * FROM tbl_candidato WHERE id = ?', [
    $_SESSION['usuario']['id']
])->fetch();

// Verifica se a data de nascimento está definida e formata-a de YYYY-MM-DD para DD-MM-YYYY
$dataNascimento = isset($perfil['data_nascimento']) ? DateTime::createFromFormat('Y-m-d', $perfil['data_nascimento'])->format('d-m-Y') : '';

// Se precisar armazenar novamente em formato correto antes de inserir na tabela
$dataNascimentoFormatado = isset($perfil['data_nascimento']) ? DateTime::createFromFormat('Y-m-d', $perfil['data_nascimento'])->format('Y-m-d') : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dados pessoais
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $candidatoPrincipal = isset($_POST['candidatoPrincipal']) ? 'Sim' : 'Não';
    $genero = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $nif = isset($_POST['nif']) ? $_POST['nif'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $telemovel = isset($_POST['telemovel']) ? $_POST['telemovel'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Informações acadêmicas
    $areaFormacao = isset($_POST['areaFormacao']) ? $_POST['areaFormacao'] : '';
    $anoCurso = isset($_POST['anoCurso']) ? $_POST['anoCurso'] : '';
    $numEstudante = isset($_POST['numEstudante']) ? $_POST['numEstudante'] : '';

    // Identificação da Ideia
    $tituloIdeia = isset($_POST['ideia']) ? $_POST['ideia'] : '';
    $setor = isset($_POST['setor']) ? $_POST['setor'] : '';
    $descricaoConceito = isset($_POST['desc']) ? $_POST['desc'] : '';
    $estadoIdeia = isset($_POST['estado']) ? $_POST['estado'] : '';
    $infoComplementar = isset($_POST['details']) ? $_POST['details'] : '';
    $datahorario = date('Y-m-d H:i:s');

    // Upload de documentos
    $docIdentifi = (isset($_FILES["docIdentifi"]['name']) ? $_FILES["docIdentifi"] : null);
    $docComprovativo = (isset($_FILES['docComprovativo']['name']) ? $_FILES['docComprovativo'] : null);
    $docApresentacao = (isset($_FILES['docApresentacao']['name']) ? $_FILES['docApresentacao'] : null);
    $videoApresentacao = (isset($_FILES['videoApresentacao']['name']) ? $_FILES['videoApresentacao'] : null);

    // Instância DateTime para gerar timestamp
    $data_ = new DateTime();

    // Movimentação dos arquivos
    $nomeficheiro_docIdentifi = ($docIdentifi !== null) ? $data_->getTimestamp() . "_" . basename($docIdentifi['name']) : "";
    $tmp_docIdentifi = ($docIdentifi !== null) ? $docIdentifi['tmp_name'] : "";
    if ($tmp_docIdentifi != '') {
        move_uploaded_file($tmp_docIdentifi, PATH_UPLOADS . "/docs_submissao_ideia/cni/$nomeficheiro_docIdentifi");
    }

    $nomeficheiro_docComprovativo = ($docComprovativo !== null) ? $data_->getTimestamp() . "_" . basename($docComprovativo['name']) : "";
    $tmp_docComprovativo = ($docComprovativo !== null) ? $docComprovativo['tmp_name'] : "";
    if ($tmp_docComprovativo != '') {
        move_uploaded_file($tmp_docComprovativo, PATH_UPLOADS . "/docs_submissao_ideia/comprovativo_estudante/$nomeficheiro_docComprovativo");
    }

    $nomeficheiro_docApresentacao = ($docApresentacao !== null) ? $data_->getTimestamp() . "_" . basename($docApresentacao['name']) : "";
    $tmp_docApresentacao = ($docApresentacao !== null) ? $docApresentacao['tmp_name'] : "";
    if ($tmp_docApresentacao != '') {
        move_uploaded_file($tmp_docApresentacao, PATH_UPLOADS . "/docs_submissao_ideia/documento_apresentacao/$nomeficheiro_docApresentacao");
    }

    $nomeficheiro_videoApresentacao = ($videoApresentacao !== null) ? $data_->getTimestamp() . "_" . basename($videoApresentacao['name']) : "";
    $tmp_videoApresentacao = ($videoApresentacao !== null) ? $videoApresentacao['tmp_name'] : "";
    if ($tmp_videoApresentacao != '') {
        move_uploaded_file($tmp_videoApresentacao, PATH_UPLOADS . "/docs_submissao_ideia/video_apresentacao/$nomeficheiro_videoApresentacao");
    }



    try {
        // Iniciar a transação
        $conexion->beginTransaction();
        // Inserir na tabela tbl_candidaturas
        $sentence = $conexion->prepare(
            'INSERT INTO tbl_candidaturas (id_candidato, candidato_principal, nome, data_nasc, genero, endereco, nif, telefone, telemovel, email, area_formacao, ano_curso, num_estudante, doc_identifi, doc_comprovativo) 
            VALUES (:id_candidato, :candidato_principal, :nome, :data_nasc, :genero, :endereco, :nif, :telefone, :telemovel, :email, :area_formacao, :ano_curso, :num_estudante, :doc_identifi, :doc_comprovativo)'
        );

        // Bind dos parâmetros
        $sentence->bindParam(':id_candidato', $_SESSION['usuario']['id']);
        $sentence->bindParam(':candidato_principal', $candidatoPrincipal);
        $sentence->bindParam(':nome', $nome);
        $sentence->bindParam(':data_nasc', $dataNascimentoFormatado);
        $sentence->bindParam(':genero', $genero);
        $sentence->bindParam(':endereco', $endereco);
        $sentence->bindParam(':nif', $nif);
        $sentence->bindParam(':telefone', $telefone);
        $sentence->bindParam(':telemovel', $telemovel);
        $sentence->bindParam(':email', $email);
        $sentence->bindParam(':area_formacao', $areaFormacao);
        $sentence->bindParam(':ano_curso', $anoCurso);
        $sentence->bindParam(':num_estudante', $numEstudante);
        $sentence->bindParam(':doc_identifi', $nomeficheiro_docIdentifi);
        $sentence->bindParam(':doc_comprovativo', $nomeficheiro_docComprovativo);

        $sentence->execute();

        // Obter o ID da candidatura inserida
        $idCandidatura = $conexion->lastInsertId();

        // Inserir na tabela tbl_identificacao_ideia
        $sentence = $conexion->prepare(
            'INSERT INTO tbl_identificacao_ideia (id_candidatura, titulo_ideia, sector, descri_conceito, estado, info_complementar, data_submissao, doc_apresent, video) 
            VALUES (:id_candidatura, :titulo_ideia, :sector, :descri_conceito, :estado, :info_complementar, :data_submissao, :doc_apresent, :video)'
        );

        // Bind dos parâmetros
        $sentence->bindParam(':id_candidatura', $idCandidatura);
        $sentence->bindParam(':titulo_ideia', $tituloIdeia);
        $sentence->bindParam(':sector', $setor);
        $sentence->bindParam(':descri_conceito', $descricaoConceito);
        $sentence->bindParam(':estado', $estadoIdeia);
        $sentence->bindParam(':info_complementar', $infoComplementar);
        $sentence->bindParam(':data_submissao', $datahorario);
        $sentence->bindParam(':doc_apresent', $nomeficheiro_docApresentacao);
        $sentence->bindParam(':video', $nomeficheiro_videoApresentacao);

        $sentence->execute();

        // Confirmar a transação
        $conexion->commit();

        // Armazenar mensagem de sucesso na sessão
        $_SESSION['flash_message'] = 'Ideia submetida com sucesso! Por favor, aguarde pelo resultado da avaliação.';

        // Redirecionar para a mesma página ou outra página
        redirect("perfil/ideias.php");
        exit(); // Certifique-se de sair após o redirecionamento
    } catch (Exception $e) {

        // Reverter a transação em caso de erro
        $conexion->rollBack();
        echo '<div class="alert alert-danger" role="alert">Erro ao submeter a candidatura. Por favor, tente novamente. ' . $e->getMessage() . '</div>';
    }
}
// Exibir a mensagem flash se existir
if (isset($_SESSION['flash_message'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['flash_message'] . '</div>';
    unset($_SESSION['flash_message']); // Remover a mensagem após exibi-la

}


includeTemplate('header'); ?>
<style>
        /* colocar nos <label> a classe 'R' para mostrar asteristico vermelho (obrigatorio) */
        label.R:after {
            content: '*';
            color: orangered;
            font-size: small;
            margin-left: 0.1em;
        }

        
</style>
    <!-- Page content-->
    <section class="py-5">
        <div class="container px-3">
            <!-- Candidatura form-->
            <div class=" rounded-3 py-5 px-4 px-md-5 mb-5">
                <div class="text-center mb-5">
                    <div class="feature bg-danger bg-gradient text-white rounded-3 mb-3"><i class="bi bi-envelope"></i>
                    </div>
                    <h1 class="fw-bolder">Submissão de Ideia</h1>
                    <p class="lead fw-normal text-muted mb-0">Faça agora a sua submissão de ideia livre !</p>
                </div>
                <div class="row">
                    <div class="">
                        <form id="formulario" action="" method="post" class="row needs-validation" enctype="multipart/form-data"
                            novalidate>
                            <div class="col-12 col-lg-4 me-4">
                                <h4>Dados pessoais</h4>
                                <div class="col form-row my-3">
                                    <label for="nome" class="text-muted form-label form-label-sm R">Nome</label>
                                    <div class="form-group input-group">
                                        <label for="nome" class="input-group-text"><i class="fa fa-user"
                                                aria-hidden="true"></i></label>
                                        <input class="form-control" id="nome" name="nome" placeholder="Nome Sobrenome"
                                            value="<?= $perfil['nome'] ?? '' ?>"  readonly />
                                            
                                    </div>
                                </div>

                                <div class="col form-row my-3 form-group">
                                    <input type="checkbox" id="candidatoPrincipal" name="candidatoPrincipal" checked
                                        class="form-check-input" />
                                    <label for="candidatoPrincipal" class="ms-2">Sou o candidato principal</label>
                                </div>


                                <div class="form-row my-3">
                                    <div class="form-group">
                                        <label for="dataNascimento" class="text-muted form-label form-label-sm ">Data
                                            de Nascimento</label>
                                        <input type="data" class="form-control" id="dataNascimento"
                                            name="dataNascimento" placeholder="dd/mm/aaaa"
                                            value="<?=  (new DateTime($dataNascimento))->format('d-F-Y') ?>" readonly />
                                    </div>

                                    <div class="form-group col-md-6 my-3">
                                        <label class="text-muted form-label form-label-sm">Género</label>
                                        <div class="d-flex justify-content-start gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="sexo" id="masculino"
                                                    value="Masculino" <?php if ($perfil['sexo'] ?? '' == 'M')
                                                        echo 'checked'; ?>
                                                    readonly />
                                                <label class="form-check-label" for="masculino">Masculino</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="sexo" id="feminino"
                                                    value="Feminino" <?php if ($perfil['sexo'] ?? '' == 'F')
                                                        echo 'checked'; ?>
                                                    readonly />
                                                <label class="form-check-label" for="feminino">Feminino</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row g-1">
                                    <div class="col">
                                        <label for="endereco"
                                            class="text-muted form-label form-label-sm R">Endereço Actual</label>
                                        <input type="text" class="form-control" id="endereco" name="endereco"
                                            placeholder="Cidade, Bairo" aria-label="city" 
                                            value="<?= $perfil['endereco'] ?? '' ?>" required  />
                                    </div>

                                    <div class="mb-3 mt-4">
                                        <label for="nif" class="text-muted form-label form-label-sm R">Número de
                                            Identificação Fiscal (NIF)</label>
                                        <input type="text" class="form-control" id="nif" name="nif"
                                            placeholder="Ex.: 123456789" required>
                                    </div>


                                    <div class="mb-3 mt-4">
                                        <label for="" class="text-muted form-label form-label-sm  R">Documento
                                            de Identificação
                                            <span class="text-muted">(CNI/BI)</span></label>
                                        <input type="file" class="form-control form-control-sm" id="docIdentifi"
                                            name="docIdentifi" placeholder="" aria-describedby="fileHelpId"
                                            accept=".pdf" required />
                                    </div>
                                </div>



                                <!-- Contactos -->
                                <div class="mb-3 mt-4">
                                    <h4>Contactos</h4>
                                    <div class="form-group my-3">
                                        <label for="telefone"
                                            class="text-muted form-label form-label-sm">Telefone</label>
                                        <input type="text" class="form-control" id="telefone" name="telefone"
                                            placeholder="Ex.: 123456789" />
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="telemovel"
                                            class="text-muted form-label form-label-sm R">Telemóvel</label>
                                        <input type="text" class="form-control" id="telemovel" name="telemovel"
                                            placeholder="Ex.: 9999999" required>
                                    </div>

                                    <div class="form-group my-3 form-flow">
                                        <label for="email" class="text-muted form-label form-label-sm R">Email
                                            (institucional)</label>
                                        <div class="input-group">
                                            <label class="input-group-text"><i class="fas fa-at"></i></label>
                                            <input class="form-control" id="email" name="email"
                                                onKeyup="validateEmail(this)" placeholder="exemplo@student.unicv.edu.cv"
                                                value="<?= $perfil['email'] ?? '' ?>" readonly />
                                        </div>
                                    </div>

                                </div>


                                <!-- Informações Académicas -->
                                <h4 class="mt-4">Informações Académicas</h4>
                                <div class="form-group mb-3">
                                    <label for="areaFormacao" class="text-muted form-label form-label-sm">Área de
                                        Formação</label>
                                    <input type="text" class="form-control" id="areaFormacao" name="areaFormacao"
                                        placeholder="Ex.: Engenharia Informática" />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="anoCurso" class="text-muted form-label form-label-sm">Ano do
                                        Curso</label>
                                    <select class="form-select" id="anoCurso" name="anoCurso">
                                        <option value="" disabled selected>Escolha o Ano</option>
                                        <option value="1">1º Ano</option>
                                        <option value="2">2º Ano</option>
                                        <option value="3">3º Ano</option>
                                        <option value="4">4º Ano</option>
                                        <option value="5">5º Ano</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="numEstudante" class="text-muted form-label form-label-sm">Número de
                                        Estudante</label>
                                    <input type="text" class="form-control" id="numEstudante" name="numEstudante"
                                        placeholder="Número de Estudante" />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="docComprovativo" class="text-muted form-label form-label-sm R">Documento
                                        Comprovativo</label>
                                    <input type="file" class="form-control form-control-sm" id="docComprovativo"
                                        name="docComprovativo" accept=".pdf" />
                                </div>

                                <!-- Layout para imagem e Identificação de Ideia -->
                                <div class="col-lg-4 mb-4 w-100 d-none">
                                    <div class="d-none d-lg-block mt-4">
                                        <img class="img-fluid" src="./assets/img/Solution_mindset.png" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg mb-4">
                                <h4>Identificação de Ideia</h4>
                                <div class="form-row">
                                    <div class="form-group my-3">
                                        <label for="ideia" class="text-muted form-label form-label-sm R">Titulo da
                                            Ideia</label>
                                        <input type="text" class="form-control " id="ideia" name="ideia"
                                            placeholder="Qual o nome da Ideia/Projeto" required>
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="setor" class="text-muted form-label form-label-sm R">Setor / Área de
                                            Negócio</label>
                                        <select id="setor" name="setor" class="form-select form-control" required>
                                            <option value="" disabled selected>Escolha um setor</option>
                                            <option>Tecnologia da Informação (TI)</option>
                                            <option>Finanças e Bancos</option>
                                            <option>Saúde</option>
                                            <option>Educação</option>
                                            <option>Manufatura e Indústria</option>
                                            <option>Agricultura e Agroindústria</option>
                                            <option>Varejo e Comércio</option>
                                            <option>Energia e Recursos Naturais</option>
                                            <option>Construção e Imobiliário</option>
                                            <option>Transporte e Logística</option>
                                        </select>
                                        <div class="form-group form-floating">
                                            <textarea class="form-control form-control-sm control-sm mt-3" id="desc"
                                                name="desc" placeholder="" style="height: 10.5em" required></textarea>
                                            <label for="desc">Descrição do conceito/objectivo da solução proposta
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <dv class="form-row">
                                    <div class="form-group my-3 mb-4">
                                        <div class="form-group fieldset">
                                            <label for="estado" class="form-label form-label-sm text-muted R">Estado /
                                                Fase do Projeto</label>
                                            <select id="estado" name="estado" class="form-select" required>
                                                <option value="" disabled selected>Escolha a Fase do Projeto</option>
                                                <option>Inicial</option>
                                                <option>Planeamento</option>
                                                <option>Prototipagem</option>
                                                <option>Fase de Desenvolvimento</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-floating">
                                            <textarea class="form-control form-control-sm control-sm mt-3" id="details"
                                                name="details" placeholder="" style="height: 10em" required></textarea>
                                            <label for="details" class="form-label form-conrol-sm">
                                                Detalhes sobre o estado da proposta</label>
                                        </div>
                                    </div>
                                </dv>
                                <dv class="form-row mt-5">
                                    <h4 class="my-3">Anexar Apresentação</h4>
                                    <div class="mb-3 mt-4">
                                        <label for="" class="form-label form-label-sm text-muted R">Documento de
                                            Apresentação</label>
                                        <input type="file" class="form-control form-control-sm" id="docApresentacao"
                                            name="docApresentacao" placeholder="" aria-describedby="fileHelpId"
                                            accept=".pdf, .docx" required />
                                        <div id="fileHelpId" class="form-text"></div>
                                    </div>
                                    <div class="mb-3 mt-4">
                                        <label for="videoApresentacao" class="form-label form-label-sm text-muted">Video
                                            de Apresentação (pitch 2 min)</label>
                                        <input type="file" class="form-control form-control-sm" id="videoApresentacao"
                                            name="videoApresentacao" placeholder="" aria-describedby="fileHelpId"
                                            accept="video/*" maxlength="25000000" />
                                        <div id="fileHelpId" class="form-text">Opcional</div>
                                    </div>



                                </dv>

                                <div class="form-check mt-5 justify-content-center">
                                    <input name="accept" class="form-check-input" type="checkbox" value="" id="" />
                                    <label class="form-check-label" for="">
                                        Concordo com as <a href="politicas.php">politicas</a> e os <a
                                            href="termos.php">termos</a> de uso dos dados para submissão.
                                    </label>
                                </div>
                                <div class="col-12 text-center mt-2">
                                    <button type="submit" class="btn btn-primary">Submeter Ideia</button>
                                </div>

                                <div class="col-lg-4 mb-4 w-100 user-select-none" style="pointer-events:none">
                                    <div class="d-none d-lg-block mt-4" >
                                        <img class="img-fluid" src="./assets/img/Solution_mindset.png" alt="">
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>


    <script>
        $(document).ready(function(){

            $("#myInput").on("keypress", function(e) {
                // Check if the character is a digit (0-9)
                if (e.which < 48 || e.which > 57) {
                    // If not a digit, prevent default behavior (character insertion)
                    e.preventDefault();
                }
            });

            $.validator.addMethod("unicvEmail", function (value, element) {
                return this.optional(element) || /.+@.*unicv.edu.cv$/.test(value);
            }, "Por favor, insira um e-mail institucional da UNICV.");

            $.validator.addMethod("telephone", function (value, element) {
                let regex = /^(?:(\+|00)\d{1,3}\s)?\d{7,20}$/;  // eu que fiz isso hein, to bom kkkkkk
                return this.optional(element) || regex.test(value);
            }, "Insira um numero de telefone valido");

            // $.validator.addMethod("selectOptions", function(value, element) {
            // const selectedIndex = element.selectedIndex;
            // return selectedIndex !== 0;
            // }, "Por favor, selecione qual é o seu ano letivo");

            $("#formulario").validate({
                rules: {
                    endereco: {
                        required: true
                    },
                    nif: {
                        required: true,
                        digits: true,
                        minlength: 9
                    },
                    docIdentifi: {
                        required: true,
                    },
                    telefone:{
                        telephone: true
                    },
                    telemovel:{
                        telephone: true
                    },
                  docComprovativo:{
                        required: true
                    },
                    // Ideia
                    ideia:{
                        required: true,
                        maxlength: 40
                    },
                    setor:{
                        required: true
                    },
                    desc:{
                        required: true,
                        minlength: 20,
                        maxlength: 500
                    },
                    estado:{
                        required: true
                    },
                    details:{
                        required: true,
                        maxlength: 500
                    },
                    docApresentacao:{
                        required: true
                    },
                    accept:{
                        required: true
                    }

                },
                messages: {
                    nif: {
                        required: "Por favor, informe seu NIF",
                        digits: "NIF inválido",
                        minlength: "Verifique seu numero NIF"
                    },
                    endereco:{
                        required: "Por favor, informe sua morada atual"
                    },
                    docIdentifi: {
                        required: "Documento de identificaçao, CNI/BI em formato PDF",
                    },
                    docComprovativo:{
                        required: "Por favor, submeta o comprovativo de estudante"
                    },
                    areaFormacao:{
                        required: "Informe o seu curso ou area de formação"
                    },
                    ideia:{
                        required: "Informe um titulo para sua Ideia de Projeto",
                        maxlength: "Tamanhamo Máximo atingido, por favor, seja direto no titulo"
                    },
                    setor:{
                        required: "Selecione o setor que mais se adequa à sua ideia"
                    },
                    desc:{
                        required: "Descreva sua ideia de projeto de forma introdutória",
                        minlength: "Descrição muito curto",
                        maxlength: "Numero de caracteres maximo atingido"
                    },
                    estado:{
                        required: "Selecione o estado/fase atual que seu projeto se encontra"
                    },
                    details:{
                        required: "Descreva como você pretende implementar sua ideia de projeto",
                        maxlength: "Excedeu o tamanho máximo"
                    },
                    docApresentacao:{
                        required: "Submeta o arquivo de apresentação do seu projeto (Importante!)"
                    }



                }
            });


            // NOT USED, JUST FOR TEST V
           

            $("#videoInput").on("change", function(e) {
            // Get the selected file
            const file = e.target.files[0];

            // Check if a file is selected
            if (!file) {
                return; // No file selected, nothing to validate
            }

            // Get the maximum allowed size in bytes (adjust as needed)
            const maxSize = 10 * 1024 * 1024; // 10 Megabytes

            // Check if file size exceeds the limit
            if (file.size > maxSize) {
                alert("Video size exceeds the maximum limit of " + maxSize / (1024 * 1024) + "MB. Please select a smaller video.");
                // Optionally clear the file input value to prevent upload attempt
                $(this).val("");
                return false; // Prevent form submission (if applicable)
            }

            // File size is valid, proceed with upload or further processing
            });

            $("#videoInput").on("change", function(e) {
                // ... (file size validation code as before)

                // Create a temporary video element
                const video = document.createElement("video");

                // Load the selected file as the video source
                video.src = URL.createObjectURL(file);

                // Wait for the video metadata to load
                video.addEventListener("loadedmetadata", function() {
                    const duration = video.duration;
                    const maxDuration = 60 * 60; // 1 hour in seconds (adjust as needed)

                    // Check if the duration exceeds the limit
                    if (duration > maxDuration) {
                    alert("Video duration exceeds the maximum limit of 1 hour. Please select a shorter video.");
                    // ... (rest of the code)
                    }
                });
            });

        });
    </script>



    <script>
        /**
         * Validacao do formulario, do bootstrap
         */
        (function () {
            'use strict'
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

    <style>
        .invalid {
            border-color: red;
        }
    </style>

    <script src="assets/js/validations.js"></script>


</body>

</html>

<?php includeTemplate('footer') ?>