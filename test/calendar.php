<td align="center" rowspan=2>
<script src="calendar.js"></script>
<table border=0 align="center">
    <tr>
      <td align="center" width="150px"> Начиная с </td>
      <td align="center" colspan="2"> Дата: <input size="8" name="fromdate" type="text" value="<?php echo $_GET["fromdate"]?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" >
      </td>
      <td align="center" >Час:Мин</td>
      <td align="center"><input name="fromhour" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["fromhour"]))	echo $_GET["fromhour"];	else echo "00";?>">
        :
      <td align="center"><input name="frommin" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["frommin"]))	echo $_GET["frommin"];	else echo "00";?>">
      </td>
      </tr>
      <tr>
      <td align="center"> Заканчивая </td>
      <td align="center" colspan="2"> Дата: <input size="8" name="todate" type="text" value="<?php echo $_GET["todate"]?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" ></td>
      <td align="center" >Час:Мин</td>
      <td align="center"><input name="tohour" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["tohour"]))	echo $_GET["tohour"];	else echo "00";?>">
        :
      <td align="center"><input name="tomin" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["tomin"]))	echo $_GET["tomin"];	else echo "00";?>">
      </td>
    </tr>
</table>
