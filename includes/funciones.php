<?php
define('TEMPLATES_URL', __DIR__.'/templates');
define('FUNCIONES_URL',__DIR__.'funciones.php');

function incluirTemplate(string $nombre, $inicio = false){
    include TEMPLATES_URL . "/${nombre}.php";
}

function estadoAutenticado():bool{
    session_start();
    $autenticado = $_SESSION['login'];
    if($autenticado){
        return true;
    }
    return false;
}