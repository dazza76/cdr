<?php
/**
 * ACDateTime class  - ACDateTime.php file
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * ACDateTime class
 *
 * @package		AC
 */
class ACDateTime extends DateTime {

    const DATETIME = 'Y-m-d H:i:s';
    const DATE     = 'Y-m-d';
    const TIME     = 'H:i:s';

    public static function createFromFormat($format, $time) {
        $datetime = DateTime::createFromFormat($format, $time);
        if ( $datetime ) {
            $cdatetime = new self($datetime->format('Y-m-d H:i:s'));
            return $cdatetime;
        }
    }

    public static function createFromDatetime(DateTime $datetime) {
        $time      = $datetime->format('Y-m-d H:i:s');
        $cdatetime = new self($time);
        return $cdatetime;
    }

    /**
     * @var string формат вывода по умолчанию
     */
    public $defaultFormat = 'Y-m-d H:i:s';

    public function getMinutesDay() {
        list($hour, $min) = explode(" ", $this->format('H i'));
        $count = $hour * 60 + $min;
        return $count;
    }

    public function format($format = null) {
        if ($format === null) {
            $format = $this->defaultFormat;
        }
        // F : Полное наименование месяца
        // f : Полное наименование месяца в родительгом падеже
        // M : Сокращенное наименование месяца, 3 символа

        // eng
        $M_eng  = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
            'Oct', 'Nov', 'Dec');
        // rus
        $F_rus  = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль',
            'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
        // rus
        $f_rus  = array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля",
            "Августа", "Сентября", "Октября", "Ноября", "Декабря");
        // rus
        $M_rus  = array('Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен',
            'Окт', 'Ноя', 'Дек');

        // find format
        if (strpos($format, "f") !== false)
            $M_rus = $f_rus;
        if (strpos($format, "F") !== false)
            $M_rus = $F_rus;

        // replace format
        $format = str_replace(array("F", "f"), "M", $format);

        return str_replace($M_eng, $M_rus, parent::format($format));
    }

    public function __toString() {
        //'Y-m-d H:i:s'
        return $this->format($this->defaultFormat);
    }
}