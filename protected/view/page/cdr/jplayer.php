<?php
/**
 * jplayer.php file
 *
 * Аудиоплеер
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

//
$dir    = App::Config()->cdr->monitor_dir . '/';
$format = App::Config()->cdr->file_format;
?>
<script type="text/javascript">
    var rec = {
        directory: '<?php echo $dir; ?>',
        format: '<?php echo $format; ?>'
    };
    $(document).ready(function() {
        $("#jquery_jplayer").jPlayer({
            swfPath: "lib/player",
            supplied: rec.format,
            wmode: "window"
        });
    });
</script>

<div id="jquery_jplayer" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio">
    <div class="jp-type-single">
        <div class="jp-gui jp-interface">
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>

            <div class="jp-time-holder">
                <div class="jp-current-time"></div>
            </div>
        </div>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>