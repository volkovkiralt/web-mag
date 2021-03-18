<?php
function DBConnect() {
    try {
        $connect = new mysqli(
            'localhost',
            'u103640_kira',
            '!12345Rbhf',
            'u103640_arenda_db'
        );
    } catch (PDOException $e) {
        echo 'Error!: ' . $e->getMessage();
        die();
    }
    if (!$connect) {
        echo 'Ошибка соединения: ' . mysqli_connect_error() . '<br>';
        echo 'Код ошибки: ' . mysqli_connect_errno();
        die();
    }
    return $connect;
}
