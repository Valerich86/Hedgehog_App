<?php
session_start();
require_once 'header.php';

echo "<div class='center'>Hedgehog приветствует Вас, ";

if ($loggedin) echo "$user";
else echo "<br>пожалуйста авторизуйтесь или зарегистрируйтесь";

echo <<<_END
        </div><br>
    </div>
    <div data-role="footer">
        <h4>Web App of <i>Dmitriy Ryazanov</i></h4>
    </div>
</body>
</html>
_END;
