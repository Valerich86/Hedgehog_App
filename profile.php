<?php
require_once 'header.php';

if (!$loggedin) die('</div></body></html>');

echo "<h3>Ваш профиль</h3>";

$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);

    if ($result->rowCount())
        queryMysql("UPDATE profiles SET text='$text' WHERE user='$user'");
    else
        queryMysql("INSERT INTO profiles (user, text) VALUES('$user','$text')");
} else {
    if ($result->rowCount()) {
        $row = $result->fetch();
        $text = stripslashes($row['text']);
    } else $text = ''; 
}

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

if (isset($_FILES['image']['name'])) {
    $saveto = "user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    $info = array();
    $width = $height = 0;
    $type = '';

    if(file_exists($saveto)){
        $info   = getimagesize($saveto);
        $width  = $info[0];
        $height = $info[1];
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
            $w = 200;
            $h = 0;
            
            if (empty($w)) {
                $w = ceil($h / ($height / $width));
            }
            if (empty($h)) {
                $h = ceil($w / ($width / $height));
            }
            
            $tmp = imageCreateTrueColor($w, $h);
            if ($type == 1 || $type == 3) {
                imagealphablending($tmp, true); 
                imageSaveAlpha($tmp, true);
                $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127); 
                imagefill($tmp, 0, 0, $transparent); 
                imagecolortransparent($tmp, $transparent);    
            }   
            
            $tw = ceil($h / ($height / $width));
            $th = ceil($w / ($width / $height));
            if ($tw < $w) {
                imageCopyResampled($tmp, $img, ceil(($w - $tw) / 2), 0, 0, 0, $tw, $h, $width, $height);        
            } else {
                imageCopyResampled($tmp, $img, 0, ceil(($h - $th) / 2), 0, 0, $w, $th, $width, $height);    
            }            
            
            $img = $tmp;
        }
    }
    
    echo "<script>window.location.replace('members.php?view=$user&r=$randstr');</script>";

    // switch ($_FILES['image']['type']) {
    //     case "image/gif":
    //         $src = imagecreatefromgif($saveto);
    //         break;
    //     case "image/jpeg":
    //     case "image/pjpeg":
    //         $src = imagecreatefromjpeg($saveto);
    //         break;
    //     case "image/png":
    //         $src = imagecreatefrompng($saveto);
    //         break;
    //     default:
    //         $typeok = FALSE;
    //         break;
    // }


    // if ($typeok) {
    //     list($w, $h) = getimagesize($saveto);

    //     $max = 100;
    //     $tw = $w;
    //     $th = $h;

    //     if ($w > $h && $max < $w) {
    //         $th = $max / $w * $h;
    //         $tw = $max;
    //     } elseif ($h > $w && $max < $h) {
    //         $tw = $max / $h * $w;
    //         $th = $max;
    //     } elseif ($max < $w) {
    //         $tw = $th = $max;
    //     }

    //     $tmp = imagecreatetruecolor($tw, $th);
    //     imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
    //     imageconvolution($tmp, array(array(-1, -1, -1), array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
    //     imagejpeg($tmp, $saveto);
    //     imagedestroy($tmp);
    //     imagedestroy($src);
    // }
}

//showProfile($user);
echo <<<_END
        <form action="profile.php?r=$randstr" data-ajax="false" method="post" enctype="multipart/form-data">
            Введите или измените инфо о себе и/или добавьте фото
            <textarea name="text">$text</textarea><br>
            Изображение: <input type="file" name="image" size="14">
            <input type="submit" value="Сохранить">
        </form>
    </div><br>
</body>
</html>
_END;


?>
