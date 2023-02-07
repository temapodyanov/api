<?php

include_once("config.php");

$connection = mysqli_connect(
    $DB['host'],
    $DB['login'],
    $DB['password'],
    $DB['name'],
);

mysqli_query(
    $connection,
    "SET NAMES '" . $DB['charset'] . "_unicode_ci';"
);

mysqli_query(
    $connection,
    "SET CHARACTER SET '" . $DB['charset'] . "_unicode_ci';"
);

mysqli_query(
    $connection,
    "SET group_concat_max_len = 999999;"
);

if(mysqli_connect_errno()){
    echo "Ошибка в подключении к базе данных (" .
        mysqli_connect_errno . "): " . mysqli_connect_errno();
    exit();
}

if(!$connection->set_charset($DB['charset'])){
    printf(
        "Ошибка при загрузке набора символов " . $DB['charset']
        . ": %s\n",
        $connection->error
    );
    exit();
}