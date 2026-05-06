<?php

/**
 * FUNCTIONS.PHP
 * Responsavel por conter uncoes que facilitara na reutilizacao do codigo, com funcoe úteis.
 */

/**
 * Redirect
 * funcao para redirecionar ã uma pagina, pode ou nao passar uma menssagem para ela
*/
function redirect($url = 'PHP_SELF', $mensagem = null)
{
    $url = $url != 'PHP_SELF' ? $url : $_SERVER['PHP_SELF'];
    $mensagem = ($mensagem != null) ? "?mensagem=$mensagem" : '';
    header("location: $url" . $mensagem);
    exit();
}

// funcao para incuir um template na pasta template, se nao existir imprime que nao foi encontrado
function includeTemplate($templateName)
{
    $template = PATH_TEMPLATES . "/$templateName.php";
    if (file_exists($template)) {
        require_once $template;
    } else {
        echo "Template $templateName não encontrado.";
    }
}

/**
 * Funcão que permite incluir um script php dentro da pasta scripts no backend
 * @param $scriptfile passe o nome com a extensao do arquivo para ser incluirdo
 * @return bool o script php aonde foi chamado, nao acontece nada caso o script nao existir
 */
function scripts($fscriptfile)
{
    $script = __DIR__ . "/scripts/$fscriptfile";
    if (file_exists($script)) {
        include_once $script;
    }
    return false;
}

function foto_candidato($nomeFoto)
{
    $img = PATH_UPLOADS . "/fotos_cand/$nomeFoto";
    if ($nomeFoto != '' && file_exists($img)) {
        return fotos_cand . $nomeFoto;
    }
    return assets . '/img/guest.png';
}

function foto_supervisor($nomeFoto)
{
    $img = PATH_UPLOADS . "/fotos_superv/$nomeFoto";
    if ($nomeFoto != '' && file_exists($img)) {
        return fotos_superv . $nomeFoto;
    }
    return assets . '/img/guest.png';
}

function foto_coordenador($nomeFoto)
{
    $img = PATH_UPLOADS . "/fotos_coord/$nomeFoto";
    if ($nomeFoto != '' && file_exists($img)) {
        return fotos_coord . $nomeFoto;
    }
    return assets . '/img/guest.png';
}

function foto_ativo($nomeFoto)
{
    $img = PATH_UPLOADS . "/fotos_ativ/$nomeFoto";
    if ($nomeFoto != '' && file_exists($img)) {
        return fotos_ativ . $nomeFoto;
    }
    return assets . '/img/no_img.png';
}

/**
 * FUncao que dado uma foto, ela procura nas pastas de upload para encontrar as determinadas fotos
 * Se nao encontrar a foto, a foto padrao é retornada
 */
function foto_utilizador($nomeFoto)
{
    if ($nomeFoto != '') {
        if (file_exists(PATH_UPLOADS . "/fotos_cand/$nomeFoto")) {
            $img = fotos_cand . $nomeFoto;
        } elseif (file_exists(PATH_UPLOADS . "/fotos_coord/$nomeFoto")) {
            $img = fotos_coord . $nomeFoto;
        } elseif (file_exists(PATH_UPLOADS . "/fotos_superv/$nomeFoto")) {
            $img = fotos_superv . $nomeFoto;
        }
    }
    return $img ?? assets . '/img/guest.png';
}


/**
 * retorna o documento do projeto/ideia submetido pelo utilizador
 */
function documento_projeto($nome)
{
    return docs_projeto . $nome;
}

/**Reforna o video de apresentação do projeto */
function video_apresentacao($video){
    return video_apresentacao . "/$video";
}


/**
 * retorna o arquivo ZIP/RAR/ compactado do projeto submetido pelo candidato
 * Para usar depois da aprovacao da ideia
 */
function arquivo_projeto($nome)
{
    return docs_projeto . $nome;
}

/**
 * funcao para eliminar a foto do usuario
 */
function eliminarFotoUsuario(string $foto)
{
    if ($foto != '') {
        if (!file_exists($img = PATH_UPLOADS . "/fotos_cand/$foto")) {
            if (!file_exists($img = PATH_UPLOADS . "/fotos_coord/$foto")) {
                if (!file_exists($img = PATH_UPLOADS . "/fotos_superv/$foto")) {
                    if (!file_exists($img = PATH_UPLOADS . "/fotos_ativ/$foto")) {
                        return false;
                    }
                }
            }
        }
        unlink($img);
    }
    return true;
}

// funcao para retornar um arquivo na pasta uploads
function uploads($filename)
{
    return uploads . "/$filename";
}

/**
 * Emviar uma menssagem flash para aparecer apenas uma vez
 * uma vez que a menssagem é mostrada, ao recaregar a pagina ela nao aparece mais
 * @return string|void
 * uso:
 * crie uma flash message:  flashMessage('minha menssagem');
 * depois use a flash message: $msg = flashMessage();
 */
function flashMessage(string $message = null, $status = null)
{
    if ($message != null) {
        $_SESSION['flash'] = $message;
        if($status){
            flashStatus($status);
        }
    } else if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
    }
    return $flash ?? '';
}

/**
 * Para utilizar no sweet alert a fim de mostrar o icone coreto para 'error', waring, info, success
 */
function flashStatus(string $status = null)
{
    if ($status != null) {
        $_SESSION['flashStatus'] = $status;
    } else if (isset($_SESSION['flashStatus'])) {
        $flash = $_SESSION['flashStatus'];
        unset($_SESSION['flashStatus']);
    }
    return $flash ?? '';
}