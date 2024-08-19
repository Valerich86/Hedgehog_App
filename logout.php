<?php
require_once 'header.php';

if(isset($_SESSION['user'])){
    destroySession();
    // echo "<br><div class='center'>Вы вышли из аккаунта. Кликните
    //     <a data-transition='slide'
    //     href='index.php?r=$randstr'> здесь </a>
    //     для перехода на главную страницу.</div>";
    echo "<script>window.location.replace('index.php?r=$randstr');</script>";
}
else{
    echo "<div class='center'>Вы не можете выйти из аккаунта, так как Вы не входили в него!</div>";
}
?>
</div></body><html>