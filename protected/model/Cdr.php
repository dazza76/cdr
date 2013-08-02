<?php
/**
 * Cdr class
 *
 * @property string $id            - id
 * @property ACDateTime $calldate      - дата и время вызова
 * @property string $clid          - Так называемый CALLERID (Имя в кавычках, номер в скобках).
 *                                   Выводится на телефон, по факту в самой странице не используется
 * @property string $src           - номер, с которого звонили.
 * @property string $dst           - набранный номер.
 * @property string $dcontext      - контекст (служебная вещь asterisk). По сути, если incoming – то вызов входящий, если остальные – то исходящий.
 *                                   Исключение – контексты вида «from-**», служащие для связи с другими станциями.
 *                                   Там придется по длине номера dst смотреть, если 3-4 цифры, то вызов внутренний,
 *                                   если больше – он в город, соответственно, при наличии записи разговора выводим.
 * @property string $channel       - имя канала (не используется в отчете, служебная информация)
 * @property string $dstchannel    - то же самое.
 * @property string $lastapp       - на какой команде закончился вызов, нам не интересно.
 * @property string $lastdata      - параметры, переданные последней команде.
 * @property string $duration      - длительность вызова.
 * @property string $billsec       - сколько времени вызов был отвечен
 * @property string $disposition   - состояние (занято, нет ответа, принят). Нам нужны только ANSWERED (неприянтые или занятые естественно не записываются)
 * @property string $amaflags      - не используется, стандартное поле.
 * @property string $accountcode   - используется
 * @property string $uniqueid      - идентификатор вызова (под этим имененем храним файл с записью)
 * @property string $file_exists   - информация о существовании файла.
 *                                     NULL - информация неизвестна
 *                                     0    - файла нет
 *                                     1    - файл находиться в папке /monitor/
 *                                     2    - файл находиться в папке /monitor/YYYY/
 *                                     3    - файл находиться в папке /monitor/YYYY/MM/
 *                                     4    - файл находиться в папке /monitor/YYYY/MM/DD/
 * @property string $userfield     - пользовательское поле, в случае исходящего вызова там может быть записан код оператора.
 *                                   Нам достаточно, если Вы выведете этот код.
 *                                   В случае входящего код принявшего вызов оператора лежит в поле dstchannel.
 * @property string $comment       - коментарий
 */
class Cdr extends ACDataObject {
    /**
     * @var string название таблицы
     */

    const TABLE = 'cdr';


    /**
     * @var integer вызов внутренний
     */
    const HOME = 0;

    /**
     * @var integer вызов входящий
     */
    const INCOMING = 1;

    /**
     * @var integer вызов исходящий
     */
    const OUTCOMING = 2;

    public function __set($name, $value) {
        if ($name == 'calldate') {
            $value = new ACDateTime($value);
        }
        parent::__set($name, $value);
    }

        /**
     * Вызов входящий, исходящий, внутренний
     *   0 - внутренний
     *   1 - входящий
     *   2 - исходящий
     * @return integer
     */
    public function getComing() {
        if ($this->dcontext == 'incoming')
            return 1;
        if (strpos($this->dcontext, 'from') === false)
            return 2;

        if (strlen((string) $this->dst) > 4)
            return 2;
        else
            return 0;
    }

    /**
     * Номер назначение
     * @return string
     */
    public function getDst() {
        // 989052823638
        if (strlen($this->dst) == 12) {
            return substr($this->dst, 2);
        }
        return $this->dst;
    }

    /**
     * Код оператора
     * @return string
     */
    public function getOperatorCode() {
        if ($this->getComing() == self::OUTCOMING) {
            return $this->userfield;
        }
        if ($this->getComing() == self::INCOMING) {
            return $this->dstchannel;
        }
    }

    /**
     * длительность вызова
     * @return int
     */
    public function getTime() {
        $file = $_SERVER['DOCUMENT_ROOT'] .  $this->getFile();

        $fp = @fopen($file, 'r');
        if ($fp && fread($fp, 4) == "RIFF") {
            fseek($fp, 20);
            $rawheader = fread($fp, 16);
            $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits', $rawheader);
            $pos = ftell($fp);
            while (fread($fp, 4) != "data" && !feof($fp)) {
                $pos++;
                fseek($fp, $pos);
            }
            $rawheader = fread($fp, 4);
            $data = unpack('Vdatasize', $rawheader);
            $sec = $data[datasize] / $header[bytespersec];

            return (int) $sec;

            // $minutes = intval(($sec / 60) % 60);
            // $seconds = intval($sec % 60);
            // return str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
        }
    }

