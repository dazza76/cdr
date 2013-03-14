$(document).ready(function() {
    supervisor.section = $('#input-section').val();

    $('#input-dynamic_update').change(function() {
        supervisor.init();
    });

    $("#input-update_interval").change(function() {
        supervisor.init();
    }).spinner({
        spin: function(event, ui) {
            if (ui.value < 0) {
                $(this).spinner("value", 0);
                return false;
            }
            $(this).spinner("value", ui.value);
            supervisor.init();
            return false;
        }
    });

    supervisor.init();
});

function starttick() {
    var id = setTimeout(function() {
        tick();
        starttick();
    }, 1000);
}

function tick() {
    $("span[tick=sec]").each(function() {
        $(this).text(parseInt($(this).text()) + 1);
    });
    $("span[tick=time]").each(function() {
        var arr = $(this).text().split(':');
        arr[0] = parseInt(arr[0]);
        arr[1] = parseInt(arr[1]);
        arr[2] = parseInt(arr[2]) + 1;

        if (arr[2] >= 60) {
            arr[2] = 0;
            arr[1]++;
        }

        if (arr[1] >= 60) {
            arr[1] = 0;
            arr[0]++;
        }

        if (arr[0] < 10) arr[0] = '0' + arr[0];
        if (arr[1] < 10) arr[1] = '0' + arr[1];
        if (arr[2] < 10) arr[2] = '0' + arr[2];
        $(this).text(arr.join(':'));
    });
}

var supervisor = {
    _timeoutId: false,
    onUpdate: 0,
    interval: 1,
    section: null,
    init: function() {
        var self = supervisor;

        self.onUpdate = ($('#input-dynamic_update').attr('checked')) ? 1 : 0;
        self.interval = $("#input-update_interval").val();

        console.log('init section: ' + self.section + '; on:' + self.onUpdate + '; interval: ' + self.interval);

        $.cookie('supervisor_update', self.onUpdate);
        $.cookie('supervisor_interval', self.interval);

        self.startUpdate();
    },

    startUpdate: function() {
        var self = supervisor;
        self.stopUpdate();

        if (!self.onUpdate) {
            return;
        }

        var interval = parseInt(self.interval) * 1000;
        if (interval > 0) {
            // console.log('start interval ' + interval);
            self._timeoutId = setTimeout(function() {
                self.actUpdate();
            }, interval);
        }
    },
    stopUpdate: function() {
        var self = supervisor;
        if (self._timeoutId !== false) {
            clearTimeout(self._timeoutId);
        }
        self._timeoutId = false;
    },
    actUpdate: function() {
        var self = supervisor;

//        $.ajax({
//            type: "POST",
//            cache: false,
//            data: {
//                act: 'update'
//            }
//        }).done(function(result) {
//            console.log("[API] supervisor::update> " + result);
//            try {
//                var response = JSON.parse(result).response;
//                console.log(response);
//            } catch (e) {
//                console.log(e);
//            }
//        }).fail(function() {
//            console.log("CONNECT ERROR");
//            // window.alert('error save comment');
//        });

        self.startUpdate();
    }
};