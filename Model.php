<?php
//В больших проектах и не очень лучше использовать автозагрузчик классов
include_once "DB.php";
include_once "Cache.php";

class Model
{
    private $mCache;


    public function __construct()
    {
        $this->mCache = $this->modelCache();
    }

    public function modelCache()
    {
        return new Cache();
    }

    public function getCourseAndWrite($currencyCode)
    {
        $val = $this->mCache->readCache($currencyCode);
        if ($val == null) {
            $val = $this->getCourseBase($currencyCode);
            $this->mCache->writeCache();
        }
        return $val;

    }

    public function getCourseBase($currencyCode)//делаем запрос, если нет данных, вызываем метод установки и еще раз делаем запрос
    {
        $args = DB::getRow('SELECT', ['val' => $currencyCode]);
        if ($args == null) {
            $this->setCourse();
            $args = DB::getRow('SELECT', ['val' => $currencyCode]);
        }
        return $args;
    }

    public function setCourse($currencyCode, $format)
    {
        $val = $this->getCourseHttp($currencyCode, $format);
        return DB::insert('INSERT', ['currencyCode' => $currencyCode, 'val' => $val]);
    }

    public function getCourseHttp($currencyCode, $format)
    {
        //тут код получаем курсы валют с сайта, на вход получаем валюту и формат, возвращаем значение

    }
}