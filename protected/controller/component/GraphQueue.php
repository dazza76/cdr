<?php
/**
 * GraphQueue class  - GraphQueue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * GraphQueue class
 * @package		AC
 */
class GraphQueue extends ChartQueue {

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

}