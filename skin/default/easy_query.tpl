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


<script type="text/javascript">
{literal}

function showRecords(){
document.forms["Main"].elements["query[all]"].value = '';
document.forms["Main"].elements["query[name]"].value = '';
document.forms["Main"].elements["query[title]"].value = '';
document.forms["Main"].elements["query[year]"].value = '';
document.forms["Main"].elements["query[imageid]"].value = '';
document.forms["Main"].submit();
}

function changeAction(){
document.forms["Main"].action="add_images.php";
document.forms["Main"].submit();
}



{/literal}
</script>

	       <!-- left side of query -->                                       
		                <table class="query" cellspacing="1" cellpadding="0">
		               <tr>
			       <td width=20%>&nbsp;</td>
			       <td> &nbsp;</td>
			       </tr>
			       <tr>
			       <td width=20%>&nbsp;</td>
			       <td colspan=2>
			       {#inserttext#}
			       </td></tr>
                       <tr>
			<td width=20%>&nbsp;</td>
			<td> &nbsp;</td>
			</tr>
                        <tr>
<td>
<input type="hidden" name="query[collectionid]" value="{$query.collectionid}">
</td>
</tr>
<tr>
<td width=20%>&nbsp;</td>
<td class="queryinputfieldtext">
{#sourcefile#}
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td class="queryinputfield">
<input class="queryinputfield" type="file" name="query[sourcedirectory]" value="{$query.directory|escape:html}" size="30">
<input type="hidden" name="query[completedir]" value="0">
</td>
</tr>
<tr>
<td>&nbsp;
</td>
</tr>
<tr>
<td class="field_name">
&nbsp;
</td>
<td>
<!--{basedir_list var=basedirs cid=$query.collectionid sql=sql}
{foreach from=$basedirs item=row}
<input type="text" name="query[baseid]" value="{$row.img_baseid}">
{/foreach}//-->
<input type="hidden" name="query[baseid]" value="1">
<input type="hidden" name="query[collectionid]" value="{$query.collectionid}">
<input type="hidden" name="query[group1id]" value="{$query.group1id}">
<input type="hidden" name="query[group1level]" value="{$query.group1level}">
<input type="hidden" name="query[group2id]" value="{$query.group2id}">
<input type="hidden" name="query[group2level]" value="{$query.group2level}">
<input type="hidden" name="query[group3id]" value="{$query.group3id}">
<input type="hidden" name="query[group3level]" value="{$query.group3level}">
<input type="hidden" name="query[process]" value="2">
{if $no_subdirs neq "1"}

<input type="button" name="query[new]" onClick="changeAction()" value="{#insertimages#|escape:html}">
{else}
<br />
{/if}
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
</td>
 </tr>
<tr>
<td>&nbsp;</td>
<td>
<a href="javascript:showRecords()" class="navsymbol">{#showrecords#}</a>
</table>
</td>
 {include file="`$config.skinBase``$config.skin`/query_right.tpl"}
</tr>
</table>
<!-- --------------------------------------------
END add_images.tpl
--------------------------------------------- -->
