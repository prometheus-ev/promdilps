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
BEGIN easy_query.tpl
================================================= -->

{if $config.utf8 eq "true"}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/easy_query.conf.utf8"}
{else}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/easy_query.conf"}
{/if}

<script type="text/javascript">
	function rotateimage(cid, imageid)
	{literal}
	{
		{/literal}
		var confirmstring = "{#rotateimagewithid#}";		
		var agree=confirm(confirmstring+": "+cid+":"+imageid+" ?");	
		
		{literal}
		if (agree)
		{
			document.forms["Main"].elements["action[target]"].value = 'image';
			document.forms["Main"].elements["action[function]"].value = 'rotate';
			document.forms["Main"].elements["action[cid]"].value = cid;
			document.forms["Main"].elements["action[imageid]"].value = imageid;			
			document.forms["Main"].submit();			
		}
		
	}
	{/literal}
	
	function deleteimage(cid, imageid)
	{literal}
	{
		{/literal}			
		var confirmstring = "{#deleteimagewithid#}";		
		var agree=confirm(confirmstring+": "+cid+":"+imageid+" ?");			
		
		if (agree)
		{literal}
		{
			{/literal}
			
			document.forms["Main"].elements["action[target]"].value = 'image';
			document.forms["Main"].elements["action[function]"].value = 'delete';
			document.forms["Main"].elements["action[cid]"].value = cid;
			document.forms["Main"].elements["action[imageid]"].value = imageid;			
			document.forms["Main"].submit();
			{literal}
		}
	}
	{/literal}
</script>

