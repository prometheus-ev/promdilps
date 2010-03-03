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
BEGIN edit_detail.tpl
--------------------------------------------- -->

{if $config.utf8 eq "true"}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/result.conf.utf8"}
{else}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/result.conf"}
{/if}

{save_image error="error" sql="sql"}
<!-- {$sql} -->
{if $error ne ""}
{$error|escape:htmlall}<p>
{/if}
{if $id ne ""}
{query_image id=$id var=result sql=sql}
{if $edit.loadtype eq "changetype"}
{assign var="rs" value=$edit}
{else}
{assign var="rs" value=$result.rs}
{/if}
<!-- {$sql} -->
{if $result.id ne ""}
<input type="hidden" name="edit[loadtype]" value="">
<input type="hidden" name="edit[id]" value="{$id}">
<input type="hidden" name="edit[locationid]"  value="{$rs.locationid}">
<table class="result_detail" width="100%">
<tr>
   <td class="result_detail_head">
	   <b>{$result.rs.title|escape:htmlall}</b>
	</td>
</tr>
<tr>
   <td class="result_detail_image">
	   <table class="result_detail_data" width="100%">
		<tr>
		   <td rowspan="4" class="result_detail_data_image">
			<table border="0" cellspacing="0" width="100%">
			<tr>
			   <td rowspan="2"><img src="/icons/blank.gif" width="1" height="91"></td>
			   <td><img src="/icons/blank.gif" width="121" height="1"></td>
			</tr>
			<tr><td style="text-align: center"><img src="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=120x90&remoteCollection={$query.remoteCollection}" border="0"></td></tr>
			</tr>
			</table>
  		   </td>
		   <td colspan="2" class="result_detail_data_data">
  		       [{$rs.width|escape:htmlall}x{$rs.height|escape:htmlall}]
			   [<a href="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=1024x768&remoteCollection={$query.remoteCollection}" target="_blank">1024x768</a>]
			   [<a href="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=1280x1024&remoteCollection={$query.remoteCollection}" target="_blank">1280x1024</a>]
			   [<a href="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=1600x1200&remoteCollection={$query.remoteCollection}" target="_blank">1600x1200</a>]
			</td>
			</tr>
	  		<tr>
			   <td class="result_detail_data_head">{if $user.admin or $user.editor}{#status#|escape:htmlall}{/if}</td>
			   <td colspan="1" class="result_detail_data_data">
			   {if $user.admin}
			   <input type="hidden" name="edit[status]"  value="{$rs.status}">
			   <input type="button" value="{#status_new#|escape:html}" onClick="setStatus( 'new' )"{if $rs.status == 'new'} disabled{/if}>
			   <input type="button" value="{#status_edited#|escape:html}" onClick="setStatus( 'edited' )"{if $rs.status == 'edited'} disabled{/if}>
			   <input type="button" value="{#status_reviewed#|escape:html}" onClick="setStatus( 'reviewed' )"{if $rs.status == 'reviewed'} disabled{/if}>
			   {elseif $user.editor}
			   <input type="hidden" name="edit[status]"  value="{$rs.status}">
			   <input type="button" value="{#status_editing#|escape:html}" onClick="setStatus( 'new' )"{if $rs.status == 'new'} disabled{/if}>
			   <input type="button" value="{#status_completed#|escape:html}" onClick="setStatus( 'edited' )"{if $rs.status == 'edited'} disabled{/if}>
			   &nbsp;({if $rs.status == 'reviewed'}{#status_reviewed#}{else}{#status_pending_review#}{/if})
			   {/if}&nbsp;
		       </td>
		    </tr>
  		<tr>
		   <td class="result_detail_data_head">ID</td>
		   <td  width="100%" class="result_detail_data_data">{$id|escape:htmlall}<input type="hidden" name="edit[id]" value="{$id}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#type#|escape:htmlall}</td>
		   <td class="result_detail_data_data">
		      <select type="hidden" name="edit[type]" onChange="changeType();">
		         {type_list var="types" sql="sql"}
		         {foreach from=`$types` item=type}
		            <option value="{$type.name|escape:html}"{if $rs.type eq $type.name} selected{/if}>{$type.print_name|escape:htmlall}</option>
		         {/foreach}
		      </select>
	          <!-- {$sql} -->
		   </td>
		</tr>
  {assign var="tpl" value=$template[$result.rs.type]}
  {include file="`$config.skinBase``$config.skin`/`$tpl.edit`" row=$rs}		
	   <table>
	</td>
</tr>
</table>
{/if}
{/query_image}
{/if}
<!-- --------------------------------------------
END edit_detail.tpl
--------------------------------------------- -->
