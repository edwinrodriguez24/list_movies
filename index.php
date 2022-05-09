<?php

require_once('autoload.php');

session_start();

$action = filter_input(INPUT_POST, 'action');
$action = (empty($action)) ? '' : $action;

if(isset($_SESSION['user']) and !empty($_SESSION['user']) and empty($action) and !in_array($action, ['registerForm', 'loginForm'])){
    $action = 'listMovies';
}
elseif(empty($action)){
    $action = 'loginForm';
}

$UserController = new UserController();
$UserController->$action();