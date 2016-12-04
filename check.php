<?php
// Скрипт проверки


# Соединямся с БД

#Соединение с БД
$mysqli = new mysqli("PhoneBook", "root", "", "phonebook");
if ($mysqli->connect_errno){
    echo "Не удалось подключится БД: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$query = mysqli_query("SELECT *,INET_NTOA(user_ip) FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
$userdata = mysqli_fetch_assoc($query);

if(($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id'])
or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['user_ip'] !== "0"))) {

    setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");

    setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");

    echo "Хм, что-то не получилось";

} else {

    print "Привет, " . $userdata['user_login'] . ". Всё работает!";

}

} else {

    print "Включите куки";

}