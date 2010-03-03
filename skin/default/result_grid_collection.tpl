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
   
   :this file is included from result_grid.tpl:
*}
<!-- ============================================
BEGIN result_grid.tpl
============================================ -->
<td class="result_grid_nav_top" id="navigation" style="text-align: center"
{if $view.detail.id ne "" or $view.edit.id ne ""}
colspan="2"
{/if}
><div class="outer">
{#page#|escape:html}
 {include file="`$config.skinBase``$config.skin`/browse.tpl"}
</div>
</td>
</tr>
<tr>
<td style="width: 50%;" valign="top">
<table class="result_list" width="100%" cellspacing="0" cellpadding="0">
<tr>
   <td class="result_grid_nav_left">
   <div class="outer">
    {include file="`$config.skinBase``$config.skin`/select.tpl"}
  </div>
  </td>
</tr>
   <td>
	   <table class="result_grid_data" width="100%">
		{math assign="colwidth" equation="100 / num" num="`$query.cols`"}
		{section name=rows start=0 step=1 loop=$query.rows}
		{assign var="r" value=$smarty.section.rows.index}
	   <tr>
		   {section name=cols start=0 step=1 loop=$query.cols}
			{math assign="id" equation="r * c + cl" r="$r" c=$query.cols cl=$smarty.section.cols.index}
		   <td width="{$colwidth}%" class="result_list_data_img">
				{assign var="row" value=$result.rs[$id]}
			   {if $row.imageid ne ""}
		          {assign var="tpl" value=$template[$row.type]}
			      {include file="`$config.skinBase``$config.skin`/`$tpl.grid`" row=$row viewFunc=$viewFunc query=$query}
				{else}
				&nbsp;
				{/if}
			</td>
			{/section}
	   </tr>
	  {/section}
	</table>
   </td>
</tr>
</table>
<!-- ============================================
END result_grid_collection.tpl
============================================ -->
