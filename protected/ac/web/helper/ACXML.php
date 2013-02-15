w<?php
/**
 * ACXML class  - ACXML.php file
 *
 * @package    AC
 * @author     Tyurin D.V. <tyurin_dv@almazovcentre.ru>
 * @copyright   (c) 2013, CMRI http://www.almazovcentre.ru
 * @link       http://www.almazovcentre.ru
 */

/**
 * ACXML
 * @package		AC
 */
class ACXML {

  /**
   * Преобразование масива в XML.
   *
   * @param  array $array масив
   * @return string
   */
  public static function encode($array, $tab = "", $is_list = false) {
    if (!is_array($array)) {
      return $tab . "<value>{$array}</value>";
    }

    $str   = "";
    $i     = 0;
    $assoc = false;
    foreach ($array as $key => $value) {
      if (!is_numeric($key)) {
        $assoc = true;
        break;
      } else {
        if ($key != $i) {
          $assoc = true;
          break;
        }
      }
      $i++;
    }

    $row = "";
    $n   = "";
    foreach ($array as $key => $value) {
      $row_arr = false;
      $list    = false;
      if (is_array($value)) {
        $_tab = $tab . " ";
        $row  = self::encode($value, $_tab, true);
        if (is_array($row)) {
          $list    = $row["list"];
          $row     = $row["value"];
        }
        $row_arr = true;
      } else {
        $row = $value;
      }

      $str .= $n;
      $n = "\n";
      if ($assoc) {
        if ($row_arr) {
          if ($list) {
            $str .= $tab . "<" . $key . ' list="' . $list . '">' . "\n";
          } else {
            $str .= $tab . "<" . $key . ">";
          }
          if (strlen(trim($row)) > 0) {
            $str .= "\n" . $row . "\n";
          }
          //$str .= $row;
          $str .= $tab . "</{$key}>";
        } else {
          $str .= $tab . "<{$key}>" . $row . "</{$key}>";
        }
      } else {
        if ($row_arr) {
          $str .= $tab . "<item key=\"{$key}\">\n";
          $str .= $row;
          $str .= "\n" . $tab . "</item>";
        } else {
          $str .= $tab . '<item key="{$key}">' . $row . "</item>";
        }
      }
    }

    if ($is_list) {
      if (!$assoc) {
        $str = array("value" => $str, "list"  => count($array));
      }
    }

    return $str;
  }
}
