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

<input class="queryinputfield" type="hidden" name="action[target]" value="">
<input class="queryinputfield" type="hidden" name="action[function]" value="">
<input class="queryinputfield" type="hidden" name="action[cid]" value="">
<input class="queryinputfield" type="hidden" name="action[gid]" value="">
<input class="queryinputfield" type="hidden" name="action[imageid]" value="">

{if $action.target eq "mygroup"}
        {if $mygroup neq ""}
                {mygroup_change action=$action.function gid=$action.gid changes=$mygroup result=result sql=sql}
        {/if}
{/if}
{if $action.target eq "image"}
        {image_change action=$action.function cid=$action.cid imageid=$action.imageid result=result sql=sql}
{/if}

<!-- {$result} -->
<!-- {$sql} -->

<!-- right side of query -->
<td width=40%>
<table class="query" cellspacing="1" cellpadding="0" >
<tr>
<td>
<input class="queryinputfield" type="hidden" name="logout" value="0">
<a href="javascript:performlogout();" class="navsymbol">
<em>{#logout#|escape:html}</em>
</a>
</td>
</tr>
		<tr>
		<td class="field_name">
	        &nbsp;
	        </td>
	       </tr>
	       <tr><td colspan=2>
	       {#searchtext#}
	       </td></tr>
			           <input type="hidden" name="query[collectionid]" value="1">
		</tr>
		<tr>
		<td>&nbsp;
		</td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#all#}
		   </td>
		   <td class="queryinputfield">
		      <input class="queryinputfield" type="text" name="query[all]" size="30" value="{$query.all|escape:html}">
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#name#}
		   </td>
		   <td class="queryinputfield">
		    <input class="queryinputfield" type="text" name="query[name]" size="30" value="{$query.name|escape:html}">
		   </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#title#}
		   </td>
		   <td class="queryinputfield">
		    <input class="queryinputfield" type="text" name="query[title]" size="30" value="{$query.title|escape:html}">
		  </td>
		</tr>
		<tr>
		   <td class="queryinputfieldtext">
		      {#year#}
		   </td>
		   <td class="queryinputfield">
		     <input class="queryinputfield" type="text" name="query[year]" size="30" value="{$query.year|escape:html}">
		   </td>
		</tr>
		<tr>
		   <td>
		   <input type="hidden" name="query[status]" value="all">
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
		
		<tr>
		   <td class="field_name">
		      &nbsp;
		   </td>
		   <td>
		      <input type="button" onClick="newQuery(); return true;" name="query[new]" value="{#search#|escape:html}">
		      <br />
		   </td>
		</tr>

		</table>
		<!-- right side of query - end -->
	</td>
	</tr>
</table>

<!-- --------------------------------------------
END easy_query.tpl
-------------------------------------------- -->
