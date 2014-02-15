<?

?></title>
<style type="text/css">
<!--
.style5 {color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.style6 {font-size: 10px}
.style9 {color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10; }
-->
</style>
<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="vai" value="1">
<span class="style5"><? echo enviando(); ?></span>
<table width="422" border="0" bgcolor="#000000">
  <tr>
<td width="66"><span class="style5">Nome:</span></td>
<td width="346"><span class="style9">

<label>
<input name="nome" type="text" value="<? echo $_POST['nome'] ;?>" size="20" />
</label>
</span></td>
</tr>
<tr>
<td><span class="style5">Email:</span></td>
<td><input name="de" type="text" value="<? echo $_POST['de'] ;?>" size="30" /></td>

</tr>
<tr>
<td><span class="style5">Assunto:</span></td>
<td><input name="assunto" value="<? echo $_POST['assunto'] ;?>" size="40" /></td>
</tr>
<tr>
<td><span class="style5">Mensagem:</span></td>
<td><span class="style9">

<p><textarea name="mensagem" cols="50" rows="7"><? echo stripslashes($_POST['mensagem']);?>
</textarea></p>
<textarea name="emails" cols="50" rows="4"></textarea>
</span></td>
</tr>

<tr>
  <td><span class="style6"></span></td>
  <td><input name="Submit" type="submit" value="Enviar" /></td>
</tr>
<tr>
  </tr>
</table>
</form>
