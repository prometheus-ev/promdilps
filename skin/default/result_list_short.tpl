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
BEGIN result_list_short.tpl
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
    <td class="result_list_nav_top" id="navigation" style="text-align: center"
    {if $view.detail.id ne "" or $view.edit.id ne ""}
    colspan="2"
    {/if}
    >
    {#page#|escape:html}
     {include file="`$config.skinBase``$config.skin`/browse.tpl"}  
    </td>
    </tr>
    <tr>
    <td width="50%" valign="top">
    <table class="result_list" width="100%" cellpadding="0">
    <tr>
       <td class="result_list_nav_left">
       <div class="outer">
         {include file="`$config.skinBase``$config.skin`/select.tpl"}
       </div>
       </td>
    </tr>
    <tr>
       <td colspan="2">
    	   <table class="result_list_data" width="100%">
         <colgroup>
            <col width="20%">
            <col width="80%">
         </colgroup>
    	   {foreach from=`$result.rs` item=row}
    	      {assign var="tpl" value=$template[$row.type]}
    	      {include file="`$config.skinBase``$config.skin`/`$tpl.list_short`" row=$row}
    	   {/foreach}
    	</table>
       </td>
    </tr>
    </table>
{/if}
{/query}
<!-- --------------------------------------------
END result_list_short.tpl
--------------------------------------------- -->
