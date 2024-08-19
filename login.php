<?php
require_once 'header.php';

$error = $user = $pass = "";

if(isset($_POST['user'])){
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if($user == "" || $pass == "")
        $error = "Заполните все поля<br><br>";
    else{
        $result = queryMysql("SELECT * FROM members WHERE user='$user'");
        
        if($result->rowCount() == 0)
            $error = "Данный пользователь не зарегистрирован";
        else{
            $row = $result->fetch();
            if (password_verify($pass, $row['pass'])){
                $_SESSION['user'] = $user;
                $_SESSION['pass'] = $pass;
                // die("<div class='center'>Вы вошли. Теперь
                // <a data-transition='slide' href='members.php?view=$user&r=$randstr'>кликните здесь</a>
                // чтобы продолжить.</div></div></body></html>");
                echo "<script>window.location.replace('members.php?view=$user&r=$randstr')</script>";
                echo "</div></div></body></html>";
                exit();
            }
            else
                $error = "Пароль не верный";
        }
    }
}

echo <<<_END
            <form action="login.php?r=$randstr" method="post">
            <div data-role="fieldcontain">
                <label for=""></label>
                <span class="error">$error</span>
            </div>
            <div data-role="fieldcontain">
                <label for=""></label>
                Введите Ваши данные для входа:
            </div>
            <div data-role="fieldcontain">
                <label for="">Имя пользователя</label>
                <input type="text" maxlength="16" name="user" value="$user">
                <label for=""></label><div id="used">&nbsp;</div>
            </div>
            <div data-role="fieldcontain">
                <label for="">Пароль</label>
                <input type="password" maxlength="16" name="pass" value="$pass">
            </div>
            <div data-role="fieldcontain">
                <label for=""></label>
                <input data-transition="slide" type="submit" value="Войти">
            </div>
        </form>
    </div>
</body>
</html>
_END;
?>