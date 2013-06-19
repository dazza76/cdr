function oper_copy_select(where) {
	element = document.getElementById('copy_list_' + where);
	from = element.value;

	if (where == from) exit();

	if (confirm('Вы уверены? Действие нельзя отменить!')) {
		move_on = 'copy.php?from=' + from + '&where=' + where + '&date=<?php echo "$_GET[year]-$_GET[month]";?>';
		document.location = move_on;
	} else {
		move_on = 'index.php';
		document.location = move_on;
	}
}

function oper_copy(oper) {
	var oper_copy_select = function(where) {
		alert('Test');
	}
	element = document.getElementById('oper_' + oper);
	output = "<SELECT name=\"test\" onchange=\"oper_copy_select('" + oper + "')\" id=\"copy_list_" + oper + "\">";
	output += "<OPTION value=\"none\">Скопировать</OPTION>";
	// output += "<?php $tmp = mysql_query("SELECT DISTINCT agentid FROM queue_agents ORDER BY name ASC;") or die(mysql_error()); while($tmp_row = mysql_fetch_array($tmp)) { echo("<OPTION value=\\\"$tmp_row[0]\\\">".showoper($tmp_row[0])."</OPTION>"); }; ?>";
	output += "</SELECT>";
	element.innerHTML += output;
	element.onclick = '';
	//	alert(element.innerHTML);
}

function plan_submit(cell) {
	element = document.getElementById(cell + '_plan_value');
	value = element.value;
	move_on = 'replan.php?cell=' + cell + '&value=' + value;
	document.location = move_on;
}

function plan_edit(cell) {
	var plan_submit = function(cell) {
		alert('Test');
	}
	element = document.getElementById(cell + '_plan');
	temp = element.innerHTML;
	element.innerHTML = "<input placeholder=\"ЧЧ\" type=text id=\"" + cell + "_plan_value\" value=" + temp + " class=\"time\"/><br/><input type=\"button\" value=\"OK\" onclick=\"plan_submit('" + cell + "')\" />";
	element.onclick = '';
}

function day_submit(cell, type) {
	if (type == 'job') {
		hour = document.getElementById(cell + ':' + type + '_value_hour').value;
		minute = document.getElementById(cell + ':' + type + '_value_min').value;
		length = document.getElementById(cell + ':' + type + '_value_length').value;
		quant = document.getElementById(cell + ':' + type + '_value_quant').value;
		data = hour + ":" + minute + ":" + length + ":" + quant;
	} else {
		data = document.getElementById(cell + ':' + type + '_value').value;
	}
	move_on = 'reschedule.php?view=<?php echo $_GET[year] . ' - ' . $_GET[month];?>&data=' + cell + ':' + type + ':' + data;
	document.location = move_on;

}

function day_select(cell) {
	var day_submit = function(cell, type) {
		alert('Test');
	}
	list = document.getElementById(cell + 'select');
	value = list.value;
	switch (value) {
		case 'off':
			element = document.getElementById(cell);
			output = '<select id=\"' + cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value="off" selected>Выходной</option>';
			output += '<option value=\"vac\">Отпуск</option>';
			output += '<option value=\"ill\">Больничный</option>';
			output += '<option value=\"job\">Смена</option>';
			output += '</select>';
			output += 'Дней: <input id=\"' + cell + ':off_value\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			id = cell + ':off_text';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell + '\',\'off\')\" />';
			break;
		case 'ill':
			element = document.getElementById(cell);
			output = '<select id=\"' + cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value=\"off\" selected>Выходной</option>';
			output += '<option value=\"vac\">Отпуск</option>';
			output += '<option value=\"ill\" selected>Больничный</option>';
			output += '<option value=\"job\">Смена</option>';
			output += '</select>';
			output += 'Дней: <input id=\"' + cell + ':ill_value\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			id = cell + ':ill_text';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell + '\',\'ill\')\" />';
			break;
		case 'vac':
			element = document.getElementById(cell);
			output = '<select id=\"' + cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value=\"off\" selected>Выходной</option>';
			output += '<option value=\"vac\" selected>Отпуск</option>';
			output += '<option value=\"ill\">Больничный</option>';
			output += '<option value=\"job\">Смена</option>';
			output += '</select>';
			output += 'Дней: <input id=\"' + cell + ':vac_value\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			id = ':vac_text';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell + '\',\'vac\')\" />';
			break;
		case 'job':
			output = '<select id=\"' + cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value=\"off\">Выходной</option>';
			output += '<option value=\"vac\">Отпуск</option>';
			output += '<option value=\"ill\">Больничный</option>';
			output += '<option value=\"job\" selected>Смена</option>';
			output += '</select><br/>';
			output += 'Начало:<br/><input id=\"' + cell + ':job_value_hour\" type=\"text\" placeholder=\"ЧЧ\" class=\"time\" maxlength=\"2\" value=\"08\"/>:<input id=\"' + cell + ':job_value_min\" type=\"text\" placeholder=\"ММ\" class=\"time\" maxlength=\"2\" value=\"00\"/><br/>Длительность:<br/>';
			output += '<select id=\"' + cell + ':job_value_length\">';
			output += '<option value=\"6\">6 часов</option>';
			output += '<option value=\"8\">8 часов</option>';
			output += '<option value=\"12\">12 часов</option>';
			output += '<option value=\"24\">24 часа</option>';
			output += '</select><br/>';
			output += 'Дней: <input id=\"' + cell + ':job_value_quant\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell + '\',\'job\')\" />';
			break;
	}
	element.innerHTML = output;
}

