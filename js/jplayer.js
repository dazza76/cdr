$(document).ready(function () {
    $("#jp_container_1").css({
        top: "-1000px",
        left: "-1000px"
    });
    $(".player-button").click(function () {
        var $player = $("#jquery_jplayer");
        $(".player-button").removeClass("icon-pause").toggleClass("icon-play", true);

        if ($(this).attr("play") === "play") {
            $(this).attr("play", "pause");
            $(this).toggleClass("icon-play", true);
            $player.jPlayer("pause");
        } else if ($(this).attr("play") === "pause") {
            $(this).attr("play", "play");
            $(this).toggleClass("icon-pause", true);
            $player.jPlayer("play");
        } else {
            $player.jPlayer("stop");
            var audiofile = $(this).find("input").val();
            var file = "http://" + document.domain + audiofile;
            var setMedia = {};
            setMedia[rec.format] = file;

            $player.jPlayer("setMedia", setMedia);
            console.log(file);

            $(".player-button").removeAttr("play");

            $(this).attr("play", "play");
            $(this).toggleClass("icon-pause", true);

            var $slider = $(this).parent().find(".player-slider").first();
            $("#jp_container_1").css({
                top: $slider.position().top + 8,
                left: $slider.position().left + 7
            });
            $player.jPlayer("play");
        }
    });
});