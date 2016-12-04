<?php
// Страница регистрации нового пользователя

#Соединение с БД
$mysqli = new mysqli("PhoneBook", "root", "", "phonebook");
if ($mysqli->connect_errno){
    echo "Не удалось подключится БД: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (isset($_POST['submit'])){
    $err = array();

    # Проверяем логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login'])){
        $err[] = "Логин может состоять только из букв латинского алфовита ";
    }

    if (strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30){
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }
    #Проверяем не сушествует ли такого пользователя

    $query = mysqli_query("SELECT COUNT(user_id) FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."'");
        if (mysqli_result($query, 0) > 0){
            $err[] = "Пользователь с таким логином уже существует";
        }
    # Если нет ошибок, то добавляем в БД нового пользователя
    if (count($err) == 0){
        $login = $_POST['login'];

        #Убераем лишнее пробелы и делае двойное шиффрование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query("INSERT INTO users SET user_login='".$login."', user_password='".$password."'");

        header("Location: login.php"); exit();
    }

    else{
        print "При регистрации произошли ошибки :";
        foreach ($err as $error){
            print $error."<br>";
        }
    }

}