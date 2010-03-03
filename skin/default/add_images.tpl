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


	<input type="hidden" name="PHPSESSID" value="{$sessionid}">

	<table class="header" style="width: 100%; height: 80%; vertical-align: top;">
 		<tr>
		<td class="queryinputfieldtext">
		{#collection#}
		{collection_list var=collections sql=sql}
		<!-- {$sql} -->
		</td>
		<td>
		<input type="hidden" name="query[collectionid]" value="{$query.collectionid}">
                </td>  
		</tr>
		<!--
				<input class="queryinputfield" type="text" name="query[group1]" size="40" readonly="readonly" value="{$query.group1|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group1id]" value="{$query.group1id|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group1level]" value="{$query.group1level|escape:html}">
				<input class="queryinputfield" type="text" name="query[group2]" size="40" readonly="readonly" value="{$query.group2|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group2id]" value="{$query.group1id|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group2level]" value="{$query.group1level|escape:html}">
				<input class="queryinputfield" type="text" name="query[group3]" size="40" readonly="readonly" value="{$query.group3|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group3id]" value="{$query.group1id|escape:html}">
				<input class="queryinputfield" type="hidden" name="query[group3level]" value="{$query.group1level|escape:html}">
				<input type=hidden  name="query[completedir]" value="0">
				<input type="submit" name="query[new]" value="{#continue#|escape:html}">
				//-->
		<tr>
			<td class="queryinputfieldtext">
		  		{#sourcefile#}
			</td>
			<td class="queryinputfield">
				<input class="queryinputfield" type="file" name="query[sourcedirectory]" value="{$query.directory|escape:html}" size="40">
				<input type="hidden" name="query[completedir]" value="0">
			</td>
		</tr>
		<!--<tr>
		   	<td class="queryinputfieldtext">
		    	{#targetdirectory#}
		   	</td>
		   	<td>
				<select class="queryselectfield" name="query[baseid]">
				{basedir_list var=basedirs cid=$query.collectionid sql=sql}
					{foreach from=$basedirs item=row}
						<option value="{$row.img_baseid}" {if $query.baseid eq $row.img_baseid} selected{/if}>{if $row.host neq ""}{$row.host}{else}localhost{/if} :: {$row.base}</option>
					{/foreach}
				</select>
			</td>
		</tr>//-->
		<tr>
			<td class="field_name">
				&nbsp;
			</td>
			<td> 
			        {basedir_list var=basedirs cid=$query.collectionid sql=sql}
			        {foreach from=$basedirs item=row}
			        <input type="hidden" name="query[baseid]" value="{$row.img_baseid}">
				{/foreach}
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

	<tr>
		<td>
		&nbsp;
		</td>
	</tr>
	</table>
<!-- --------------------------------------------
END add_images.tpl
--------------------------------------------- -->
