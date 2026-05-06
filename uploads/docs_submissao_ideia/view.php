<?php
// view.php

include '../../backend.php';

$id_candidatura = $_GET['id'] ?? null; // Captura o ID da candidatura da URL

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver vídeos de Apresentação de ideia</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .content-wrapper {
            width: 100%;
            max-width: 960px;
            margin: auto;
            padding: 20px;
        }

        .btn-voltar {
            display: inline-block;
            text-decoration: none;
            background-color: #006CFF;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }

        .btn-voltar:hover {
            background-color: #0056b3;
        }

        .alb {
            margin-top: 20px;
        }

        video {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <a href="<?= url_base ?>/coord/candidaturas.php" class="btn-voltar">Voltar</a>

        <div class="alb">
            <?php
            try {
                if ($id_candidatura) {
                    // Consulta SQL para buscar o vídeo específico da candidatura
                    $sql = "SELECT video FROM tbl_identificacao_ideia WHERE id_candidatura = :id_candidatura";
                    $stmt = $database->prepare($sql);
                    $stmt->bindParam(':id_candidatura', $id_candidatura, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $video = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
                        <video src="video_apresentacao/<?= htmlspecialchars($video['video']) ?>" controls></video>
                    <?php
                    } else {
                        echo "<h1>Vídeo não encontrado para esta candidatura.</h1>";
                    }
                } else {
                    echo "<h1>ID da candidatura não especificado.</h1>";
                }
            } catch (PDOException $e) {
                echo "<h1>Erro na execução da consulta: " . $e->getMessage() . "</h1>";
            }
            ?>
        </div>
    </div>
</body>

</html>
