<?php
/**
 * GraphQueue class  - GraphQueue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * GraphQueue class
 */
class GraphQueue {

    const FONT   = "protected/view/font/tahoma.ttf";
    const FSIZE  = 9;
    const LEGEND = "images/graph/legend.png";

    /**
     * @var int число делений
     */
    public $numStepY = 5;

    /**
     *
     * @var bool
     */
    public $rotation = false;
    // -------------------------------------------

    private $config = array(
        'width'         => 1240,
        'height'        => 400,
        'bg_color'      => 0xFFFFFF,
        'bg_color_odd'  => 0xFAFAFA,
        'oY'            => 350,
        'oY_length'     => 330,
        'oX'            => 50,
        'oX_length'     => 1160,
        'axes_color'    => 0x000000,
        'grid_color'    => 0xCCCCCC,
        'success_color' => 0xD98962, // желтый
        'total_color'   => 0xB64245, // красный
    );

    /**
     * @var resource
     */
    private $_image;

    /**
     * @var array
     */
    private $_data;
    private $_countData;
    private $_delH;
    private $_stepH;
    private $_stepW;
    private $_avgStep;
    private $_totalMax;

    /**
     * construct
     * $data = array(
     *   'ox' => array (total, success)
     * )
     * @param array $data
     */
    public function __construct($data) {
        $this->_data      = $data;
        $this->_countData = count($data);

        $this->_getTotalMax();

        $image = @imagecreatetruecolor($this->config['width'],
                                       $this->config['height']) or die('Невозможно инициализировать GD поток');

        imagefill($image, 0, 0, $this->config['bg_color']);
        $this->_image = $image;
    }

    private function _getTotalMax() {
        $max       = 0;
        $avg_step  = 0;
        $pre_total = 0;
        foreach ($this->_data as $value) {
            if (is_array($value)) {
                list($total) = $value;
            } else {
                $total = $value;
            }
            if ($total > $max) {
                $max = $total;
            }

            $avg_step += abs($pre_total - $total);
            $pre_total = $total;
        }

        $tmax = round($max + $max * 0.2);

        for ($i = 1; $i < strlen($tmax); $i ++ ) {
            $n = (int) substr($tmax, -$i, 1);
            if ($n == 0) {
                break;
            }
            $tmax -= $n * pow(10, $i - 1);
            if ($n > 5) {
                $tmax += pow(10, $i);
            }
        }



        $this->_avgStep  = (int) round($avg_step / $this->_countData);
        $this->numStepY  = 5;
        $this->_totalMax = (int) $tmax;
    }

    public function init() {
        $this->_delH  = $this->config['oY_length'] / $this->_totalMax;
        $this->_stepH = $this->config['oY_length'] / $this->numStepY;
        $this->_stepW = round($this->config['oX_length'] / $this->_countData);

        $this->_drawGrid();
        $this->_drawChart();
        $this->_drawAxes();
        $this->_addLegend();
    }

    public function draw($filename = null) {
        if ($filename == null) {
            header('Content-Type: image/png');
        }
        $image = $this->_image;
        imagepng($image, $filename);
    }

    /**
     * Добавляет текст на изображения
     * @param int $x
     * @param int $y
     * @param string $text
     * @param int $color
     */
    public function string($x, $y, $text, $color, $angle = 0) {
        $font = ROOT . self::FONT;
        imagefttext($this->_image, self::FSIZE, $angle, $x, $y, $color, $font,
                    $text);
    }

    public function getTtfBbox($text, $angle = 0) {
        $font = ROOT . self::FONT;
        $arr  = imagettfbbox(self::FSIZE, $angle, $font, $text);
        return $arr;
    }

    /**
     * Рисует линию
     * @param int $x0
     * @param int $y0
     * @param int $x1
     * @param int $y1
     * @param int $color
     */
    public function line($x0, $y0, $x1, $y1, $color) {
        imageline($this->_image, $x0, $y0, $x1, $y1, $color);
    }

    private function _drawChart() {
        $img = $this->_image;

        $x0 = $this->config['oX'];
        $y0 = $this->config['oY'];
        $y1 = $this->config['oY'] - $this->config['oY_length'];
        $x1 = $this->config['oX'] + $this->config['oX_length'];

        $ac = $this->config['axes_color'];
        $tc = $this->config['total_color'];
        $sc = $this->config['success_color'];

        $delH       = $this->_delH; // $this->config['oY_length'] / $this->totalMax;
        $stepWidth  = $this->_stepW;
        $half_width = round($stepWidth / 4);
        $x0         = round($x0 - $stepWidth / 2);
        $i          = 1;
        foreach ($this->_data as $value) {
            if (is_array($value)) {
                list($total, $success) = $value;
            } else {
                $total   = $value;
                $success = null;
            }

            if ( ! $total) {
                $total = 0;
            }
            if ( ! $success) {
                $success = $total;
            }

            $totalH   = round($y0 - $total * $delH);
            $successH = round($y0 - $success * $delH);

            $x   = $x0 + $i ++ * $stepWidth;
            $_x0 = $x - $half_width;
            $_x1 = $x + $half_width;
            imagefilledrectangle($img, $_x0, $y0, $_x1, $totalH, $tc);
            imagefilledrectangle($img, $_x0, $y0, $_x1, $successH, $sc);

            $arr = $this->getTtfBbox($total);
            $x   = round($x - (($arr[2] - $arr[0]) / 2));
            $this->string($x, $totalH - 5, $total, $tc);
        }
    }