<input type="hidden" name="PHPSESSID" value="{$sessionid}">
<input type="hidden" name="query[querytype]" value="simple">
<input type="hidden" name="query[fromquerytype]" value="simple">
<input type="hidden" name="query[all_op]" value="likesoundslike">
<input type="hidden" name="query[showoverviewlink]" value="{$query.showoverviewlink}">
<!--<input type="hidden" name="query[querypiece][0][piece_connector]" value="and">-->
<table class="query" cellspacing="0" cellpadding="0" width="100%">
<tr>
	<td width="70%">
	<!-- left side of query -->
		<table class="query" cellspacing="1" cellpadding="0">
		<tr>
		   <td class="queryinputfieldtext">
		      {#collection#}
		   	  {collection_list var=collections sql=sql}
		      <!-- {$sql} -->
		   </td>
		   <td>
		      <select class="queryselectfield" name="query[collectionid]">
				   <option value="-1">{#all_collections#|escape:htmlall}</option>
					{foreach from=$collections item=row}
					<option value="{$row.collectionid}"{if $query.collectionid eq "" and $config.default_sammlung eq $row.collectionid} selected{elseif $query.collectionid eq $row.collectionid} selected{/if}>{$row.name|escape:htmlall}</option>
					{/foreach}
			  </select>
		      <input type="hidden" name="query[collectionidconnector]" value="and" />
		   </td>
		   <!--<td>
		      <select class="queryselectfield" name="query[querypiece][0][val][0]">
				   <option value="-1">{#all_collections#|escape:htmlall}</option>
					{foreach from=$collections item=row}
					<option value="{$row.collectionid}"{if $query.querypiece[0].val[0] eq "" and $config.default_sammlung eq $row.collectionid} selected{elseif $query.querypiece[0].val[0] eq $row.collectionid} selected{/if}>{$row.name|escape:htmlall}</option>
					{/foreach}
			  </select>
		      <input type="hidden" name="query[querypiece][0][field][0]" value="collectionid" />
		      <input type="hidden" name="query[querypiece][0][connector][0]" value="and" />
		      <input type="hidden" name="query[querypiece][0][operator][0]" value="equals" />
		      <input type="hidden" name="query[querypiece][0][not][0]" value="0" />
		      <input type="hidden" name="query[querypiece][0][operator_list][0]" value="equals" />
		   </td>-->
		</tr>
		
		
		
		<tr>
		   <td class="queryinputfieldtext">
		      {#all#}
		   </td>
		   <td class="queryinputfield">
		      <input class="queryinputfield" type="text" name="query[all]" size="80" value="{$query.all|escape:html}">
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#name#}
		   </td>
		   <td class="queryinputfield">
		    <input class="queryinputfield" type="text" name="query[name]" size="80" value="{$query.name|escape:html}">
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#title#}
		   </td>
		   <td class="queryinputfield">
		    <input class="queryinputfield" type="text" name="query[title]" size="80" value="{$query.title|escape:html}">
		  </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#year#}
		   </td>
		   <td class="queryinputfield">
		     <input class="queryinputfield" type="text" name="query[year]" size="80" value="{$query.year|escape:html}">
		   </td>
		</tr>
		<tr>
			<td class="queryinputfieldtext">
		  		{#group#|escape:htmlall}
			</td>
			<td class="queryinputfield">
				{if $query.group eq ""}
					<input class="queryinputfield" type="text" name="query[group]" size="40" readonly="readonly" value=" ({#selecthere#|escape:htmlall}) " onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=group','groupselection1','width=800,height=300,left=10,top=250,dependent=yes');">					
				{else}
					<input class="queryinputfield" type="text" name="query[group]" size="40" readonly="readonly" value="{$query.group|escape:html}" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=group','groupselection1','width=800,height=300,left=10,top=250,dependent=yes');">
				{/if}
				<input class="queryinputfield" type="hidden" name="query[groupid]" value="{$query.groupid|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[grouplevel]" value="{$query.grouplevel|escape:html}">
				<button type="button" class="actionbutton2" onclick="javascript:cleargroup();" title="{#nogroup#|escape:htmall}"><img src="clear.png" style="width: 12px; height: 12px;" /></button>
			</td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#imagestatus#|escape:htmlall}
		   </td>
		   <td class="queryinputfield">
		      <select class="queryselectfield" name="query[status]">
				{if $user.admin or $user.editor}
					<option value="all" {if $query.status eq "all"}selected="selected"{/if}>{#images_all#}</option>
					<option value="new" {if $query.status eq "new"}selected="selected"{/if}>{#images_new#}</option>
					<option value="edited" {if $query.status eq "edited"}selected="selected"{/if}>{#images_edited#}</option>
				{/if}
				<option value="reviewed" {if $query.status eq "reviewed"}selected="selected"{/if}>{#images_reviewed#}</option>
			  </select>
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#imageid#|escape:htmlall}
		   </td>
		   <td class="queryinputfield">
		     <input class="queryinputfield" type="text" name="query[imageid]" size="20" value="{$query.imageid|escape:html}">
		   </td>
		</tr>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
<!--		<tr>
		   <td class="queryinputfieldtext">
		      {#all#}
		   </td>
		   <td class="queryinputfield">
		      <input class="queryinputfield" type="text" name="query[all]" size="80" value="{$query.all|escape:html}">
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#name#}
		   </td>
		   <td class="queryinputfield">
		    <input class="queryinputfield" type="text" name="query[querypiece][1][val][0]" size="80" value="{$query.querypiece[1].val[0]|escape:html}">
		    <input type="hidden" name="query[querypiece][1][field][0]" value="name" />
		    <input type="hidden" name="query[querypiece][1][connector][0]" value="and" />
		    <input type="hidden" name="query[querypiece][1][operator][0]" value="like" />
		    <input type="hidden" name="query[querypiece][1][not][0]" value="0" />
		    <input type="hidden" name="query[querypiece][1][operator_list][0]" value="extended" />
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#title#}
		   </td>
		   <td class="queryinputfield">
		    <input class="queryinputfield" type="text" name="query[querypiece][1][val][1]" size="80" value="{$query.querypiece[1].val[1]|escape:html}">
		    <input type="hidden" name="query[querypiece][1][field][1]" value="title" />
		    <input type="hidden" name="query[querypiece][1][connector][1]" value="and" />
		    <input type="hidden" name="query[querypiece][1][operator][1]" value="like" />
		    <input type="hidden" name="query[querypiece][1][not][1]" value="0" />
		    <input type="hidden" name="query[querypiece][1][operator_list][1]" value="normal" />
		  </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#year#}
		   </td>
		   <td class="queryinputfield">
		     <input class="queryinputfield" type="text" name="query[querypiece][1][val][2]" size="80" value="{$query.querypiece[1].val[2]|escape:html}">
		     <input type="hidden" name="query[querypiece][1][field][2]" value="year" />
		     <input type="hidden" name="query[querypiece][1][connector][2]" value="and" />
		     <input type="hidden" name="query[querypiece][1][operator][2]" value="equals" />
		     <input type="hidden" name="query[querypiece][1][not][2]" value="0" />
		     <input type="hidden" name="query[querypiece][1][operator_list][2]" value="like" />
		   </td>
		</tr>
		<tr>
			<td class="queryinputfieldtext">
		  		{#group#|escape:htmlall}
			</td>
			<td class="queryinputfield">
				{if $query.group eq ""}
					<input class="queryinputfield" type="text" name="query[group]" size="40" readonly="readonly" value=" ({#selecthere#|escape:htmlall}) " onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=group','groupselection1','width=800,height=300,left=10,top=250,dependent=yes');">					
				{else}
					<input class="queryinputfield" type="text" name="query[group]" size="40" readonly="readonly" value="{$query.group|escape:html}" onclick="javascript:window.open('group_select.php?PHPSESSID={$sessionid}&target=group','groupselection1','width=800,height=300,left=10,top=250,dependent=yes');">
				{/if}
				<input class="queryinputfield" type="hidden" name="query[groupid]" value="{$query.groupid|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[grouplevel]" value="{$query.grouplevel|escape:html}">
				<button type="button" class="actionbutton2" onclick="javascript:cleargroup();" title="{#nogroup#|escape:htmall}"><img src="clear.png" style="width: 12px; height: 12px;" /></button>
			</td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#imagestatus#|escape:htmlall}
		   </td>
		   <td class="queryinputfield">
		      <select class="queryselectfield" name="query[querypiece][1][val][3]">
				{if $user.admin or $user.editor}
					<option value="all" {if $query.querypiece[1].val[3] eq "all"}selected="selected"{/if}>{#images_all#}</option>
					<option value="new" {if $query.querypiece[1].val[3] eq "new"}selected="selected"{/if}>{#images_new#}</option>
					<option value="edited" {if $query.querypiece[1].val[3] eq "edited"}selected="selected"{/if}>{#images_edited#}</option>
				{/if}
				<option value="reviewed" {if $query.querypiece[1].val[3] eq "reviewed"}selected="selected"{/if}>{#images_reviewed#}</option>
			  </select>
		     <input type="hidden" name="query[querypiece][1][field][3]" value="status" />
		     <input type="hidden" name="query[querypiece][1][connector][3]" value="and" />
		     <input type="hidden" name="query[querypiece][1][operator][3]" value="equals" />
		     <input type="hidden" name="query[querypiece][1][not][3]" value="0" />
		     <input type="hidden" name="query[querypiece][1][operator_list][3]" value="equals" />
		   </td>
		</tr>

		
		
		
		<tr>
		   <td class="queryinputfieldtext">
		      {#imageid#|escape:htmlall}
		   </td>
		   <td class="queryinputfield">
		     <input class="queryinputfield" type="text" name="query[querypiece][1][val][4]" size="20" value="{$query.querypiece[1].val[4]|escape:html}">
		     <input type="hidden" name="query[querypiece][1][field][4]" value="imageid" />
		     <input type="hidden" name="query[querypiece][1][connector][4]" value="and" />
		     <input type="hidden" name="query[querypiece][1][operator][4]" value="equals" />
		     <input type="hidden" name="query[querypiece][1][not][4]" value="0" />
		     <input type="hidden" name="query[querypiece][1][operator_list][4]" value="equals" />
		   </td>
		</tr>-->
		
		
		
		
		<!--<tr>
			<td class="queryinputfieldtext">
	  			{#imageid#|escape:htmlall}
			</td>
			<td class="queryinputfield">
				<input class="queryinputfield" type="text" name="query[imageid]" size="20" value="{$query.imageid|escape:html}">
			</td>
		</tr>-->
		<tr>
		   <td class="field_name">
		      &nbsp;
		   </td>
		   <td>
		      <input type="button" onClick="newQuery(); return true;" name="query[new]" value="{#search#|escape:html}">
		      <br />
		      <input type="button" onClick="changeQueryType('advanced')" name="changequerytype" value="{#advancedsearch#|escape:html}">
		   </td>
		</tr>

		</table>
		<!-- left side of query - end -->
	</td>
	{include file="`$config.skinBase``$config.skin`/query_right.tpl"}
	</tr>
</table>

<!-- --------------------------------------------
END easy_query.tpl
--------------------------------------------- -->
