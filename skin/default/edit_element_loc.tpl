{*
   +----------------------------------------------------------------------+
   | DILPS - Distributed Image Library System                             |
   +----------------------------------------------------------------------+
   | Copyright (c) 2002-2004 Juergen Enge                                 |
   | juergen@info-age.net                                                 |
   | http://www.dilps.net                                                 |
   +----------------------------------------------------------------------+
   | This source file is subject to the GNU General Public License as     |
   | published by the Free Software Foundation; either version 2 of the   |
   | License, or (at your option) any later version.                      |
   |                                                                      |
   | Distributed Playout Infrastructure is distributed in the hope that   |
   | it will be useful,but WITHOUT ANY WARRANTY; without even the implied |
   | warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.     |
   | See the GNU General Public License for more details.                 |
   |                                                                      |
   | You should have received a copy of the GNU General Public License    |
   | along with this program; if not, write to the Free Software          |
   | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA            |
   | 02111-1307, USA                                                      |
   +----------------------------------------------------------------------+
*}
<!-- --------------------------------------------
BEGIN edit_element_loc.tpl
--------------------------------------------- -->
{if $config.utf8 eq "true"}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/result.conf.utf8"}
{else}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/result.conf"}
{/if}

<html>
<head>
  <meta name="robots" content="index,follow">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="expires" content="0">
  <meta http-equiv="cache-control" content="no-cache">
  <meta name="keywords" content="Bilddatenbanksystem, Bilddatenbank, Diathek, digitalisiert">
  {if $config.utf8 eq 'true'}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  {else}
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  {/if}
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta name="author" content="j�rgen enge, thorsten w�bbena">
  <meta name="date" content="2003-01-23">
  <link rel="shortcut icon" href="favicon.ico">
  <title>. : {#title#|escape:"htmlall"} : .  [[{$query.id}]]</title>
  <script src="dilps.lib.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="css.php">
<script language="javascript">
{literal}
function setLocation()
{
   if( document.Main.city.value != "" ) {

	   window.opener.document.forms["Main"].elements["edit[city]"].value=document.Main.city.value;
	   window.opener.document.forms["Main"].elements["edit[locationid]"].value="new";

   } else {

	   window.opener.document.forms["Main"].elements["edit[city]"].value=locations[document.Main.elements["query[city]"].value];
	   window.opener.document.forms["Main"].elements["edit[locationid]"].value=document.Main.elements["query[city]"].value;
	}

	if( document.Main.institution.value != "" )
	   window.opener.document.forms["Main"].elements["edit[institution]"].value=document.Main.institution.value;
	else
	   window.opener.document.forms["Main"].elements["edit[institution]"].value=document.Main.elements["query[institution]"].value;

	close();
}

function searchLocation()
{
   if (document.Main.elements["query[citysearch]"].value != "")
	   document.Main.submit();
}

function city_selected()
{
   document.Main.elements["query[cityname]"].value = locations[document.Main.elements["query[city]"].value];
   document.Main.submit();
}


function setFocus()
{
	document.Main.elements["query[city]"].focus();
}

locations = new Array();

{/literal}
</script>
</head>
<body class="main" onLoad="setFocus();">
<form name="Main" action="{$SCRIPT_NAME}" method="POST">
<input type="hidden" name="PHPSESSID" value="{$sessionid}">
<input type="hidden" name="query[id]" value="{$query.id|escape:html}">
<input type="hidden" name="query[element]" value="{$query.element|escape:html}">
<input type="hidden" name="query[cityname]" value="">

<table border="0">
<tr>
	<td style="vertical-align: bottom;">
		<!--bk: -1 +2 <b>{#city#|escape:htmlall}</b><br>&nbsp;<br>-->
		<b>{#city#|escape:htmlall}</b><br>
		<input type="text" name="query[citysearch]" value="{$query.citysearch|escape:html}" size="30" tabindex="1">&nbsp;
		<input type="button" name="search" value="{#search#|escape:html}" onClick="searchLocation();" tabindex="2"><br>

		<select name="query[city]" size="20" onChange="city_selected();" tabindex="3">
			<option value=""{if $query.city eq ""} selected{/if}>--------------------------------------------------------------------</option>
			{city_list var="cityresult" sql="sql" frommeta="frommeta" query=$query}
			<!-- {$sql} -->
			<script language="javascript">
			{foreach from=$cityresult item=row}
			locations["{$row.id}"] = "{$row.location}";
			{/foreach}
			</script>

			{foreach from=$cityresult item=row}
			{if $row.location ne ""}
			<option value="{$row.id}"{if $query.city eq $row.id} selected{/if}>{$row.location|escape:htmlall}{if $row.hierarchy != ''} ({$row.hierarchy}){/if}</option>
			<!--<option value="{$row.id}"{if $query.cityname eq $row.location} selected{/if}>{$row.location|escape:htmlall}{if $row.hierarchy != ''} ({$row.hierarchy}){/if}</option>-->
			{/if}
			{/foreach}
		</select>
		<p>
		<b>{#city#|escape:htmlall}</b><br>
		<input type="text" name="city" value="" size="30" tabindex="5">
	</td>
	<td style="vertical-align: bottom;">
	   {if $query.cityname ne ""}
		<b>{#institution#|escape:htmlall}</b><br>
		{#city#|escape:htmlall}: {$query.cityname|escape:htmlall}<br>
		<select name="query[institution]" size="20" tabindex="4">
			<option value=""{if $query.institution eq ""} selected{/if}>--------------------------------------------------------------------</option>
			{institution_list var="result" city=$query.city sql="sql"}
			<!-- {$sql} -->
			{foreach from=$result item=row}
			{if $row.institution ne ""}
			<option value="{$row.institution}"{if $query.institution eq $row.institution or ( $frommeta and $query.id eq $row.id)} selected{/if}>{$row.institution|escape:htmlall}</option>
			{/if}
			{/foreach}
		</select>
	   {/if}
		<p>
		<b>{#institution#|escape:htmlall}</b><br>
		<input type="text" name="institution" value="" size="30" tabindex="6">
	</td>
</tr>
<tr>
	<td colspan="2">
	   <img src="/icons/blank.gif" width="1" height="3">
	</td>
</tr>
<tr>
	<td colspan="2" style="vertical-align: bottom;">
	   <input type="button" name="setit" value="{#setit#|escape:html}" onClick="setLocation();" tabindex="7">
       <input type="button" name="cancel" value="{#cancel#|escape:html}" onClick="window.close(); " tabindex="8">
	</td>
</tr>
</table>
<p>
</form>
</body>
</html>
<!-- --------------------------------------------
END edit_element_loc.tpl
--------------------------------------------- -->
