<?php
session_start();

echo <<<_INIT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.mobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <link rel="stylesheet" href='styles.css'>
    <script src="javascript.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.2.4.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script></script>
_INIT;

require_once 'functions.php';

$userstr = 'Добро пожаловать в Hedgehog!';
$randstr = substr(md5(rand()), 0, 7);

if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr = "Выполнен вход под именем $user";
}
else $loggedin = FALSE;

echo <<<_MAIN
<title>Hedgehog</title>
</head>
<body>
    <div data-role="page">
        <div data-role="header">
            <div id="logo" class="center"><img src="res/hedgehog .png" id='hedgehog' alt='hedgehog'></div>
            <div class="username">$userstr</div>
        </div>
        <div data-role="content">

_MAIN;

if($loggedin){
    echo <<<_LOGGEDIN
    <div class="center">
        <a data-role="button" data-inline="true" data-icon="home" data-transition="slide" 
        href="members.php?view=$user&r=$randstr">Профиль</a>
        <a data-role="button" data-inline="true" data-icon="user" data-transition="slide" 
        href="members.php?r=$randstr">Участники</a>
        <a data-role="button" data-inline="true" data-icon="heart" data-transition="slide" 
        href="friends.php?r=$randstr">Друзья</a>
        <a data-role="button" data-inline="true" data-icon="mail" data-transition="slide" 
        href="messages.php?r=$randstr">Чат</a>
        <a data-role="button" data-inline="true" data-icon="edit" data-transition="slide" 
        href="profile.php?r=$randstr">Редактировать</a>
        <a data-role="button" data-inline="true" data-icon="action" data-transition="flip" 
        href="logout.php?r=$randstr">Выход</a>
    </div>
    _LOGGEDIN;
}
else{
    echo <<<_GUEST
        <div class="center">
            <a data-role="button" data-inline="true" data-icon="home" data-transition="flip" 
            href="index.php?r=$randstr">Домашняя</a>
            <a data-role="button" data-inline="true" data-icon="plus" data-transition="flip" 
            href="signup.php?r=$randstr">Регистрация</a>
            <a data-role="button" data-inline="true" data-icon="check" data-transition="flip" 
            href="login.php?r=$randstr">Войти</a>
        </div>
        <p class="info">(Для использования приложения необходимо пройти регистрацию)</p>
    _GUEST;
}
            


?>