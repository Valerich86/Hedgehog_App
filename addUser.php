<?php

require_once 'login.php';

try{
    $pdo = new PDO($attr,$user,$pass,$opts);
}
catch(\PDOException $e){
    throw new \PDOException($e -> getMessage(), (int)$e -> getCode());
}

$forename = $surname = $username = $password = $age = $email = "";

if(isset($_POST['forename']))
    $forename = sanitizeString($_POST['forename']);
if(isset($_POST['surname']))
    $surname = sanitizeString($_POST['surname']);
if(isset($_POST['username']))
    $username = sanitizeString($_POST['username']);
if(isset($_POST['password']))
    $password = sanitizeString($_POST['password']);
if(isset($_POST['age']))
    $age = sanitizeString($_POST['age']);
if(isset($_POST['email']))
    $email = sanitizeString($_POST['email']);

    $fail = validateForename($forename);
    $fail .= validateSurname($surname);
    $fail .= validateUsername($username);
    $fail .= validatePassword($password);
    $fail .= validateAge($age);
    $fail .= validateEmail($email); 

    echo "<!DOCTYPE html>\n<html><head><title>Пример формы</title>";
    if ($fail == ''){
        echo "</head><body>Проверка формы прошла успешно: <br> 
        $forename, $surname, $username, $password, $age, $email.</body></html>";

        exit;
    }
    
    function validateForename($field){
        return ($field == "") ? "Не введено имя.<br>" : "";
    }
    
    function validateSurname($field){
        return ($field == "") ? "Не введена фамилия.<br>" : "";
    }
    
    function validateUsername($field){
        if ($field == "") return "Не введено имя пользователя.<br>";
        else if (strlen($field)<5)
            return "В имени пользователя должно быть не менее 5 символов.<br>";
        else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
            return "В имени пользователя имеются недопустимые символы.<br>";
        return "";
    }
    
    function validatePassword($field){
        if ($field == "") return "Не введен пароль.<br>";
        else if (strlen($field)<6)
            return "В пароле должно быть не менее 6 символов.<br>";
        else if (!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field))
            return "В пароле должны быть использованы буквы латиницы и цифры.<br>";
        return "";
    }
    
    function validateAge($field){
        if ($field == "") return "Не введен возраст.<br>";
        else if ($field < 18)
            return "Возраст должен быть больше или равен 18.<br>";
        return "";
    }
    
    function validateEmail($field){
        if ($field == "") return "Не введен адрес электронной почты.<br>";
        else if (!((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $field))
            return "Электронный адрес имеет неверный формат.<br>";
        return "";
    }
    
function sanitizeString($var){
    
    // полностью очистить от html
    $var = strip_tags($var);
    // заменить угловые скобки на безопасные символы
    $var = htmlentities($var);
    return $var;
}

function sanitizeMySQL($pdo, $var){
    $var = $pdo->quote($var);
    $var = sanitizeString($var);
    return $var;
}

echo <<<_END

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pacifico&display=swap" rel="stylesheet">
    <title>Form testing</title>
</head>
<body>
    <div class="canvas">
    <div class="title" >Please sign in!</div>
    <table class="signup" cellpadding="2" 
        cellspacing="5" bgcolor="#eeeeee">
        <th colspan="2" align="center"><h1>Registration form</h1></th>
        <tr><td colspan="2" align="center">
            <p id="error1"></p>
        </td></tr>
        <tr><td colspan="2"> 
            <p><i id="error2"></i></p>
        </td></tr>
        <form method="post" class="form" action="addUser.php" onsubmit="return validate(this)">
            <tr><td>Forename</td><td><input type="text" maxlength="32" name="forename" value="$forename"</td></tr>
            <tr><td>Surname</td><td><input type="text" maxlength="32" name="surname"  value="$surname"></td></tr>
            <tr><td>Username</td><td><input type="text" maxlength="16" name="username"  value="$username"></td></tr>
            <tr><td>Password</td><td><input type="text" maxlength="12" name="password"  value="$password"></td></tr>
            <tr><td>Age</td><td><input type="text" maxlength="3" name="age"  value="$age"></td></tr>
            <tr><td>Email</td><td><input type="text" maxlength="64" name="email"  value="$email"></td></tr>
            <tr><td><td><tr>
            <tr><td><td><tr>
            <tr><td colspan="2" align="center"><input type="submit" class="btn" value="Submit"></td></tr>
        </form> 
    </table><div>
    <script src="script.js"></script>
</body>
</html>

_END;

