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
BEGIN result.tpl
--------------------------------------------- -->
{if $config.utf8 eq "true"}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/result.conf.utf8"}
{else}
	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/result.conf"}
{/if}

{query var=result sql=sql}
{if $result.result_type eq "collections"}
    {include file="`$config.skinBase``$config.skin`/result_grid_collections.tpl" result=$result}
{else}    

<table class="main" width="100%">
<tr>
   <td>
	{foreach from=`$result.rs` item=row}
	   {$row.title|escape:htmlall} / 
	   {$row.artistname|escape:htmlall}<br>
		
	{/foreach}
	{$sql}
   </td>
</tr>
</table>
{/if}
{/query}
<!-- --------------------------------------------
END result.tpl
--------------------------------------------- -->
