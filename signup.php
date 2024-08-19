<?php
require_once 'header.php';

$error = $user = $pass = $hash = "";
if(isset($_SESSION['user'])) destroySession();

if(isset($_POST['user'])){
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if($user == "" || $pass == "")
        $error = "Заполните все поля<br><br>";
    else if (strlen($pass)<6)
        $error = "В пароле должно быть не менее 6 символов.<br>";
    else if (!preg_match("/[a-z]/", $pass) || !preg_match("/[A-Z]/", $pass) || !preg_match("/[0-9]/", $pass))
        $error = "В пароле должны быть использованы буквы латиницы и цифры.<br>";
    else{
        $result = queryMysql("SELECT * FROM members WHERE user='$user'");

        if($result->rowCount())
            $error = "Это имя уже используется<br><br>";
        else{
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            queryMysql("INSERT INTO members (user, pass) VALUES('$user', '$hash')");
            $loggedin = TRUE;
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            echo "<script>window.location.replace('profile.php?&r=$randstr')</script>";
            echo "</div></body></html>"; exit();
        }
    }
}

echo <<<_END
    <form action="signup.php?r=$randstr" method="post">
            <div data-role="fieldcontain">
                <label for=""></label>
                <span class="error">$error</span>
            </div>
            <div data-role="fieldcontain">
                <label for=""></label>
                Введите Ваши данные для регистрации:
            </div>
            <div data-role="fieldcontain">
                <label for="">Имя пользователя</label>
                <input type="text" maxlength="16" name="user" value="$user" onblur="checkUser(this)">
                <label for=""></label><div id="used">&nbsp;</div>
            </div>
            <div data-role="fieldcontain">
                <label for="">Пароль</label>
                <input type="password" maxlength="16" name="pass" value="$pass" onblur="checkUser(this)">
            </div>
            <div data-role="fieldcontain">
                <label for=""></label>
                <input data-transition="slide" type="submit" value="Регистрация">
            </div>
        </form>
    </div>
</body>
</html>
_END;
?>