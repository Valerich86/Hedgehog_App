<?php
require_once 'header.php';

if (!$loggedin) die("</div></body></html>");

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else                      $view = $user;

if (isset($_POST['message'])){
    $message = sanitizeString($_POST['message']);

    if ($message != ""){
        $pm = substr(sanitizeString($_POST['pm']), 0, 1);
        $time = time();
        queryMysql("INSERT INTO messages VALUES (null, '$user', '$view', '$pm', '$time', '$message')");
    }
}

if ($view != ""){
    if ($view == $user) $name1 = $name2 = "Ваши ";
    else{
        $name1 = "<a href='members.php?view=$view&r=$randstr'>$view:</a>";
        $name2 = "$view";
    }

    showProfile($view);

    echo <<<_END
        <form method="post" action="messages.php?view=$view&r=$randstr">
            <fieldset data-role="controlgroup" data-type="horizontal">
                <legend>Выберите тип сообщения:</legend>
                <input type="radio" name="pm" id="public" value="0" checked="checked">
                <label for="public">Публичное</label>
                <input type="radio" name="pm" id="private" value="1">
                <label for="private">Приватное</label>
            </fieldset>
            <textarea name="message"></textarea>
            <input data-transition="slide" type="submit" value="Отправить">
        </form><br>
    _END;

    date_default_timezone_set('Europe/Moscow');

    if(isset($_GET['erase'])){
        $erase = sanitizeString($_GET['erase']);
        queryMysql("DELETE FROM messages WHERE id='$erase' AND recip='$user'");
    }

    $result = queryMysql("SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC");
    $num = $result->rowCount();

    while ($row = $result->fetch()){
        if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user){
            echo "<li>";
            echo date('j F Y - H:i', $row['time']) . "<br>";
            echo "<a href='messages.php?view=" . $row['auth'] . "&r=$randstr'>" . $row['auth'] . "</a>";

            if ($row['pm'] == 0)
                echo " пишет: &quot;" . $row['message'] . "&quot; ";
            else
                echo " Приватное: <span class='whisper'>&quot;" . $row['message'] . "&quot;</span>";

            if ($row['recip'] == $user)
                echo "[<a href='messages.php?view=$view" . "&erase=" . $row['id'] . "&r=$randstr'>Стереть</a>]";
            echo "</li><br>";
        }
    }
    if (!$num){
        echo "<br><span class='info'>Сообщений нет.</span><br><br>";
    }
}

echo "<br><br><a data-role='button' href='messages.php?view=$view&r=$randstr'>Обновить</a>";
?>
        </div><br>
    </body>
</html>