<?php
require_once 'header.php';

if (!$loggedin) die('</div></body></html>');

echo "<h3>Ваш профиль</h3>";
$text = $photo = $birthday = '';

$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

if ($result->rowCount()) {
    while($row = $result->fetch()){
        $text = $row['about'];
        $birthday = $row['birthday'];
    }
}
if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);
    if ($result->rowCount())
        queryMysql("UPDATE profiles SET about='$text' WHERE user='$user'");
} 

if (isset($_POST['birthday'])) {
    $birthday = $_POST['birthday'];
    if ($result->rowCount())
        queryMysql("UPDATE profiles SET birthday='$birthday' WHERE user='$user'");
} 

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

if (isset($_FILES['image']['name'])) {
    $photo = $_FILES['image']['name'];
    $saveto = "res/".$photo;
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    if(file_exists($saveto)){
        $info   = getimagesize($saveto);
        $type   = $info[2];
        switch ($type) { 
            case 1: 
                $img = imageCreateFromGif($saveto);
                imageSaveAlpha($img, true);
                break;					
            case 2: 
                $img = imageCreateFromJpeg($saveto);
                break;
            case 3: 
                $img = imageCreateFromPng($saveto); 
                imageSaveAlpha($img, true);
                break;
            default:
                $typeok = FALSE;
                break;
        }
    
        if($typeok){
            if ($result->rowCount())
                queryMysql("UPDATE profiles SET photo='$photo' WHERE user='$user'");
        }
        else $photo = '';
    }
}


if (isset($_POST['submit'])){
    if (!$result->rowCount())
    queryMysql("INSERT INTO profiles (user, birthday, about, photo) 
    VALUES('$user', '$birthday','$text','$photo')");
    echo "<script>window.location.replace('members.php?view=$user&r=$randstr');</script>";
}

//showProfile($user);
echo <<<_END
        <form action="profile.php?r=$randstr" data-ajax="false" method="post" enctype="multipart/form-data">
            <h3 class='whisper'>Введите или измените инфо о себе и/или добавьте фото</h3>
            Дата рождения:
            <input type='date' name='birthday' value='$birthday'>
            <textarea name="text" placeholder='Немного о себе'>$text</textarea><br>
            Ваше фото: <input type="file" name="image" size="14">
            <input type="submit" name='submit' value="Сохранить">
        </form>
    </div><br>
</body>
</html>
_END;


?>
