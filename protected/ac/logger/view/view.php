<?php
/**
 * view.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

$i = 0;
?>
<style type="text/css">
    #ac-logger-switch { position: fixed; background: #FF0000; top: 5px; left: 0px; width: 30px; height: 15px; z-index: 1000001; cursor: pointer; }
    #ac-logger { position: fixed; top: 0px; left: 0px; z-index: 1000000; font: 9px Tahoma, Geneva, sans-serif; border-bottom: 3px solid black;  height: 60%; width: 80%;   }
    #ac-logger div.content { background: #CCC; overflow: auto; width: 100%; height: 100%; }
    #ac-logger thead { background: #999; }
    #ac-logger table tr { font-family: monospace; font-size: 11px; }
    #ac-logger thead tr th { vertical-align: top; white-space: pre; font-weight: bold; text-align: left; }
    #ac-logger tbody tr td { vertical-align: top; text-align: left; }
    #ac-logger  tr .number { color: #888a85; }
    #ac-logger  tr .time { color: #f57900; }
    #ac-logger  tr .category { color: #4e9a06; }
    #ac-logger  tr .level { color: #3465a4; }
    #ac-logger  tr .messag { color: #888a85; }
    #ac-logger  tr.error { background: rgb(255, 179, 179);  }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $("#ac-logger").resizable();
        $("#ac-logger-switch").click(function() {
            $("#ac-logger").toggleClass("hidden");
        });
        $("#ac-logger td.level:contains(error)").parent('tr').addClass('error');
    });
</script>
<div id="ac-logger-switch">DBG</div>
<div id="ac-logger" class="hidden">
    <div class="content">
        <table style="width: 100%;">
            <thead>
                <tr >
                    <th class="number"   style="width: 30px;">№</th>
                    <th class="time"     style="width: 70px;"><?php printf("%-8s", 'time'); ?></th>
                    <th class="level"    style="width: 85px;"><?php printf("%-9s", 'level'); ?></th>
                    <th class="category" style="width: 70px;"><?php printf("%-9s", 'category'); ?></th>
                    <th class="message">message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($Logs as $row) {?>
                    <tr>
                        <td class="number"><?php printf("%'02d", ++ $i); ?></td>
                        <td class="time"><?php printf("%-9s", $row['time']); ?></td>
                        <td class="level"><?php html(printf("%-9s", '[' . $row['level'] . ']')); ?></td>
                        <td class="category"><?php html(printf("%-9s", $row['ctg'])); ?></td>
                        <td class="message"><?php echo $row['msg']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>