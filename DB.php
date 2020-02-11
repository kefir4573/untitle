<?php
//Класс, который отвечает за подключение к базе данных и методы


class DB {

    const DB_HOST = 'localhost';
    const DB_NAME = 'test';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_CHAR = 'utf8';

    protected static $instance = null;

    private function __construct() {

    }

    private function __clone() {

    }

    //метод создания/возвращения подключения к бд синглтон
    private static function instance() {
        if (self::$instance === null) {
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => TRUE,
            );
            $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=' . self::DB_CHAR;
            self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $opt);
        }
        return self::$instance;
    }

    //так как используем ПДО, создаем общий метод подготовки, отправки запроса
    private static function sql($sql, $args = []) {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    //метод который принимает на вход sql запрос и массив с аргументами и возвращаем данные
    public static function getRow($sql, $args = []) {
        return self::sql($sql, $args)->fetch();
    }
    //инсерт в бд, в нашем случае usd добавляем
    public static function insert($sql, $args = []) {
        self::sql($sql, $args);
        return self::instance()->lastInsertId();
    }




}



