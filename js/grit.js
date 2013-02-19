/*********
 * result table
 *********/
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



    /**
     * edit comment control
     */
    var $cedit = $("#comment_edit");
    var $editBox = $("#comment_edit .edit-box").first();
    var rowComment = {
        id: null,
        comment: "",
        $span: null,
        initRow: function (id, span) {
            rowComment.id = id;
            rowComment.$span = span;
            rowComment.comment = $(span).text().toString();
        },
        editRow: function (str) {
            var $edit_span = rowComment.$span;
            var edit_id = rowComment.id;
            str = $.trim(str);
            if (!rowComment.comment) {
                rowComment.comment = "";
            }
            if (!str) {
                str = "";
            }
            if (rowComment.comment === str || !rowComment.id) {
                console.log("comment cancel");
                return;
            }
            $edit_span.text("loading...");
            var data = {
                act: "editComment",
                id: rowComment.id,
                comment: str
            };
            $.ajax({
                type: "POST",
                cache: false,
                data: data
            }).done(function (result) {
                console.log("comment save: " + edit_id);
                $edit_span.text(str);
            }).fail(function () {
                $edit_span.text("error");
                console.log("CONNECT ERROR");
            });
        }
    };

    $editBox.keydown(function (evt) {
        // enter = 13
        // esc = 27
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode === 13) {
            $editBox.blur();
            return false;
        }
        if (charCode === 27) {
            rowComment.id = null;
            $editBox.blur();
            return false;
        }
        return true;
    });
    $editBox.blur(function () {
        var str = $editBox.val().toString();
        rowComment.editRow(str);
        rowComment.id = null;
        $editBox.val("");
        $cedit.hide();
    });
    $table.find("tbody td.grid-edit").click(function () {
        var id = $(this).parent().attr("callid");
        var span = $(this).find("span");
        rowComment.initRow(id, span);

        $cedit.css({
            top: $(this).position().top,
            left: $(this).position().left,
            width: $(this).outerWidth(),
            height: $(this).outerHeight()
        }).show();
        $editBox.val(rowComment.comment);
        $editBox.focus();
    });

});