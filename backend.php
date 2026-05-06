<?php

Define('RUN_TESTS', false);

/**
 * Este Arquivo deve ser incuido em todas as paginas, excepto templates.
 * Responavel por carregar todo o backend do projeto.
 */

//** $url_base */
const url_base = 'http://localhost/icub-unicv/'; 
 
 //* Inicar Sessão */
session_start();

require_once __DIR__ . '/backend/config.php';
require_once __DIR__ . '/backend/autoload.php';
require_once __DIR__ . '/backend/dbconnection.php';
require_once __DIR__ . '/backend/functions.php';
require_once __DIR__ . '/backend/auth.php';


//**  Preparar variavel $database para usar*/ é a mesma que conexion
$database = Database::getConnection();
$conexion = getDatabaseConnection();

//** Nome da Variavel da sesson que contem todas as informacoes de login do usuario */
$session_usuario = $_SESSION["usuario"] ?? '';

//** Configuracoes de data e hora */
date_default_timezone_set('Atlantic/Cape_Verde'); 


if(RUN_TESTS ?? false):
/**
 * todo: AREA DE EXECUTÇÃO DE TESTES
 */



endif;