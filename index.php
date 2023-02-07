<?php

include_once("config.php");
include_once("error_handler.php");
include_once("db_connection.php");
include_once("functions.php");
include_once("find_token.php");

if(!isset($_GET['type'])) {
    echo ajax_echo(
        "Ошибка!", // Заголовок ответа
        "Вы не указали GET параметр type", // Описание ответа
        true, // Наличие ошибка
        "ERROR", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}

//—————————————————————————
//  ВЫВОД ЗАПИСЕЙ ИЗ БД
//—————————————————————————

//  Список всех художников
if(preg_match_all("/^(artist_list)$/ui", $_GET['type'])){
    $query = "SELECT `name`, `surname`, `birth_date`, `death_date`, `annotation` FROM `artists`";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе.", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    $arr_list = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++) {
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_list, $row);
    }


    echo ajax_echo(
        "Список художников", // Заголовок ответа
        "Вывод списка художников", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        $arr_list // Дополнительные данные для ответа
    );

    exit();
}

//  Список всех направлений
if(preg_match_all("/^(movement_list)$/ui", $_GET['type'])){
    $query = "SELECT `title`, `annotation` FROM `movements`";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе.", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    $arr_list = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++) {
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_list, $row);
    }


    echo ajax_echo(
        "Список направлений", // Заголовок ответа
        "Вывод списка направлений", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        $arr_list // Дополнительные данные для ответа
    );

    exit();
}

//  Список всех картин
if(preg_match_all("/^(painting_list)$/ui", $_GET['type'])){
    $query = "SELECT p.title, p.year, CONCAT(a.name, ' ', a.surname) AS artist, m.title AS movement
    FROM `paintings` AS p JOIN `artists` AS a JOIN `movements` AS m 
    ON p.artist_id = a.id AND p.movement_id = m.id";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе.", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    $arr_list = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++) {
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_list, $row);
    }


    echo ajax_echo(
        "Список картин", // Заголовок ответа
        "Вывод списка картин", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        $arr_list // Дополнительные данные для ответа
    );

    exit();
}

//  Список экспонатов определенной выставки
if(preg_match_all("/^(exhibition_exposition_list)$/ui", $_GET['type'])){
    if(!isset($_GET['exhibition_id'])){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр exhibition_id!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    $query = "SELECT exh.theme AS 'exhibition theme', p.title AS exposition, CONCAT(a.name, ' ', a.surname) AS artist
    FROM `expositions` AS exp JOIN `exhibitions` AS exh JOIN `paintings` AS p JOIN `artists` AS a
    ON exp.painting_id = p.id AND exp.exhibition_id = exh.id AND p.artist_id = a.id
    WHERE exh.id = '" . $_GET['exhibition_id'] . "'";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе.", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    $arr_list = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++) {
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_list, $row);
    }


    echo ajax_echo(
        "Список экспонатов выставки", // Заголовок ответа
        "Вывод списка экспонатов выставки", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        $arr_list // Дополнительные данные для ответа
    );

    exit();
}

//  Список избранного определенного пользователя
if(preg_match_all("/^(user_favorites_list)$/ui", $_GET['type'])){
    if(!isset($_GET['user_id'])){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр user_id!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    $query = "SELECT u.name, u.surname, u.login AS user, p.title, CONCAT(a.name,' ', a.surname) AS artist 
    FROM `user_favorites` AS f JOIN users AS u JOIN `paintings` AS p JOIN `artists` AS a
    ON f.painting_id = p.id AND f.user_id = u.id AND p.artist_id = a.id
    WHERE u.id = '" . $_GET['user_id'] . "'";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе.", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    $arr_list = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++) {
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_list, $row);
    }


    echo ajax_echo(
        "Список избранного пользователя", // Заголовок ответа
        "Вывод списка избранного пользователем", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        $arr_list // Дополнительные данные для ответа
    );

    exit();
}

//—————————————————————————
// ДОБАВЛЕНИЕ ЗАПИСЕЙ В БД
//—————————————————————————

