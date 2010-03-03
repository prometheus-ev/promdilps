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
BEGIN edit_element_src.tpl
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
function setName( name, givenname )
{
   window.opener.document.forms["Main"].elements["edit[name]"].value=name;
	window.opener.document.forms["Main"].elements["edit[givenname]"].value=givenname;
	close();
}

function setNew()
{
   window.opener.document.forms["Main"].elements["edit[name]"].value=document.Main.elements["query[name]"].value;
	window.opener.document.forms["Main"].elements["edit[givenname]"].value=document.Main.elements["query[givenname]"].value;
	close();
}

function setFocus()
{
	document.Main.elements["query[artist]"].focus();
	document.Main.elements["query[artist]"].select();
}
{/literal}
</script>
</head>
<body class="main" onLoad="setFocus();">
<form name="Main" action="{$SCRIPT_NAME}" method="POST">
<input type="hidden" name="PHPSESSID" value="{$sessionid}">
<input type="hidden" name="query[id]" value="{$query.id|escape:html}">
<input type="hidden" name="query[element]" value="{$query.element|escape:html}">
{if $query.id ne ""}
{src_list var="result" sql="sql"}
<!-- {$sql} -->
<table border="0">
<tr>
   <td style="vertical-align: bottom;">
		<b>{#artistname#|escape:htmlall}</b><br>
		<input type="text" name="query[artist]" value="{$query.artist|escape:html}"><input type="submit" name="search" value="{#search#|escape:html}">
	</td>
	<td>
	   <img src="/icons/blank.gif" width="10" height="1">
	</td>
	<td>
		<b>{#surname#|escape:htmlall}</b><br>
		<input type="text" name="query[name]" value="{$query.name|escape:html}" size="50"><p>
		<b>{#givenname#|escape:htmlall}</b><br>
		<input type="text" name="query[givenname]" value="{$query.givenname|escape:html}" size="50">
	</td>
   <td style="vertical-align: bottom;">
		<input type="button" name="search" value="{#setit#|escape:html}" onClick="setNew();">
	</td>
</tr>
</table>
<p>
<table class="result_list_data" width="100%">
<tr>
   <td class="result_list_nav_left">
		<input type="submit" name="query[prev]" value=" << "{if $result.page lte 1} disabled{/if}>
		<input type="text" name="query[page]" value="{$result.page|escape:html}" size="3">({#of#|escape:htmlall} {$result.lastpage|escape:htmlall}) {$result.maxrecords|escape:htmlall} {#records#|escape:htmlall}
		<input type="submit" name="query[next]" value=" >> "{if $result.page gte $result.lastpage} disabled{/if}>
		<input type="submit" name="query[reload]" value="{#reload#|escape:html}">
  </td>
</tr>
{foreach from=$result.rs item="row"}
<tr>
   <td class="result_list_data_data">
		   <a onClick="setName( '{$row.name|escape:html}', '{$row.givenname|escape:html}' );">{$row.name|escape:htmlall}{if $row.givenname ne ""}, {$row.givenname|escape:htmlall}{/if}</a>
	</td>
</tr>
{/foreach}
</table>
{/if}
</form>
</body>
</html>
<!-- --------------------------------------------
END edit_element_src.tpl
--------------------------------------------- -->
