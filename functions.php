<?php

$host = 'localhost';
$data = 'hedgehog';
$user = 'Valerich';
$pass = 'aquamarine';
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts = 
[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try{
    $pdo = new PDO($attr,$user,$pass,$opts);
}
catch(\PDOException $e){
    throw new \PDOException($e -> getMessage(), (int)$e -> getCode());
}

function queryMysql($query){
    global $pdo;
    return $pdo->query($query);
}

function createTable($name, $query){
    queryMysql("create table if not exists $name($query)");
    echo "Таблица '$name' создана или уже существует<br>";
}

function destroySession(){
    $_SESSION=array();

    if(session_id() != "" || isset($_COOCKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');

        session_destroy();
}

function sanitizeString($var){
    global $pdo;
    $var = strip_tags($var);
    $var = htmlentities($var);

    $res = $pdo->quote($var);
    return str_replace("'", "", $res);
}

function showProfile($user){
    global $pdo; 
    if (file_exists('user.jpg'))
        echo "<img src='user.jpg' alt='' style='float: left;' id='user-img'>";

    $res = $pdo->query("SELECT * FROM profiles WHERE user='$user'");

    while($row = $res->fetch()){
        echo (stripslashes($row['text']) . "<br style='clear: left;'><br>");
    }
    if (!$res->rowCount())
        echo "Здесь пока ничего нет. <br>Добавьте информацию о себе во вкладке <span class='whisper'>'редактировать'.</span>";
}
?>