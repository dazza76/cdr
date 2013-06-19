<?php
/**
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * Ответ пользователю
 */
class ACResponse {

    /**
     * @var ACResponse
     */
    protected static $_singleton;

    /**
     * Список HTTP статусов ответа
     * @var array
     */
    protected static $_statusCodes;

    /**
     * Масив типов документа
     * @var array
     */
    protected static $_mimeTypes;

    /**
     * @var array
     */
    protected $_cookieDefaults = array(
        'name'     => '',
        'value'    => '',
        'expire'   => 0,
        'path'     => '/',
        'domain'   => null,
        'secure'   => false,
        'httpOnly' => false
    );

    /**
     * Протокол
     * @var string
     */
    protected $_protocol = 'HTTP/1.1';

    /**
     * код состояния ответа
     * @var integer
     */
    protected $_status = 200;

    /**
     * Тип документа
     * @var integer
     */
    protected $_contentType = 'text/html';

    /**
     * Буфер список заголовков
     * @var array
     */
    protected $_headers = array();

    /**
     * Буфер строки для ответного сообщения
     * @var string
     */
    protected $_body = null;

    /**
     * Кодировка тела ответа
     * @var string
     */
    protected $_charset = 'UTF-8';

    /**
     * Куки:)
     * @var array
     */
    protected $_cookies = array();

    /**
     * Class constructor
     *
     * @param array $options list of parameters to setup the response. Possible values are:
     * 	- body: the response text that should be sent to the client
     * 	- status: the HTTP status code to respond with
     * 	- type: a complete mime-type string or an extension mapped in this class
     * 	- charset: the charset for the response body
     */
    public function __construct($options = null) {
        if (self::$_singleton) {
            throw new ACSingletonException(get_class($this));
        }
        self::$_mimeTypes = include_once APPPATH . 'ac/base/mimeTypes.php';
        self::$_statusCodes = include_once APPPATH .'ac/base/statusCodes.php';
        self::$_singleton = $this;

        if ($options instanceof ACConfig) {
            $options = $options->toArray();
        }
        if ( ! is_array($options)) {
            $options = array();
        }
        if (isset($options['body'])) {
            $this->body($options['body']);
        }
        if (isset($options['status'])) {
            $this->statusCode($options['status']);
        }
        if (isset($options['type'])) {
            $this->type($options['type']);
        }
        if (isset($options['charset'])) {
            $this->charset($options['charset']);
        }
        if (isset($options['cookie'])) {
            $this->_cookieDefaults = array_replace($this->_cookieDefaults,
                                                   $options['cookie']);
        }
    }

    /**
     * Конвертирование объекта в строку.
     * Заголовки при этом не посылаються.
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->_body;
    }

    /**
     * Buffers a header string to be sent
     * Returns the complete list of buffered headers
     *
     * ### Single header
     * e.g `header('Location', 'http://example.com');`
     *
     * ### Multiple headers
     * e.g `header(array('Location' => 'http://example.com', 'X-Extra' => 'My header'));`
     *
     * ### String header
     * e.g `header('WWW-Authenticate: Negotiate');`
     *
     * ### Array of string headers
     * e.g `header(array('WWW-Authenticate: Negotiate', 'Content-type: application/pdf'));`
     *
     * Multiple calls for setting the same header name will have the same effect as setting the header once
     * with the last value sent for it
     *  e.g `header('WWW-Authenticate: Negotiate'); header('WWW-Authenticate: Not-Negotiate');`
     * will have the same effect as only doing `header('WWW-Authenticate: Not-Negotiate');`
     *
     * @param string|array $header An array of header strings or a single header string
     * 	- an associative array of "header name" => "header value" is also accepted
     * 	- an array of string headers is also accepted
     * @param string $value  The header value.
     * @return array list of headers to be sent
     */
    public function header($header = null, $value = null) {
        if (is_null($header)) {
            return $this->_headers;
        }
        if (is_array($header)) {
            foreach ($header as $h => $v) {
                if (is_numeric($h)) {
                    $this->header($v);
                    continue;
                }
                $this->_headers[$h] = trim($v);
            }
            return $this->_headers;
        }

        if ( ! is_null($value)) {
            $this->_headers[$header] = $value;
            return $this->_headers;
        }

        list($header, $value) = explode(':', $header, 2);
        $this->_headers[$header] = trim($value);
        return $this->_headers;
    }

    /**
     * Buffers the response message to be sent
     * if $content is null the current buffer is returned
     *
     * @param string $content the string message to be sent
     * @return string current message buffer if $content param is passed as null
     */
    public function body($content = null) {
        if (is_null($content)) {
            return $this->_body;
        }
        return $this->_body = $content;
    }

    /**
     * Устанавливает код статуса отправки HTTP
     * Если $code равен null возвращаеться текущий статус
     *
     * @param integer $code
     * @return integer current status code
     * @throws CakeException When an unknown status code is reached.
     */
    public function statusCode($code = null) {
        if (is_null($code)) {
            return $this->_status;
        }
        return $this->_status = $code;
    }

