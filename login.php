<?php
// Страница авторизации

#Функция для генерации случайной строки

function generateCode($lenght=6){
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $lenght){
        $code = $chars[mt_rand(0, $clen)];
    }
    return $code;
}

#Соединение с БД
$mysqli = new mysqli("localhost", "root", "", "phonebook");
if ($mysqli->connect_errno){
    echo "Не удалось подключится БД: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (isset($_POST['submit'])) {
    # Вытаскиваем из БД запись, у которой логин равняеться введенному

    $query = mysqli_query("SELECT user_id, user_password FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."' LIMIT 1");
        $data = mysqli_fetch_assoc($query);

    #Сравниваем пароли

    if ($data['user_password'] === md5(md5($_POST['password']))){
        $hash = md5(generateCode(10));
        if (!@$_POST['not_attach_ip']){
            # Если пользователя выбрал привязку к IP

            # Переводим IP в строку

            $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";

        }
        # Записываем в БД новый хеш авторизации и IP

        mysqli_query("UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");
        #Ставим Куки

        setcookie("id", $data['user_id'], time()+60*60*24*30);

        setcookie("hash", $hash, time()+60*60*24*30);

        # Переадресовываем браузер на страницу проверки нашего скрипта

        header("Location: check.php"); exit();

}}

else

{

    print "Вы ввели неправильный логин/пароль";



}