    /**
     * Ссылка на файл
     * @return string
     */
    public function getFile() {
        if (!$this->file_exists) {
            return;
        }

        $autoinform = in_array($this->dcontext, array('autoinform', 'outgoing', 'dialout'));
        $date = null;
        $format = App::Config()->cdr['file_format'];
        $format = ($this->file_exists & 4) ? strtoupper($format) : strtolower($format);

        if ($this->file_exists & 2) {
            $date = $this->calldate;
        }


        return self::audioDir($date, $autoinform).$this->uniqueid.".".$format;
    }


    /**
     * Поиск аудио файла по директориям.
     * mask:  1 - файл в директории
     *        2 - файл в папках по датам
     *        4 - верхний регистр расширения
     * @return int
     */
    public function getFileExistsInPath()
    {
        $autoinform = in_array($this->dcontext, array('autoinform', 'outgoing', 'dialout'));
        $file_exists = 0;
        $format_lower = strtolower(App::Config()->cdr['file_format']);
        $format_upper = strtoupper(App::Config()->cdr['file_format']);

        $file = $_SERVER['DOCUMENT_ROOT']. self::audioDir(null, $autoinform).$this->uniqueid;

        // echo "# " . $file.".".$format_lower. "\n";  // LOG::echo
        if (file_exists($file.".".App::Config()->cdr['file_format_low'])) {
            return $this->file_exists = 1;
        }
        // echo "# " . $file.".".$format_upper. "\n";  // LOG::echo
        if (file_exists($file.".".App::Config()->cdr['file_format_up'])) {
           return $this->file_exists = 1 | 4;
        }

        $file = $_SERVER['DOCUMENT_ROOT'].self::audioDir($this->calldate, $autoinform).$this->uniqueid;
        // echo "# " . $file.".".$format_lower. "\n";  // LOG::echo
        if (file_exists($file.".".App::Config()->cdr['file_format_low'])) {
            return $this->file_exists = 2;
        }
        // echo "# " . $file.".".$format_upper. "\n";  // LOG::echo
        if (file_exists($file.".".App::Config()->cdr['file_format_up'])) {
           return $this->file_exists = 2 | 4;
        }

        // $this->file_exists = $file_exists;
        return $this->file_exists = 0;
    }

    /**
     * Директория с аудиозаписями
     * @param string $date  искать в папках по датам
     * @param bool   $autoinform
     * @return string
     */
    public static function audioDir($date = null, $autoinform = false) {
        if ($autoinform) {
            $dir = App::Config()->cdr['autoinform_dir'] . "/";
        } else {
            $dir = App::Config()->cdr['monitor_dir'] . "/";
        }

        if ($date == null) {
            return $dir;
        }

        $date = ACPropertyValue::ensureDate($date, false);
        return $dir . implode('/', $date) . '/';
    }


    /**
     * Полный путь к файлу
     * @param string $uniqueid
     * @param string $date  искать в папках по датам
     * @return string
     */
    /*
    public static function audioFile($uniqueid, $date = null, $autoinform = false, $file_format = true) {
        $file = self::audioDir($date, $autoinform) . $uniqueid ;
        if ($file_format) {
             $file .= '.' . App::Config()->cdr['file_format'];
        }
        return $file;
    }
    */
    /*
    public static function getFileExist($uniqueid, $date = null, $autoinform = false) {
        $file_ln = self::audioFile($uniqueid, $date, $autoinform, false) ;

        $file = $file_ln . '.' . strtolower(App::Config()->cdr['file_format']);
        if (file_exists($file)) {
            return $file;
        }

        $file = $file_ln . '.' . strtoupper(App::Config()->cdr['file_format']);
        if (file_exists($file)) {
            return $file;
        }

        return null;
    }
    */
    /*
    public static function audioDuration($file, $format = false) {
        $fp = @fopen($file, 'r');
        if ($fp && fread($fp, 4) == "RIFF") {
            fseek($fp, 20);
            $rawheader = fread($fp, 16);
            $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits', $rawheader);
            $pos = ftell($fp);
            while (fread($fp, 4) != "data" && !feof($fp)) {
                $pos++;
                fseek($fp, $pos);
            }
            $rawheader = fread($fp, 4);
            $data = unpack('Vdatasize', $rawheader);
            $sec = $data[datasize] / $header[bytespersec];


            if (!$format)  {
                return $sec;
            }
            $minutes = intval(($sec / 60) % 60);
            $seconds = intval($sec % 60);
            return str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
        }

        return 0;
    }
    */

}