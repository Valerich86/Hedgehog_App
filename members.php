<?php
require_once 'header.php';

if(!$loggedin) die("</div></body></html>");

if(isset($_GET['view'])){
    $view = sanitizeString($_GET['view']);

    echo "<h3>Профиль пользователя $view</h3>";
    showProfile($view);
    if($view !== $user){
        echo "<a data-role='button' data-transition='slide' data-inline='true'
            data-icon='mail' href='messages.php?view=$view&r=$randstr'>
            <span class='whisper'>Чат с пользователем $view</span></a>";
        echo "<a data-role='button' data-transition='slide' data-inline='true'
            data-icon='heart' href='friends.php?view=$view&r=$randstr'>
            <span class='whisper'>Друзья пользователя $view</span></a>";
    }
    die("</div></body></html>");
}

if(isset($_GET['add'])){
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
    if(!$result->rowCount())
        queryMysql("INSERT INTO friends (user, friend) VALUES ('$add', '$user')");
}
elseif(isset($_GET['remove'])){
    $remove = sanitizeString($_GET['remove']);

    queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
}

$result = queryMysql("SELECT user FROM members ORDER BY user");

if($result->rowCount()<2) echo "<br>Пока здесь никого нет...";
else      echo "<br>Вот список:<br><br>";
echo "<ul data-filter='true' data-filter-placeholder='Найти...'  data-autodividers='true'>";
while($row = $result->fetch()){
    if($row['user'] == $user) continue;

    echo "<li><a data-transition='slide' href='members.php?view=" . 
          $row['user'] . "&$randstr'>" . $row['user'] . "</a>";
    $follow = "Пригласить в друзья";

    $result1 = queryMysql("SELECT * FROM friends WHERE user='" . $row['user'] . "' AND friend='$user'");
    $t1 = $result1->rowCount();
    $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
    $t2 = $result1->rowCount();

    if(($t1 + $t2) > 1) echo " &harr; у Вас в друзьях ";
    elseif($t1)         echo " &larr; получил Ваш запрос ";
    elseif($t2)         {echo " &rarr; отправил Вам запрос "; $follow = "Принять";}

    if(!$t1) echo " &larr;<a href='members.php?add=" . $row['user'] . "&r=$randstr'>$follow</a>";
    else     echo " <a href='members.php?remove=" . $row['user'] . "&r=$randstr'>Отклонить</a>";
    echo "<br>";
}
?>
        </ul><div>
    </body>
</html>