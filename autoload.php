<?php

spl_autoload_register(function ($nombre) {

    $url = "/var/www/html/talent/Controller/{$nombre}.php";
    if(!file_exists($url)){
        $url = "/var/www/html/talent/Model/{$nombre}.php";
    }

    include $url;
});

function error($mensaje){
    header('HTTP/1.1 500 Error con la Peticion');
    echo json_encode(['response' => $mensaje]);
    exit;
}

function mensaje($mensaje){
    header('HTTP/1.1 404 Advertencia');
    echo json_encode(['response' => $mensaje]);
}

function respuesta($mensaje){
    header('HTTP/1.1 200 Peticion Satisfactoria');
    echo json_encode(['response' => $mensaje]);
    exit;
}

function render($vista, $array = []){
    ob_start();
    extract($array);
    include $vista;
    $datos = ob_get_contents();
    return $datos;
}