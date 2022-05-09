<?php

class UserController extends UserModel{

    private $fileGet = 'https://www.omdbapi.com/?s=avengers&apiKey=fc59da33';
    private $fileSet = __DIR__.'/../Db/dbMovies.js';

    public function registerUser()
    {
        parent::__construct($_POST);
        $result = $this->registrarUsuario();

        respuesta($result);
    }

    public function loginUser()
    {
        # Definicion de Variables
        $user = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        # Valido la Sesion
        $result = $this->validarLogueo($user, $password);
        if($result == 'ok'){
            $_SESSION['user'] = $user;
        }
        
        respuesta($result);
    }

    public function updateMovieList()
    {
        # Obtento el Arhivo de Peliculas
        $file = file_get_contents($this->fileGet);

        # Actualizo el Archivo
        file_put_contents($this->fileSet, $file);

        respuesta(json_decode($file, true));
    }

    public function searchMovieList()
    {
        # Busco por Titulo
        $searchTitle = function($array){
            $search = filter_input(INPUT_POST, 'searchTitle');
            return strpos(strtolower($array['Title']), $search) !== false;
        };

        # Busco por Rango Minimo
        $dateRangeIni = function($array){
            $ini = intVal(filter_input(INPUT_POST, 'dateRangeIni'));
            $arrayYear = explode('–', $array['Year']);
            return ($arrayYear[0] >= $ini or (isset($arrayYear[1]) and $arrayYear[1] >= $ini));
        };

        # Busco por Rango Maximo
        $dateRangeMax = function($array){
            $max = intVal(filter_input(INPUT_POST, 'dateRangeMax'));
            $arrayYear = explode('–', $array['Year']);
            return ($arrayYear[0] <= $max or (isset($arrayYear[1]) and $arrayYear[1] <= $max));
        };

        # Obtento el Arhivo de Peliculas
        $file = file_get_contents($this->fileSet);
        $array = json_decode($file, true);

        # Find Title
        if(isset($_POST['searchTitle']) and !empty($_POST['searchTitle'])){
            $array['Search'] = array_filter($array['Search'], $searchTitle);
        }

        # Find Min
        if(isset($_POST['dateRangeIni']) and !empty($_POST['dateRangeIni'])){
            $array['Search'] = array_filter($array['Search'], $dateRangeIni);
        }

        # Find Max
        if(isset($_POST['dateRangeMax']) and !empty($_POST['dateRangeMax'])){
            $array['Search'] = array_filter($array['Search'], $dateRangeMax);
        }

        # Order By
        if(isset($_POST['sortBy']) and !empty($_POST['sortBy'])){

            $order = filter_input(INPUT_POST, 'sortBy');
            $column = array_column($array['Search'], $order);
            array_multisort($column, SORT_ASC, $array['Search']);
        }

        respuesta($array);
    }

    public function registerForm()
    {
        render(__DIR__.'/../View/register.html');
    }

    public function loginForm()
    {
        session_destroy();
        render(__DIR__.'/../View/login.html');
    }

    public function listMovies()
    {
        render(__DIR__.'/../View/movieList.html');
    }
}
