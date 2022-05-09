<?php 

class UserModel {

    private $id;
    private $userName;
    private $phone;
    private $email;
    private $password;
    private $error = [];
    private $db = __DIR__.'/../Db/dbJson.js';


    public function __construct($cliente = [])
    {
        if(isset($cliente['username'])){
            $this->setUserName($cliente['username']);
            $this->setPhone($cliente['phone']);
            $this->setEmail($cliente['email']);
            $this->setPassword($cliente['password'], $cliente['repassword']);
        }
    }

    /**
     * Get the value of nombre
     */ 
    public function getError(){
        return $this->error;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setError($error){

        $this->error[] = $error;
    }

    /**
     * Get the value of nombre
     */ 
    public function getUserName(){
        return $this->userName;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setUserName($userName){

        if(empty($userName)){
            $this->setError("Please fill username");
        }
        elseif(!preg_match("/^[a-zA-Z\sñÑ]+$/", $userName)){
            $this->setError("Only accept letters");
        }
        else{
            $this->userName = $userName;
        }
    }

    /**
     * Get the value of apellido
     */ 
    public function getPhone(){
        return $this->phone;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setPhone($phone){

        if(empty($phone)){
            $this->setError("Please fill phone");
        }
        elseif(!preg_match("/^\+[0-9]{8}+$/", $phone)){
            $this->setError("Only accept (8) numbers and starts by +");
        }
        else{
            $this->phone = $phone;
        }
    }

    /**
     * Get the value of apellido
     */ 
    public function getEmail(){
        return $this->email;
    }

    /**
     * Set the value of apellido
     *
     * @return  self
     */ 
    public function setEmail($email){

        if(empty($email)){
            $this->setError("Please fill email address");
        }
        elseif(!preg_match("/^[A-z0-9\._-]+@[A-z0-9][A-z0-9-]*(\.[A-z0-9_-]+)*\.([A-z]{2,6})$/", $email)){
            $this->setError("The email structure is invalid");
        }
        else{
            $this->email = $email;
        }
    }

    /**
     * Get the value of usuario
     */ 
    public function getPassword(){
        return $this->password;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */ 
    public function setPassword($password, $repassword){

        if($password == ''){
            $this->setError("Please fill password");
        }
        elseif($repassword == ''){
            $this->setError("Please fill confirm password");
        }
        elseif($password !== $repassword){
            $this->setError("Password and confirm password are not equal");
        }
        elseif(strlen($password) !== 6){
            $this->setError("Password should be six characters");
        }
        elseif(strpos($password, '.') === false and strpos($password, '-') === false and strpos($password, '*') === false){
            $this->setError("Password should has one of this characters (.), (*), (-)");
        }
        elseif(!preg_match("/[A-Z]/", $password)){
            $this->setError("Password has less one letter uppercase");
        }
        else{
            $this->password = $password;
        }
    }

    private function obtenerBaseJson()
    {
        # Abro el Usuario
        if(file_exists($this->db)){
            $file = file_get_contents($this->db);
            $arrayData = json_decode($file, true);
        }
        else{
            $arrayData = [];
        }

        return $arrayData;
    }

    public function registrarUsuario(){

        # Datos del Nuevo Usuario
        $arrayUsuario = [
            'username' => $this->getUserName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword()
        ];

        if(empty($this->error)){

            # Abro el Usuario
            $arrayData = $this->obtenerBaseJson();

            # Valido si el Usuario Existe
            if(!isset($arrayData[$this->getUserName()])){

                # Añado el Usuario
                $arrayData[$this->getUserName()] = $arrayUsuario;

                # Actualizo el Archivo
                file_put_contents($this->db, json_encode($arrayData));

                return true;
            }
            else{
                return ['Usuario ya registrado!'];
            }
        }
        else{
            return $this->error;
        }
    }

    public function validarLogueo($user, $password)
    {
        # Abro el Usuario
        $arrayData = $this->obtenerBaseJson();
        if(isset($arrayData[$user])){
            if($arrayData[$user]['password'] == $password){
                return 'ok';
            }
            else{
                return 'user or password is incorrect';
            }
        }
        else{
            return 'user not register on our sistem';
        }
    }

}
