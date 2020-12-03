<?php
require_once 'dao/UserDaoMysql.php';
/*Verifica se tem sessão, e se a sessão tem algum token
e verificar se o token tem no banco de dados
e pertence a algum usuario*/
class Auth{
    private $pdo;
    private $base;
    private $dao;

    public function __construct(PDO $pdo, $base){
        $this->pdo = $pdo;
        $this->base = $base;
        $this->dao = new UserDaoMySql($this->pdo);
    }
    public function checkToken(){
        //verifica se tem uma sessao com nome token, e se ela nao esta vazia
        if (!empty($_SESSION['token'])){
            
            $token = $_SESSION['token'];
            //ai manda essa sessao para o dao, para verificar no banco de dados se existe esse token
            //se existir ele retorna o usuario com esse token
            $user = $this->dao->findByToken($token);
            //se existir esse token, ele retorna o usuario, caso contrario vai pra pagina de login
            if($user){
                return $user;
            }
        }else{
            header("Location: ".$this->base."/login.php");
            exit;
        }

       
    }
    public function validateLogin($email,$password){
        //verifica se existe esse email
        $user = $this->dao->findByEmail($email);
        if($user){
            
            if(password_verify($password, $user->password)){
                //se o password bater com o do email, vai gerar um token e salvar no banco de dados
                $token = md5(time().rand(0,9999));

                $_SESSION['token'] = $token;
                $user->token = $token;
                $this->dao->update($user);

                return true;
            }
        }

        return false;
    }
    public function emailExists($email){        
        return $this->dao->findByEmail($email) ? true : false;
    }
    public function registerUser($name,$email,$password,$birthdate){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,9999));

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->token = $token;

        $this->dao->insert($newUser);

        $_SESSION['token'] = $token;
    }
}