var supervisor = {
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
    }
};