    /**
     * Queries & sets valid HTTP response codes & messages.
     *
     * @param integer|array $code If $code is an integer, then the corresponding code/message is
     *        returned if it exists, null if it does not exist. If $code is an array,
     *        then the 'code' and 'message' keys of each nested array are added to the default
     *        HTTP codes. Example:
     *
     *        httpCodes(404); // returns array(404 => 'Not Found')
     *
     *        httpCodes(array(
     *            701 => 'Unicorn Moved',
     *            800 => 'Unexpected Minotaur'
     *        )); // sets these new values, and returns true
     *
     * @return mixed associative array of the HTTP codes as keys, and the message
     *    strings as values, or null of the given $code does not exist.
     */
    public function httpCodes($code = null) {
        if (empty($code)) {
            return self::$_statusCodes;
        }

        if (is_array($code)) {
            self::$_statusCodes = $code + self::$_statusCodes;
            return true;
        }

        if ( ! isset(self::$_statusCodes[$code])) {
            return null;
        }
        return array($code => self::$_statusCodes[$code]);
    }

    /**
     * Sets the response content type. It can be either a file extension
     * which will be mapped internally to a mime-type or a string representing a mime-type
     * if $contentType is null the current content type is returned
     * if $contentType is an associative array, content type definitions will be stored/replaced
     *
     * ### Установка типа содержимого
     *
     * e.g `type('jpg');`
     *
     * ### Возвращает текущий тип контента
     *
     * e.g `type();`
     *
     * ### Хранение определения типа содержимого
     *
     * e.g `type(array('keynote' => 'application/keynote', 'bat' => 'application/bat'));`
     *
     * ### Замена определения типа содержимого
     *
     * e.g `type(array('jpg' => 'text/plain'));`
     *
     * @param string $contentType
     * @return mixed current content type or false if supplied an invalid content type
     */
    public function type($contentType = null) {
        if (is_null($contentType)) {
            return $this->_contentType;
        }
        if (is_array($contentType)) {
            foreach ($contentType as $type => $definition) {
                self::$_mimeTypes[$type] = $definition;
            }
            return $this->_contentType;
        }
        if (isset(self::$_mimeTypes[$contentType])) {
            $contentType = self::$_mimeTypes[$contentType];
            $contentType = is_array($contentType) ? current($contentType) : $contentType;
        }
        if (strpos($contentType, '/') === false) {
            return false;
        }
        return $this->_contentType = $contentType;
    }

    /**
     * установка кодировки страницы. Возвращает новую установившуюся кодировку
     *
     * @param string $charset - новая кодировка
     * @return string - текущая кодировка
     */
    public function charset($charset = null) {
        if (is_null($charset)) {
            return $this->_charset;
        }
        return $this->_charset = $charset;
    }

    /**
     * Устанавливает правильные заголовки, чтобы настроить браузер для загрузки
     * ответ в виде файла.
     *
     * @param string $filename - имя файла в качестве браузера будет скачать ответ
     * @return void
     */
    public function download($filename) {
        $this->header('Content-Disposition',
                      'attachment; filename="' . $filename . '"');
    }

    public function cookie($name, $value = null, $expire = 0, $path = null,
                           $domain = null, $secure = false, $httpOnly = false) {
        $options = compact('name', 'value', 'expire', 'path', 'domain',
                           'secure', 'httpOnly');
        $options = array_slice($options, 0, func_num_args()) + $this->_cookieDefaults;

        $this->_cookies[$options['name']] = $options;
    }

    /**
     * Передает полный ответ на клиента, включая заголовки и тело сообщения.
     * Will echo out the content in the response body.
     *
     * @return void
     */
    public function send() {
        $codeMessage = self::$_statusCodes[$this->_status];
        $this->sendCookies();
        $this->_sendHeader("{$this->_protocol} {$this->_status} {$codeMessage}");
        $this->_sendContentType();
        foreach ($this->_headers as $header => $value) {
            $this->_sendHeader($header, $value);
        }

        $this->_sendContent($this->_body);

        Log::trace('Response::send() [end]', 'Header');
    }

    /**
     * Отправка подготовленных кукитов клиенту
     *
     * @return void
     */
    public function sendCookies() {
        foreach ($this->_cookies as $name => $c) {
            if ($c['expire'] == 0) {
                setcookie($name, $c['value']);
                $c = array($name, $c['value']);
            } else {
                setcookie($name, $c['value'], $c['expire']);
                $c = array($name, $c['value'], $c['expire']);
            }
            Log::trace("Header> setcookie(" . implode(", ", $c) . ")");
        }
    }

    /**
     * Отправка заголовков клиенту
     *
     * @param string $name - the header name
     * @param string $value - the header value
     * @return void
     */
    protected function _sendHeader($name, $value = null) {
        if ( ! headers_sent()) {
            if (is_null($value)) {
                $header = $name;
            } else {
                $header = "{$name}: {$value}";
            }
            header($header);
            Log::trace($header, 'Header');
            // Log::trace("Header> header:: " . $header);
        } else {
            Log::error("Header> headers was sent", 'Header');
        }

    }

    /**
     * печать контента
     *
     * @param string $content - строка печати
     * @return void
     */
    protected function _sendContent($content) {
        echo $content;
        // Log::trace("Header> Send Content");
    }

    /**
     * Formats the Content-Type header based on the configured contentType and charset
     * the charset will only be set in the header if the response is of type text/*
     *
     * @return void
     */
    protected function _sendContentType() {
        if (in_array($this->_status, array(304, 204))) {
            return;
        }
        if (strpos($this->_contentType, 'text/') === 0) {
            $this->header('Content-Type',
                          "{$this->_contentType}; charset={$this->_charset}");
        } else {
            // $this->header('Content-Type', "{$this->_contentType}");
        }
    }
}
