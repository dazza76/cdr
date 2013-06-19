<?php
/**
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

class ACRequest {

    /**
     * Метод выполнения GET/POST
     * @var string
     */
    public $method;

    /**
     * Время начало скрипта
     * @var integer
     */
    public $time;

    /**
     * От куда пришел пользователь
     * @var string
     */
    public $referer;

    /**
     * hostname - имя хоста
     * пример: localhost
     * @var string
     */
    public $host;

    /**
     * корневая директория приложения. Начинаеться с /
     * пример:
     *   {$webroot} :: /acvawe
     * полный путь при этом будет:
     *   {$host}{$webroot} :: localhost/acvawe
     * @var string
     */
    public $webpath; // = '/acvawe';

    /**
     * Строка параметров. Она же GET в виде строки
     * @var string
     */
    public $query;

    /**
     * URL строку, используемая для запроса.
     * $_SERVER['REQUEST_URI'].
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $page;

    /**
     * Constructor
     */
    public function __construct($webpath = "") {
        $this->webpath = $webpath;
        $this->method  = $_SERVER["REQUEST_METHOD"];
        $this->time    = $_SERVER["REQUEST_TIME"];
        $this->referer = $_SERVER["HTTP_REFERER"];
        $this->host    = $_SERVER["SERVER_NAME"];
        $this->query   = $_SERVER["QUERY_STRING"];
        $this->url     = $_SERVER["REQUEST_URI"];

        $url     = substr($this->url, strlen($webpath));
        $pattern = array(
            '|/+|',
            '|^/|',
            '|/$|',
            '|\?.*|'
        );
        $replace = array('/', '');
        $page    = preg_replace($pattern, $replace, $url);
        if (!$page) {
            $page                = 'index';
        }
        $this->page = $page;
    }

}
