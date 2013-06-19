<?php
/* AC: v: */

/**
 * header file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
$c[$this->getPage()] = 'current';
?>

<div id="header" class="fixed clear_fix">
    <ul class="menu clear_fix">
        <?php
        foreach (App::Config()->pages as $name => $page) {
            $section = $page['section'];
            $page    = html($page['page']);

            if ($section) {
                if ($c[$name]) {
                    $sm[$this->getSection()] = 'current';

                    $submenu = "<li class=\"submenu\">\n"
                            . "<span class=\"submenu-title\"> <a href=\"{$name}.php\" class=\"header-icon icon-{$name}-big\"> {$page}: </a> </span>\n"
                            . "<ul>\n";
                    foreach ($section as $key => $value) {
                        $submenu .= "  <li class=\"{$sm[$key]}\"> <a href=\"{$name}.php?section={$key}\"> " . html($value) . " </a> </li>\n";
                    }
                    $submenu .= "</ul>";
                } else {
                    $submenu = "<li>"
                            . "<a href=\"{$name}.php\" class=\"header-icon icon-{$name}-big\"> {$page} </a>"
                            . "</li>";
                }
            } else {
                $submenu = "<li class=\"{$c[$name]}\">"
                        . "<a href=\"{$name}.php\" class=\"header-icon icon-{$name}-big\"> {$page} </a>"
                        . "</li>";
            }
            echo $submenu;
        }
        ?>
    </ul>
</div>