    /**
     * Отрисовать сетку
     */
    private function _drawGrid() {
        $img = $this->_image;
        $x0  = $this->config['oX'];
        $y0  = $this->config['oY'];
        $y1  = $this->config['oY'] - $this->config['oY_length'];
        $x1  = $this->config['oX'] + $this->config['oX_length'];

        $c          = $this->config['grid_color'];
        $style_grid = array($c, $c, $c, $c, $c, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT);
        $style_axes = array($this->config['axes_color']);

        // Рисуем линии по оси У с низу вверх
        $stepHeight = $this->_stepH;
        imagesetstyle($img, $style_grid);
        for ($i = 1; $i < $this->numStepY; $i ++ ) {
            $y    = round($y0 - $i * $stepHeight);
            $c    = ($grid) ? 0xFFFFFF : 0xFAFAFA;
            imagefilledrectangle($img, $x0, $y, $x1, $y - $stepHeight, $c);
            $grid = ! $grid;

            $this->line($x0, $y, $x1, $y, IMG_COLOR_STYLED);
        }
        // Рисуем обозначения по оси У с низу вверх
        imagesetstyle($img, $style_axes);
        for ($i = 0; $i <= $this->numStepY; $i ++ ) {
            $y     = round($y0 - $i * $stepHeight);
            $total = round(($i * $stepHeight) / $this->_delH);
            if ($i == $this->numStepY) {
                $str = $this->_totalMax;
                $y   = $y1;
            }
            $this->line($x0, $y, $x0 - 5, $y, IMG_COLOR_STYLED);
            $this->_stringOY($y, $total);
        }

        // Рисуем линии по оси X с лево на право
        $stepWidth = $this->_stepW;
        $x0        = round($x0 - $stepWidth / 2);
        imagesetstyle($img, $style_grid);
        $i         = 1;
        foreach ($this->_data as $key => $value) {
            $x = $x0 + $i ++ * $stepWidth;
            $this->line($x, $y0, $x, $y1, IMG_COLOR_STYLED);
            $this->_stringOX($x, $key, $this->rotation);
        }
        imagesetstyle($img, $style_axes);
        for ($i = 1; $i <= $this->_countData; $i ++ ) {
            $x = $x0 + $i * $stepWidth;
            $this->line($x, $y0, $x, $y0 + 5, IMG_COLOR_STYLED);
        }
    }

    private function _stringOX($x, $text, $rotation = false) {
        $angle = ($rotation) ? 90 : 0;
        $arr   = $this->getTtfBbox($text, $angle);
        $c     = $this->config['axes_color'];
        $y     = $this->config['oY'] + 17;


        if ($rotation) {
            $x += 5;
            $y += 25;
        } else {
            $x = round($x - (($arr[2] - $arr[0]) / 2));
        }


        $this->string($x, $y, $text, $c, $angle);
    }

    private function _stringOY($y, $text) {
        $arr = $this->getTtfBbox($text);
        $x   = $this->config['oX'] - $arr[2] - $arr[0] - 11;
        $this->string($x, $y + 5, $text, $this->config['axes_color']);
    }

    /**
     * Отрисовать оси
     */
    private function _drawAxes() {
        $img = $this->_image;
        $c   = $this->config['axes_color'];
        $oX  = $this->config['oX'];
        $oY  = $this->config['oY'];

        imageline($img, $oX, $oY, $oX + $this->config['oX_length'], $oY, $c);
        imageline($img, $oX, $oY, $oX, $oY - $this->config['oY_length'], $c);
    }

    /**
     * Добавить лигенду
     */
    private function _addLegend() {
        $filename = ROOT . self::LEGEND;
        $inf      = getimagesize($filename);
        $w        = $inf[0];
        $h        = $inf[1];
        $legend   = imagecreatefrompng($filename);
        imagecopyresampled($this->_image, $legend, $this->config['width'] - $w,
                           0, 0, 0, $w, $h, $w, $h);
        imagedestroy($legend);
    }

    private $_log = 0;

    public function log($str) {
        $this->string(100, $this->_log, $str, 0x000000);
        $this->_log += 15;
    }

    /**
     * destruct
     */
    public function __destruct() {
        if ($this->_image) {
            imagedestroy($this->_image);
        }
    }

    public function destroy() {
        imagedestroy($this->_image);
        $this->_image = null;
    }
}
// ----------------------------------------------------------------------------