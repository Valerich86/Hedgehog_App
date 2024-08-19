<?php
require_once 'header.php';

if (!$loggedin) die("</div></body></html>");

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else                      $view = $user;

if ($view != $user){
    $name1 = "<a data-transition='slide' href='members.php?view=$view&r=$randstr'>$view</a>";
}

$followers = array();
$following = array();

$result = queryMysql("SELECT * FROM friends WHERE user='$view'");
while($row = $result->fetch()){
    array_push($followers, $row['friend']);
}

$result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
while($row = $result->fetch()){
    array_push($following, $row['user']);
}

$mutual = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
$friends = FALSE;

if(sizeof($mutual)){
    echo "<span class='subhead'>Друзья пользователя '$view':</span><ul>";
    foreach ($mutual as $friend){
        echo "<li><a data-transition='slide' href='members.php?view=$friend&r=$randstr'>$friend</a></li>";
    echo "</ul>";
    $friends = TRUE;
    }
}
if(sizeof($followers)){
    echo "<span class='subhead'>Вам отправил приглашение:</span><ul>";
    foreach ($followers as $friend){
        echo "<li><a data-transition='slide' href='members.php?view=$friend&r=$randstr'>$friend</a></li>";
    echo "</ul>";
    $friends = TRUE;
    }
}
if(sizeof($following)){
    echo "<span class='subhead'>Вы отправили приглашение:</span><ul>";
    foreach ($following as $friend){
        echo "<li><a data-transition='slide' href='members.php?view=$friend&r=$randstr'>$friend</a></li>";
    echo "</ul>";
    $friends = TRUE;
    }
}
if(!$friends) echo "<br>Друзей пока нет...";
?>
        </div><br>
    </body>
</html>