$(document).ready(function () {
    var $table = $(".grid");
    if (!$table.length) {
        return;
    }

    $table.find("thead th.sortable[data-sort]").each(function () {
        var txt = ($(this).attr("data-sort") == "desc") ? "&nbsp;▼" : "&nbsp;▲";
        txt = $(this).html() + txt;
        $(this).html(txt);
    });

    $table.find("thead th.sortable").click(function () {
        var sort = $(this).attr("data-column");
        var desc = $(this).attr("data-sort"); //.toString();
        $(".filters form input[name=offset]").val(0);

        desc = (desc == 'asc') ? 1 : '';
        $(".filters form input[name=sort]").val(sort);
        $(".filters form input[name=desc]").val(desc);
        $(".filters form").submit();
    });
});