//Добавление нового художника
if(preg_match_all("/^(artist_add)$/ui", $_GET['type'])) {
    if (!isset($_GET['name'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр name!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['surname'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр surname!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['birth_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр birth_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['death_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр death_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['annotation'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр annotation!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['annotation']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['annotation'])){
        $query = "INSERT INTO `artists`(`annotation`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `artists`(`annotation`) VALUES ('".$_GET['annotation']."');";
    }

    $query = "INSERT INTO `artists`(`name`, `surname`, `birth_date`, `death_date`, `annotation`) 
    VALUES (
            '". $_GET['name'] ."',
            '". $_GET['surname'] ."',
            '". $_GET['birth_date'] ."',
            '". $_GET['death_date'] ."',
            '". $_GET['annotation'] ."'
    )";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", // Заголовок ответа
        "Новый художник успешно добавлен", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}

// Добавление нового пользователя
if(preg_match_all("/^(user_add)$/ui", $_GET['type'])) {
    if (!isset($_GET['login'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр login!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['pass'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр pass!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['name'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр name!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['surname'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр surname!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['email'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр email!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if(!isset($_GET['birth_date'])){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр birth_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['birth_date']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['birth_date'])){
        $query = "INSERT INTO `users`(`birth_date`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `users`(`birth_date`) VALUES ('".$_GET['birth_date']."');";
    }

    if(!isset($_GET['gender'])){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр gender!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['gender']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['gender'])){
        $query = "INSERT INTO `users`(`gender`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `users`(`gender`) VALUES ('".$_GET['gender']."');";
    }

    $query = "INSERT INTO `users`(`login`, `pass`, `name`, `surname`, `email`, `birth_date`, `gender`) 
    VALUES (
            '". $_GET['login'] ."',
            '". $_GET['pass'] ."',
            '". $_GET['name'] ."',
            '". $_GET['surname'] ."',
            '". $_GET['email'] ."',
            '". $_GET['birth_date'] ."',
            '". $_GET['gender'] ."'
    )";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", // Заголовок ответа
        "Новый пользователь успешно добавлен", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}

// Добавление новой выставки
if(preg_match_all("/^(exhibition_add)$/ui", $_GET['type'])) {
    if (!isset($_GET['theme'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр theme!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['start_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр start_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['end_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр end_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    $query = "INSERT INTO `exhibitions`(`theme`, `start_date`, `end_date`) 
    VALUES (
            '". $_GET['theme'] ."',
            '". $_GET['start_date'] ."',
            '". $_GET['end_date'] ."'
    )";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", // Заголовок ответа
        "Новая выставка успешно добавлена", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}

//—————————————————————————
// РЕДАКТИРОВАНИЕ ЗАПИСЕЙ В БД
//—————————————————————————

// Редактирование художника
if(preg_match_all("/^(artist_upd)$/ui", $_GET['type'])) {
    if (!isset($_GET['artist_id'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр artist_id!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['name'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр name!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['name']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['name'])){
        $query = "INSERT INTO `artists`(`name`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `artists`(`name`) VALUES ('".$_GET['name']."');";
    }

    if (!isset($_GET['surname'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр surname!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['birth_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр birth_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['death_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр death_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['death_date']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['death_date'])){
        $query = "INSERT INTO `artists`(`death_date`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `artists`(`death_date`) VALUES ('".$_GET['death_date']."');";
    }

    if (!isset($_GET['annotation'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр annotation!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['annotation']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['annotation'])){
        $query = "INSERT INTO `artists`(`annotation`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `artists`(`annotation`) VALUES ('".$_GET['annotation']."');";
    }

    $query = "UPDATE `artists`
    SET `name` = '". $_GET['name'] ."',
    `surname` = '". $_GET['surname'] ."',
    `birth_date` = '". $_GET['birth_date'] ."',
    `death_date` = '". $_GET['death_date'] ."',
    `annotation` = '". $_GET['annotation'] ."'
    WHERE `id` = '". $_GET['artist_id'] ."'";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", // Заголовок ответа
        "Обновление художника прошло успешно", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}

// Редактирование направления
if(preg_match_all("/^(movement_upd)$/ui", $_GET['type'])) {
    if (!isset($_GET['movement_id'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр movement_id!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['title'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр title!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['annotation'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр annotation!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['annotation']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['annotation'])){
        $query = "INSERT INTO `artists`(`annotation`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `artists`(`annotation`) VALUES ('".$_GET['annotation']."');";
    }

    $query = "UPDATE `movements`
    SET `title` = '". $_GET['title'] ."',
    `annotation` = '". $_GET['annotation'] ."'
    WHERE `id` = '". $_GET['movement_id'] ."'";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", // Заголовок ответа
        "Обновление направления прошло успешно", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}

// Редактирование пользователя
if(preg_match_all("/^(user_upd)$/ui", $_GET['type'])) {
    if (!isset($_GET['user_id'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр user_id!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['pass'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр pass!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }

    if (!isset($_GET['name'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр surname!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['name']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['name'])){
        $query = "INSERT INTO `users`(`name`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `users`(`name`) VALUES ('".$_GET['name']."');";
    }

    if (!isset($_GET['surname'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр birth_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['surname']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['surname'])){
        $query = "INSERT INTO `users`(`surname`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `users`(`surname`) VALUES ('".$_GET['surname']."');";
    }

    if (!isset($_GET['birth_date'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр birth_date!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['birth_date']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['birth_date'])){
        $query = "INSERT INTO `users`(`birth_date`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `users`(`birth_date`) VALUES ('".$_GET['birth_date']."');";
    }

    if (!isset($_GET['gender'])) {
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Вы не указали GET параметр gender!", // Описание ответа
            true, // Наличие ошибки в ответе
            "ERROR", // Статус ответа
            null // Дополнительные параметры ответа
        );
        exit();
    }
    if(iconv_strlen($_GET['gender']) == 0 || preg_match_all("/^(NULL)$/ui", $_GET['gender'])){
        $query = "INSERT INTO `users`(`gender`) VALUES (NULL);";
    } else {
        $query = "INSERT INTO `users`(`gender`) VALUES ('".$_GET['gender']."');";
    }

    $query = "UPDATE `users`
    SET `pass` = '". $_GET['pass'] ."',
    `name` = '". $_GET['name'] ."',
    `surname` = '". $_GET['surname'] ."',
    `birth_date` = '". $_GET['birth_date'] ."',
    `gender` = '". $_GET['gender'] ."'
    WHERE `id` = '". $_GET['user_id'] ."'";
    $res_query = mysqli_query($connection, $query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", // Заголовок ответа
            "Ошибка в запросе", // Описание ответа
            true, // Наличие ошибка
            "ERROR", // Результат ответа
            null // Дополнительные данные для ответа
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", // Заголовок ответа
        "Обновление пользователя прошло успешно", // Описание ответа
        false, // Наличие ошибка
        "SUCCESS", // Результат ответа
        null // Дополнительные данные для ответа
    );
    exit();
}
