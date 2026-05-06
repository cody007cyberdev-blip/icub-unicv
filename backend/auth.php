<?php

/**
 * AUTH.PHP
 * Responsavel por facilitar a verificao de autentificaao e permicao a paginas
 * recebe um parametro OPCIONAL $tipo caso queremos verificar se o login é de um dado usuario
 * ainda tem o parametro REDIRECT (true/false) para decidir se, caso nao estiver loga, deve redirectionar ou somente reti=ornar true/false
 */

function verificarAutenticacao($tipo = null, $redirect = true)
{
   if (isset($_SESSION['usuario'])) {
      // Usa os dados da sessão atual
      $tipo_usuario = $_SESSION['usuario']['tipo'] ?? '';

      if ($tipo != null) {
         if ($tipo_usuario != $tipo) {
            if ($redirect == false) {
               return false;
            }
            header('HTTP/1.1 401 Unauthorized', true, 401);
            includeTemplate('../401');
            exit;
            // unathourized header
         }
      }
      return true;
   } elseif ($redirect == false) {
      return false;
   } else {
      // Redireciona para a página de login se o usuário não estiver autenticado
      redirect(BASE_URL . '/login.php');
   }
}

/**
 * Esta funcao retorna o enderenco completo da imagem do utilizador logado,
 * Se nao tiver foto, coloca padrao.jpg por defeito
 */
function fotoUsuario()
{
   $foto_padrao = isset($_SESSION['usuario']['foto']) ? $_SESSION['usuario']['foto'] : 'padrao.jpg';
   $tipo_usuario = isset($_SESSION['usuario']['tipo']) ? $_SESSION['usuario']['tipo'] : '';
   switch ($tipo_usuario) {
      case 'coordenador':
         $foto_padrao = fotos_coord . "/$foto_padrao";
         break;
      case 'candidato':
         $foto_padrao = fotos_cand . "/$foto_padrao";
         break;
      case 'supervisor':
         $foto_padrao = fotos_superv . "/$foto_padrao";
         break;
      default:
         $foto_padrao = assets . "/img/guest.jpg";
   }
   return $foto_padrao;
}