<?php
require 'config.php';   
require 'models/Auth.php';

//auth é um objeto especifico para fazer a autenticação
$auth = new Auth($pdo,$base);
$userInfo = $auth->checkToken();

echo 'index';