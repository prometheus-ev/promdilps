<!-- ============================================
BEGIN select.tpl
============================================= -->

<table border="0" cellspacing="0" cellpadding="2">
           <tr>	
	   <td style="color: #FF0000" vertical-align:middle;>{#mode#|escape:html}</td>
           <td><a href="javascript: changeView( 'liste' );"><img style="vertical-align:middle;" src="liste{if $view.type eq "liste"}_marked{/if}.gif" border="0"></a></td>
	   <td><a href="javascript: changeView( 'detail' );"><img  style="vertical-align:middle;" src="detail{if $view.type eq "detail"}_marked{/if}.gif" border="0"></a></td>
	   <td><a href="javascript: changeView( 'grid' );"><img  style="vertical-align:middle;" src="grid{if $view.type eq "grid"}_marked{/if}.gif" border="0"></a></td>
	   <td><a href="javascript: changeView( 'grid_detail' );"><img  style="vertical-align:middle;" src="grid_detail{if $view.type eq "grid_detail"}_marked{/if}.gif" border="0"></a></td>
           <td><a class="reloadbutton" href="javascript: editDetail('');">{#reload#|escape:html}</a></td>
  	   <td>
                <input type="hidden" name="query[cols]" value="{$query.cols}">
                <input type="hidden" name="query[rows]" value="{$query.rows}">
                <input type="hidden" name="query[page]" value="{$query.page}">

                <select class="navselectfield" name="queryrows" OnChange="ChangeQuery()">
                   {if $query.cols eq 1} 	
		   <option value="1x3"{if $query.cols eq 1 & $query.rows eq 3} selected{/if}>3</option>
		   <option value="1x4"{if $query.cols eq 1 & $query.rows eq 4} selected{/if}>4</option>
		   <option value="1x8"{if $query.cols eq 1 & $query.rows eq 8} selected{/if}>8</option>
		   <option value="1x10"{if $query.cols eq 1 & $query.rows eq 10} selected{/if}>10</option>
		   <option value="1x16"{if $query.cols eq 1 & $query.rows eq 16} selected{/if}>16</option>
		   <option value="1x32"{if $query.cols eq 1 & $query.rows eq 32} selected{/if}>32</option>
		   {else}
                   <option value="3x3"{if $query.cols eq 3 & $query.rows eq 3} selected{/if}>3 x 3</option>
		   <option value="3x4"{if $query.cols eq 3 & $query.rows eq 4} selected{/if}>3 x 4</option>
		   <option value="3x8"{if $query.cols eq 3 & $query.rows eq 8} selected{/if}>3 x 8</option>
		   <option value="3x25"{if $query.cols eq 3 & $query.rows eq 25} selected{/if}>3 x 25</option>
		   <option value="6x3"{if $query.cols eq 6 & $query.rows eq 3} selected{/if}>6 x 3</option>
		   <option value="6x4"{if $query.cols eq 6 & $query.rows eq 4} selected{/if}>6 x 4</option>
		   <option value="6x8"{if $query.cols eq 6 & $query.rows eq 8} selected{/if}>6 x 8</option>
		   <option value="6x25"{if $query.cols eq 6 & $query.rows eq 25} selected{/if}>6 x 25</option>		
		   <option value="8x3"{if $query.cols eq 8 & $query.rows eq 3} selected{/if}>8 x 3</option>
		   <option value="8x4"{if $query.cols eq 8 & $query.rows eq 4} selected{/if}>8 x 4</option>
		   <option value="8x8"{if $query.cols eq 8 & $query.rows eq 8} selected{/if}>8 x 8</option>
		   <option value="8x25"{if $query.cols eq 8 & $query.rows eq 25} selected{/if}>8 x 25</option>
                   {/if}
               </select>
          </td>	
	  </tr>
</table>

<!-- ============================================
END select.tpl
============================================= -->
