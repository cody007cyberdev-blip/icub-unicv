<?php

/**
 **CONFIG.PHP
 * Responsavel por definir todas as contantes que serão utilizados para faciliar e evitar manutenções
 * devido a eventos como: arquivo não encontrado, erro de diretorio, algo do genero
 */

//** URL BASE */
const BASE_URL = 'http://localhost/icub-unicv';

const root = BASE_URL . '/';
const coordenador = BASE_URL . '/coord';
const assets = BASE_URL . '/assets';
const uploads = BASE_URL . '/uploads';
const docs_ideia = uploads . '/docs_submissao_ideia';

// Está vermelho por causa da barra no final..
const fotos_coord = uploads . '/fotos_coord/';
const fotos_proj = uploads . '/fotos_proj/';
const fotos_cand = uploads . '/fotos_cand/';
const fotos_superv = uploads . '/fotos_superv/';
const docs_projeto = uploads . '/docs_projetos/';
const foto_projeto = uploads . '/fotos_projetos/';
const fotos_ativ = uploads . '/fotos_ativ/';
const fotos = uploads . '/fotos/';
const video_apresentacao = docs_ideia . '/video_apresentacao/';
const doc_identificacao = docs_ideia . '/cni/';
const doc_apresentacao = docs_ideia . '/documento_apresentacao/';
const comprovativo_estudante = docs_ideia . '/comprovativo_estudante/';


//** PATHS */
define('PATH_TEMPLATES', __DIR__ . "/../templates");
define('PATH_UPLOADS', __DIR__ . "/../uploads");
define('PATH_PROJETO', PATH_UPLOADS . "/projeto");

//** DATABASE */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_icub');

//** SESSION */
const session_login = 'usuario';