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
BEGIN detail.tpl
--------------------------------------------- -->
{if $config.utf8 eq "true"}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/detail.conf.utf8"}
{else}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/detail.conf"}
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
  <title>. : {#title#|escape:"htmlall"} : .</title>
  {if $config.soapresults }
      <script src="prototype.js" type="text/javascript"></script>
      <script src="FieldUpdater.js" type="text/javascript"></script>
      {if $config.debug }
        <script src="debug.js" type="text/javascript"></script>
      {/if}
  {/if}
  <script src="dilps.lib.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="css.php">
</head>
<body class="main" {if $config.soapresults }onload="updateRemoteCollectionFields('{$sessionid}','{$query.queryid}')"{/if}>
<form name="Main" action="{$SCRIPT_NAME}" method="POST">
<input type="hidden" name="view[type]" value="detail">
<input type="hidden" name="view[detail][id]" value="{$view.detail.id}">
<input type="hidden" name="view[edit][id]" value="{$view.edit.id}">
<input type="hidden" name="query[remoteCollection]" value="{$query.remoteCollection}">
<table class="header" width="100%">
<tr>
   <td>
   {if $query.querytype eq 'advanced'}
      {include file="`$config.skinBase``$config.skin`/advanced_query.tpl"}
   {else}
      {include file="`$config.skinBase``$config.skin`/easy_query.tpl"}
   {/if}
   </td>
</tr>
</table>
<table class="maincontent" border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
      {include file="`$config.skinBase``$config.skin`/result_list_short.tpl"}
	</td>
	<td width="50%" valign="top">
	   {if $view.edit.id ne ""}{include file="`$config.skinBase``$config.skin`/edit_detail.tpl" id=$view.edit.id}
		{else}{include file="`$config.skinBase``$config.skin`/result_detail.tpl" id=$view.detail.id}
		{/if}
	</td>
</form>
</body>
</html>
<!-- --------------------------------------------
END detail.tpl
--------------------------------------------- -->
