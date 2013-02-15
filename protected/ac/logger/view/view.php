<?php
/**
 * view.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

?>
<script type="text/javascript">
$(document).ready(function () {
    $("#ac-logger-switch").click(function () {
        $("#ac-logger").toggleClass("hidden");
    });
});
</script>
<div id="ac-logger-switch">DBG</div>
<div id="ac-logger" class="hidden">
    <table style="width: 100%;">
        <thead>
            <tr >
                <th class="number"   style="width: 30px;">№</th>
                <th class="time"     style="width: 70px;"><?php printf("%-8s", 'time'); ?></th>
                <th class="category" style="width: 70px;"><?php printf("%-9s", 'category'); ?></th>
                <th class="level"    style="width: 85px;"><?php printf("%-9s", 'level'); ?></th>
                <th class="message">message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($logs as $row) {
                list($msg,  $category, $level, $time) = each($row);
                ?>
                <tr>
                    <td class="number"><?php printf("%'02d", ++$i); ?></td>
                    <td class="time"><?php printf(" %01.6f", $row['time'] - TIME_START); ?></td>
                    <td class="category"><?php html(printf("%-9s", $row['ctg'])); ?></td>
                    <td class="level"><?php html(printf("%-9s", '[' . $row['level'] . ']')); ?></td>
                    <td class="message"><?php echo html($row['msg']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>