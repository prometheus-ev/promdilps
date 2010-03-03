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
<!-- =================================================
BEGIN add_images.tpl
================================================= -->
{if $config.utf8 eq "true"}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/easy_query.conf.utf8"}
{else}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/easy_query.conf"}
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
<meta name="author" content="jrgen enge, thorsten wbbena">
<meta name="date" content="2003-01-23">
<link rel="shortcut icon" href="favicon.ico">
<title>. : DILPS : .</title>
<script type="text/javascript">
{literal}
function cleargroup1() {
	document.forms["Main"].elements["query[group1]"].value = '';
	document.forms["Main"].elements["query[group1id]"].value = '';
	document.forms["Main"].elements["query[group1level]"].value = '';
}

function cleargroup2() {
	document.forms["Main"].elements["query[group2]"].value = '';
	document.forms["Main"].elements["query[group2id]"].value = '';
	document.forms["Main"].elements["query[group2level]"].value = '';
}

function cleargroup3() {
	document.forms["Main"].elements["query[group3]"].value = '';
	document.forms["Main"].elements["query[group3id]"].value = '';
	document.forms["Main"].elements["query[group3level]"].value = '';
}
{/literal}
</script>

<link rel="stylesheet" type="text/css" href="css.php">
</head>
<body class="main" style="width: 100%; height: 100%;">
	<form name="Main" action="{$SCRIPT_NAME}" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="PHPSESSID" value="{$sessionid}">

	<table class="header" style="width: 100%; height: 80%; vertical-align: top;">
	{if $query.process eq "" or $query.process eq "0"}
		<tr>
		   <td class="queryinputfieldtext">
		      {#collection#}
		   </td>
		   <td>
		      <select class="queryselectfield" name="query[collectionid]">
					{collection_list var=collections sql=sql}
					<!-- {$sql} -->
					{foreach from=$collections item=row}
						<option value="{$row.collectionid}"{if $query.collection eq "" and $config.defaultcollection eq $row.collectionid} selected{elseif $query.collection eq $row.collectionid} selected{/if}>{$row.name|escape:htmlall}</option>
					{/foreach}
				</select>
		   </td>
		</tr>
		<tr>
			<td class="queryinputfieldtext">
		  		{#group#|escape:htmlall} 1
			</td>
			<td class="queryinputfield">
				{if $query.group1 neq ""}
					<input class="queryinputfield" type="text" name="query[group1]" size="40" readonly="readonly" value="{$query.group1|escape:html}" onclick="javascript:window.open('group_select.php?target=img_group','groupselection1','width=1000,height=300,left=10,top=250,dependent=yes');">
				{else}
					<input class="queryinputfield" type="text" name="query[group1]" size="40" readonly="readonly" value=" ( {#selecthere#} )" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=img_group','groupselection1','width=1000,height=300,left=10,top=250,dependent=yes');">
				{/if}
				<input class="queryinputfield" type="hidden" name="query[group1id]" value="{$query.group1id|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group1level]" value="{$query.group1level|escape:html}">
				<input type="button" onClick="cleargroup1();" name="nogroup1" value="{#nogroup#|escape:html}">
			</td>
		</tr>
		<tr>
			<td class="queryinputfieldtext">
		  		{#group#|escape:htmlall} 2
			</td>
			<td class="queryinputfield">
				{if $query.group2 neq ""}
					<input class="queryinputfield" type="text" name="query[group2]" size="40" readonly="readonly" value="{$query.group2|escape:html}" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=img_group','groupselection2','width=800,height=300,left=10,top=250,dependent=yes');">
				{else}
					<input class="queryinputfield" type="text" name="query[group2]" size="40" readonly="readonly" value=" ( {#selecthere#} )" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=img_group','groupselection2','width=800,height=300,left=10,top=250,dependent=yes');">
				{/if}
				<input class="queryinputfield" type="hidden" name="query[group2id]" value="{$query.group1id|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group2level]" value="{$query.group1level|escape:html}">
				<input type="button" onClick="cleargroup2();" name="nogroup2" value="{#nogroup#|escape:html}">
			</td>
		</tr>
		<tr>
			<td class="queryinputfieldtext">
		  		{#group#|escape:htmlall} 3
			</td>
			<td class="queryinputfield">
				{if $query.group3 neq ""}
					<input class="queryinputfield" type="text" name="query[group3]" size="40" readonly="readonly" value="{$query.group3|escape:html}" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=img_group','groupselection3','width=800,height=300,left=10,top=250,dependent=yes');">
				{else}
					<input class="queryinputfield" type="text" name="query[group3]" size="40" readonly="readonly" value=" ( {#selecthere#} )" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=img_group','width=800,height=300,left=10,top=250,dependent=yes');">
				{/if}

				<input class="queryinputfield" type="hidden" name="query[group3id]" value="{$query.group1id|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group3level]" value="{$query.group1level|escape:html}">
				<input type="button" onClick="cleargroup3();" name="nogroup3" value="{#nogroup#|escape:html}">
			</td>
		</tr>
		<tr>
			<td class="queryinputfieldtext">
		  		{#completedir#}
			</td>
			<td class="queryinputfield">
				<select class="queryselectfield" name="query[completedir]">
					<option value="0">{#file#}</option>
					<option value="1">{#directory#}</option>
				</select>
			</td>
		</tr>
		<tr>
		   	<td class="queryinputfieldtext">
		    	{#targetdirectory#}
		   	</td>
		   	<td>
		   		{#nextstep#}
			</td>
		</tr>
		<tr>
			<td class="field_name">
				&nbsp;
			</td>
			<td>
				<input type="hidden" name="query[process]" value="1">
				<input type="submit" name="query[new]" value="{#continue#|escape:html}">
			</td>
		</tr>
	{else}
		{if $query.completedir eq "0"}
		<tr>
			<td class="queryinputfieldtext">
		  		{#sourcefile#}
			</td>
			<td class="queryinputfield">
				<input class="queryinputfield" type="file" name="query[sourcedirectory]" value="{$query.directory|escape:html}" size="40">
				<input type="hidden" name="query[completedir]" value="0">
			</td>
		</tr>
		{else}
		<tr>
			<td class="queryinputfieldtext">
		  		{#sourcedirectory#}
			</td>
			<td class="queryinputfield">
				<!--
				<input class="queryinputfield" type="text" name="query[sourcedirectory]" value="{$config.uploaddir|escape:html}" size="40">
				-->

				<select class="queryselectfield" name="query[sourcedirectory]">
				{dir_list var=basedirs dir=$config.uploaddir}
					{foreach from=$basedirs item=row}
						<option value="{$config.uploaddir}{$row.name}">{if $row.name eq "no_subdirs"}{assign var=no_subdirs value=1}{#no_subdirs#}{else}{$row.name}{/if}</option>
					{/foreach}
				</select>
				<input type="hidden" name="query[completedir]" value="1">
			</td>
		</tr>
		{/if}
		<tr>
		   	<td class="queryinputfieldtext">
		    	{#targetdirectory#}
		   	</td>
		   	<td>
				<select class="queryselectfield" name="query[baseid]">
				{basedir_list var=basedirs cid=$query.collectionid sql=sql}
					<!-- {$sql} -->
					{foreach from=$basedirs item=row}
						<option value="{$row.img_baseid}" {if $query.baseid eq $row.img_baseid} selected{/if}>{if $row.host neq ""}{$row.host}{else}localhost{/if} :: {$row.base}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="field_name">
				&nbsp;
			</td>
			<td>
				<input type="hidden" name="query[collectionid]" value="{$query.collectionid}">
				<input type="hidden" name="query[group1id]" value="{$query.group1id}">
				<input type="hidden" name="query[group1level]" value="{$query.group1level}">
				<input type="hidden" name="query[group2id]" value="{$query.group2id}">
				<input type="hidden" name="query[group2level]" value="{$query.group2level}">
				<input type="hidden" name="query[group3id]" value="{$query.group3id}">
				<input type="hidden" name="query[group3level]" value="{$query.group3level}">
				<input type="hidden" name="query[process]" value="2">
				{if $no_subdirs neq "1"}
					<input type="submit" name="query[new]" value="{#insertimages#|escape:html}">
				{else}
					<br />
					<strong onclick="window.close();">{#closewindow#}</strong>
				{/if}
			</td>
		</tr>
	{/if}

	<tr>
		<td>
		&nbsp;
		</td>
	</tr>
	</table>
</form>
</body>
</html>
<!-- --------------------------------------------
END add_images.tpl
--------------------------------------------- -->
