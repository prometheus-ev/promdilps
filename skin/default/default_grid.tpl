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
BEGIN default_grid.tpl
--------------------------------------------- -->
<script type="text/javascript">
	{literal}
	function changemygroup(buttonid, imageid) 
	{
		// imageid is a combined id here (=> collectionid:imageid)
		
		// do we add or delete?
		var mode;
		
		// change the button
		if (document.getElementById(buttonid) != null)
		{				
			{/literal}
			if (document.getElementById(buttonid).value == "+")
			{literal}
			{
				{/literal}
				mode = 'add';
				document.getElementById(buttonid).value = "-";				
				document.getElementById(buttonid).title = "{#delfrommygroup#}";
				{literal}
			}
			else
			{
				{/literal}
				mode = 'del';
				document.getElementById(buttonid).value = "+";
				document.getElementById(buttonid).title = "{#addtomygroup#}";
				{literal}
			}
		}
		
		// test, if button has been clicked before
		var resid = 'mygroup[' + imageid + ']';
		
		if (document.getElementById(resid) != null)
		{
			document.getElementById(resid).value = mode;
		}
		else
		{			
			// create new hidden input field with resid as name
			var groupElem 				= document.createElement('input');
			groupElem.name 			= resid
			groupElem.value 			= mode;
			groupElem.type				= 'hidden';
			groupElem.className	= 'queryinputfield';
			groupElem.setAttribute('id',resid);
			
			// attach it to the groupchanges block			
			document.getElementById('mygroupchanges').appendChild(groupElem);
		}
		
		return;	
	}
	{/literal}
	
	
	
</script>

<table border="0" cellspacing="0" width="100%">
<tr>
   <td rowspan="2"><img src="/icons/blank.gif" width="1" height="91"></td>
   <td><img src="/icons/blank.gif" width="121" height="1"></td>
</tr>
<tr>
	<td style="text-align: center">
		<a href="javascript:{$viewFunc}('{$sessionid}','{$row.collectionid}:{$row.imageid}', '{$result.remoteCollection}');"><img src="image.php?PHPSESSID={$sessionid}&id={$row.collectionid}:{$row.imageid}&resolution=140x140&remoteCollection={$result.remoteCollection}" border="0"></a>
	</td>
</tr>
<tr>
	<td colspan="2" class="result_list_data_data" style="text-align: center">
	<b>
	{if $row.name1 ne "" or $row.name2 ne ""}
		{$row.name1|escape:htmlall}
		{if $row.name2 ne ""}<br/>
			{$row.name2|escape:htmlall}
		{/if}
		</b>
	{else}
		&nbsp;
	{/if}
	<br>
	{if $row.title ne ""}
		{$row.title|replace:"-":"- "|truncate:50|escape:htmlall}		
	{else}
		&nbsp;
	{/if}	
	</td>
</tr>
<tr>
	<td colspan="2" style="height: 5px;">		
	</td>
</tr>
{if $result.local}
<tr>
	<td rowspan="2"><img src="/icons/blank.gif" width="1"></td>
	<td colspan="2" class="result_list_data_data" style="text-align: center">
		<table border="0" cellspacing="0" style="width: 100%">			
			<tr>
				<td class="result_list_data_data" style="width: 50%">					
					{if $query.mygroupid neq ""}
						{#group#}
						{mygroup_isin var=ingroup sql=sql cid=$row.collectionid groupid=$query.mygroupid imageid=$row.imageid}
						{if $ingroup eq 'no'}
							<input type="button" name="but-{$row.collectionid}-{$row.imageid}" id="but-{$row.collectionid}-{$row.imageid}" class="actionbutton" onclick="javascript:changemygroup('but-{$row.collectionid}-{$row.imageid}','{$row.collectionid}:{$row.imageid}');" value="+" style="height: 22px; width: 22px;" title="{#addtomygroup#}"/>
						{else}	
							<input type="button" name="but-{$row.collectionid}-{$row.imageid}" id="but-{$row.collectionid}-{$row.imageid}" class="actionbutton" onclick="javascript:changemygroup('but-{$row.collectionid}-{$row.imageid}','{$row.collectionid}:{$row.imageid}');" value="-" style="height: 22px; width: 22px;" title="{#delfrommygroup#}"/>
						{/if}
						<!-- {$sql} -->
					{else}
						&nbsp;
					{/if}
				</td>				
				<td class="result_list_data_data">
					{if $user.editor or $user.admin}
						{#image#}
					{else}
						&nbsp;
					{/if}
					{if $user.editor}
						<button type="button" name="rot-{$row.collectionid}-{$row.imageid}" class="actionbutton" onclick="javascript:rotateimage('{$row.collectionid}','{$row.imageid}');" style="padding: 1px;" title="{#rotate#|escape:htmlall}"><img src="rotate-90.png" style="width: 12px; height: 12px" /></button>
					{/if}
					{if $user.admin}
						<button type="button" name="del-{$row.collectionid}-{$row.imageid}" class="actionbutton" onclick="javascript:deleteimage('{$row.collectionid}','{$row.imageid}');" style="padding: 1px;" title="{#delete#|escape:htmlall}"><img src="delete.png" style="width: 12px; height: 12px" /></button>
					{/if}
				</td>
			</tr>
		</table>
   </td>
</tr>
{/if}
</table>
<!-- --------------------------------------------
END default_grid.tpl
--------------------------------------------- -->
				
