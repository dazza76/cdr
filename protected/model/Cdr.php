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
     * @return string в формате hh:mm::ss
     */
    public function getTime() {
        $file = $file = $_SERVER['DOCUMENT_ROOT'] .  $this->getFile();

        // $f = fopen($file, 'r');
        // fseek($f, 16);
        // list(, $chunk_size) = unpack('V', fread($f, 4));
        // fseek($f, 28);
        // list(, $bps) = unpack('V', fread($f, 4));
        // fseek($f, 24 + $chunk_size);
        // list(, $data_size) = unpack('V', fread($f, 4));

        // return $data_size / $bps;



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
            $minutes = intval(($sec / 60) % 60);
            $seconds = intval($sec % 60);
            return str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
        }

        // $seconds = (int) $this->duration;
        // $di      = new DateInterval('PT' . $seconds . 'S');
        // $di->h   = floor($seconds / 60 / 60);
        // $seconds -= $di->h * 3600;
        // $di->i   = floor($seconds / 60);
        // $seconds -= $di->i * 60;
        // $di->s   = $seconds;

        // return $di->format('%H:%I:%S');
    }




    /**
     * Ссылка на файл
     * @return string
     */
    public function getFile() {
        if ($this->file_exists == 2) {
            return self::monitorFile($this->uniqueid, $this->calldate);
        } else {
            return self::monitorFile($this->uniqueid);
        }
    }

    /**
     * Директория с аудиозаписями
     * @param string $date  искать в папках по датам
     * @return string
     */
    public static function monitorDir($date = null) {
        $dir = App::Config()->cdr->monitor_dir . "/";
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
    public static function monitorFile($uniqueid, $date = null) {
        return self::monitorDir($date) . $uniqueid . '.' . App::Config()->cdr->file_format;
    }
}