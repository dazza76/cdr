<?php
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
        $menu = $submenu = '';

        echo "<ul class=\"fl_l clear\">";
        foreach (App::Config()->pages as $name => $page) {
            $sections = $page['section'];
            $page = html($page['page']);

            if ($c[$name]) {
                $menu .= "<li class=\"submenu\"><span class=\"submenu-title\">"
                        . "<a href=\"{$name}.php\" class=\"header-icon icon-{$name}-big\"> {$page}: </a> "
                        . "</span></li>";
            } else {
                $menu .= "<li>"
                        . "<a href=\"{$name}.php\" class=\"header-icon icon-{$name}-big\"> {$page} </a>"
                        . "</li>";
            }

            if ($c[$name] && $sections) {
                $sm[$this->getSection()] = 'current';
                foreach ($sections as $key => $value) {
                    $submenu .= "  <li class=\"{$sm[$key]}\"> <a href=\"{$name}.php?section={$key}\"> " . html($value) . " </a> </li>\n";
                }
                $submenu = "<ul class=\"fl_l clear\" style=\"padding: 10px 6px;\">" . $submenu . "</ul>";
            }
        }

        echo $menu;
        echo $submenu;
        echo "</ul></ul>";
        ?>
    </ul>
</div>
