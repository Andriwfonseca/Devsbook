<?php
require 'config.php';
require 'models/Auth.php';

//pegar os dados do input
$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate');

if ($name && $email && $password && $birthdate){
    $auth = new Auth($pdo,$base);

    $birthdate = explode('/',$birthdate); //vai separar em um array de 3 itens

    if(count($birthdate) != 3){ //verifica se tem 3 itens no array
        $_SESSION['flash'] = 'Data de nascimento inválida!';
        header("Location: ".$base."/signup.php");
        exit;
    }
    $birthdate = $birthdate[2]."-".$birthdate[1]."-".$birthdate[0];

    //agora vamos transformar a data para ms , pra saber se é válida mesmo
    if(strtotime($birthdate) === false){
        $_SESSION['flash'] = 'Data de nascimento inválida!';
        header("Location: ".$base."/signup.php");
        exit;
    }
    if($auth->emailExists($email) === false){

        $auth->registerUser($name,$email,$password,$birthdate);
        header("Location: ".$base);
        exit;
    }else{
        $_SESSION['flash'] = 'E-mail já cadastrado!';
        header("Location: ".$base."/signup.php");
        exit;
    }

}

$_SESSION['flash'] = 'Campos não enviados!';
header("Location: ".$base."/signup.php");
exit;

function validateDate($data){
    
    return $data;
}