function day_click(cell, type) {
	var day_select = function(cell) {
		alert('Test');
	}
	element = document.getElementById(cell);
	if (!(element.innerHTML.indexOf('<select>') + 1)) {
		var output = '<select id=\"' + cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
		output += '<option value="off"';
		if (type == 'off') output += ' selected';
		output += '>Выходной</option>';
		output += '<option value=\"vac\"';
		if (type == 'vac') output += ' selected';
		output += '>Отпуск</option>';
		output += '<option value=\"ill\"';
		if (type == 'ill') output += ' selected';
		output += '>Больничный</option>';
		output += '<option value=\"job\"';
		if (type == 'job') output += ' selected';
		output += '>Смена</option>';
		output += '</select>';
	}
	element.innerHTML = output;
	element.onclick = '';
}


var Schedule = {

};


$(document).ready(function() {
	var $table = $(".schedule");
	if (!$table.length) {
		return;
	}


	var $select = $("<select name=\"event\"> \n\
<option value=\"off\">Выходной</option>\n\
<option value=\"vac\">Отпуск</option>\n\
<option value=\"ill\">Больничный</option>\n\
<option value=\"job\">Смена</option>\n\
</select>");

	var $job = $("<br />\n\
Начало:<br />\n\
<input name=\"time_h\" type=\"text\" placeholder=\"ЧЧ\" maxlength=\"2\" value=\"\" />:\n\
<input name=\"time_m\" type=\"text\" placeholder=\"ММ\" maxlength=\"2\" value=\"\" /><br />\n\
Длительность:<br />\n\
<select name=\"duration\">\n\
    <option value=\"6\">6 часов</option>\n\
    <option value=\"8\">8 часов</option>\n\
    <option value=\"12\">12 часов</option>\n\
    <option value=\"24\">24 часа</option>\n\
</select>");

	var $days = $("<br />\n\
Дней: <input name=\"days\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\" /><br />\n\
<input type=\"submit\" id=\"act-schedule\" id=\"button-search\" class=\"button ui-button ui-widget ui-state-default ui-corner-all\" value=\"Сохранить\" />");

	var data = {
		act: "schedule"
	};

	$table.find('tbody tr').each(function() {
		var $tr = $(this);
		$(this).find('td[date]').click(function() {
			var $td = $(this);

			if ($(this).hasClass('edit')) {
				return;
			}
			if ($(this).index)


			// Удаляем editor у всех
			$table.find("tbody td.edit").each(function() {
				$(this).removeClass('edit').html($(this).data('html'));
			});

			// Сохраняем параметры
			$(this).addClass('edit');
			$(this).data('html', $(this).html());
			$(this).html($select);

			$select.change(function() {
				// console.log($(this).val());
				if ($(this).val() == 'job') {
					$td.append($job).append($days);
				} else {
					$td.append($days);
					$job.remove();
				}
			}).change();

			$("#act-schedule").click(function() {
				var data = {
					act: "create",
					agentid: $tr.attr('agentid'),
					date: $td.attr('date'),
					event: $td.find("select[name='event']").val(),
					time_h: $td.find("input[name='time_h']").val(),
					time_m: $td.find("input[name='time_m']").val(),
					duration: $td.find("select[name='duration']").val(),
					days: $td.find("input[name='days']").val()
				};
				console.log(data);

				$ajax(data, function(result) {
					if (result ) {
						refresh();
					}
					// } else {
					// 	console.log("Error : " + result);
					// }
					console.log(result);
				});
			});
		});
	});
});