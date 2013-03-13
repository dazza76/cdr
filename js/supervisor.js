$(document).ready(function() {
    // starttick();
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
    update: 0,
    interval: 1000,
    init: function() {
        var self = supervisor;
        if (supervisor.update) {
            $('#input-dynamic_update').attr('checked', 'checked');
        }
        $('#input-dynamic_update').change(function() {
            self.update = ($(this).attr('checked')) ? 1 : 0;
            $.cookie('supervisor_update', self.update);
        });

        $('#input-update_interval').val(supervisor.interval);
        $('#input-update_interval').change(function() {
            self.interval = $(this).val();
            $.cookie('supervisor_interval', self.interval);
        });
    },

    startUpdate: function() {
        var self = supervisor;
        self._timeoutId = setTimeout(function() {
            self.update();
        }, self.interval);
    },
    stopUpdate: function() {
        var self = supervisor;
        if (self._timeoutId !== false) {
            clearTimeout(self._timeoutId);
        }
        self._timeoutId = false;
    },
    update: function() {
        var self = supervisor;
        self.stopUpdate();

        console.log("update");
        self.startUpdate();
    }
};