$(document).ready(function() {
    Supervisor.section = $('#input-section').val();

    $('#input-dynamic_update').change(function() {
        Supervisor.init();
    });

    $("#input-update_interval").change(function() {
        Supervisor.init();
    }).spinner({
        spin: function(event, ui) {
            if (ui.value < 0) {
                $(this).spinner("value", 0);
                return false;
            }
            $(this).spinner("value", ui.value);
            Supervisor.init();
            return false;
        }
    });

    Supervisor.init();
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



var Supervisor = {
    _timeoutId: false,
    onUpdate: 1,
    interval: 2,
    section: null,
    init: function() {
        var self = Supervisor;

        if (typeof window.pageOptions !== 'undefined') {
            $.extend(self, pageOptions);
        }

        // self.onUpdate = ($('#input-dynamic_update').attr('checked')) ? 1 : 0;
        // self.interval = $("#input-update_interval").val();
        // self. = $("#input-update_interval").val();

        console.log('init section: ' + self.section + '; on:' + self.onUpdate + '; interval: ' + self.interval);

        // $.cookie('Supervisor_update', self.onUpdate);
        // $.cookie('Supervisor_interval', self.interval);

        $('#queueAgents').parent().append("<div id=\"ajaxlog\"></div>");

        self.startUpdate();
    },

    /**
     * Запустить механизм автообнавления
     */
    startUpdate: function() {
        var self = Supervisor;
        self.stopUpdate();

        if (!self.onUpdate) {
            return;
        }

        var interval = parseInt(self.interval) * 1000;
        if (interval > 0) {
            // console.log('start interval ' + interval);
            self._timeoutId = setTimeout(function() {
                self.onUpdateHandler();
            }, interval);
        }

        // return 'start';
    },

    /**
     * Остановить механизм автообнавления
     */
    stopUpdate: function() {
        var self = Supervisor;
        if (self._timeoutId !== false) {
            clearTimeout(self._timeoutId);
        }
        self._timeoutId = false;
    },
    onUpdateHandler: function() {
        var self = Supervisor;

        $.ajax({
            type: "POST",
            cache: false,
            data: {
                act: 'update'
            }
        }).done(function(result) {
            //console.log("[API] Supervisor::update> " + result);
            try {
                var response = JSON.parse(result).response;
                // console.log(response);
                if (self.section == 'queue') {
                    self._updateSectionQueue(response.queuesData);
                }
                if (self.section == 'operator') {
                    self._updateSectionOperator(response);
                }
            } catch (e) {
                console.log(e);
            }

            self.startUpdate();
        }).fail(function() {
            console.log("CONNECT ERROR");
            // window.alert('error save comment');
        });
    },
    _updateSectionQueue: function(queuesData) {
        var $tabel = $('#queuesData tbody');
        for (var i in queuesData) {
            var $tr = $tabel.find('tr[queueid=' + i + ']');
            var queue = queuesData[i];
            for (var qname in queue) {
                $tr.find('td[queue=' + qname + '] span').text(queue[qname]);
            }
        }
    },
    _updateSectionOperator: function(data) {

        var da = data.queueAgents;
        var lg = [];
        for (var i in da) {
            lg[i] = da[i] = da[i].agentid + "; " + da[i].member + "; " + da[i].phone + "; " /* + da[i].queues.replace(/<br \/>/g, ',') + "; " */ + da[i].state_phone + "; ";
        }
        $("#ajaxlog").html(lg.join("<br />"));



        var $tableAgent = $('#queueAgents tbody');
        for (var i in data.queueAgents) {
            var qAgent = data.queueAgents[i];
            var $tr = $tableAgent.find('tr[agentid=' + qAgent.agentid + ']');
            $tr.find('td[agent=state_phone]').text(qAgent.state_phone);
            $tr.find('td[agent=queues]').html(qAgent.queues);
        }

        var $tableData = $('#queuesData tbody');
        for (var i in data.queuesData) {
            var qData = data.queuesData[i];
            $tableData.find('td[queue=' + i + '] span').text(qData);
        }

        var n = 0;
        chartOptions.series[n++].data = [data.queueChart.free];
        chartOptions.series[n++].data = [data.queueChart.used];
        chartOptions.series[n++].data = [data.queueChart.aftercall];
        chartOptions.series[n++].data = [data.queueChart.paused];
        chartOptions.series[n++].data = [data.queueChart.ringing];


        chart = new Highcharts.Chart(chartOptions);
    }
};