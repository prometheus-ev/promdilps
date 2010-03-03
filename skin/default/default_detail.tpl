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

<!-- ============================================
BEGIN default_detail.tpl
============================================= -->

<table class="result_detail" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="result_detail_top" style="padding-bottom: 6px; text-align: right"> 

    {if $user.editor && $result.local}
    [<a class="result_detail_image_list" href="javascript:editDetail('{$id}');">{#edit#}</a>]
    {/if}
    </td>
  </tr>
  <tr>
    <td class="result_detail_image">
    <table class="result_detail_data_image" width="100%"  cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" class="result_detail_data_image">
        <div class="outer">
        <img src="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=640x480&remoteCollection={$query.remoteCollection}" border="0"><br>
        [{$row.width|escape:htmlall}x{$row.height|escape:htmlall}]
        </div>
        </td>
      </tr>
      <tr style="border-bottom: 6px solid #778898">
        <td colspan="2">
	  (<a class="result_detail_image_list" href="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=1024x768&remoteCollection={$query.remoteCollection}" target="_blank">1024x768</a>)
	  (<a class="result_detail_image_list" href="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=1280x1024&remoteCollection={$query.remoteCollection}" target="_blank">1280x1024</a>)
	  (<a class="result_detail_image_list" href="image.php?PHPSESSID={$sessionid}&id={$result.id}&resolution=1600x1200&remoteCollection={$query.remoteCollection}" target="_blank">1600x1200</a>)
	</td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td>
    <table class="result_detail_imagedetails" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td>
	<table class="result_detail_data" width="100%"  cellpadding="0" cellspacing="0">
	  <tr>
	    <td class="result_detail_data_head">ID</td>
	    <td class="result_detail_data_data">{$row.collectionid|escape:htmall}:{$row.imageid|escape:htmlall}</td>
	  </tr>
	  <tr>
	    <td class="result_detail_data_head">{#title#}</td>
	    <td class="result_detail_data_data">{$row.title|escape:htmall}</td>
	  </tr>
	  <tr>
	    <td class="result_detail_data_head">{#name#}</td>
	    <td class="result_detail_data_data">{$row.name1text|escape:htmall}{if $row.name2text ne ""} <br /> {$row.name2text|escape:htmlall}{/if}</td>
	  </tr>
	  {if $row.addition ne ""}
	  <tr>
	    <td class="result_detail_data_head">{#addition#}</td>
	    <td class="result_detail_data_data">{$row.addition|escape:htmall}</td>
	  </tr>
	  {/if}
	  {if $row.dating ne ""}
	  <tr>
	    <td class="result_detail_data_head">{#date#}</td>
	    <td class="result_detail_data_data">{$row.dating|escape:htmall}</td>
	  </tr>
	  {/if}
	  {if $row.material ne ""}
	  <tr>
	    <td class="result_detail_data_head">{#material#}</td>
	    <td class="result_detail_data_data">{$row.material|escape:htmall}</td>
	  </tr>
	  {/if}
	  {if $row.technique ne ""}
	  <tr>
	    <td class="result_detail_data_head">{#technics#}</td>
	    <td class="result_detail_data_data">{$row.technique|escape:htmall}</td>
	  </tr>
	  {/if}
	  {if $row.format ne ""}
	  <tr>
	    <td class="result_detail_data_head">{#format#}</td>
	    <td class="result_detail_data_data">{$row.format|escape:htmall}</td>
	  </tr>
	  {/if}
          {if $row.city ne "" or $row.institution ne ""}
	  <tr>
	    <td class="result_detail_data_head">{#location#}</td>
	    <td class="result_detail_data_data">{$row.city|escape:htmall}{if $row.institution ne "" AND $row.city ne ""} / {/if}{$row.institution|escape:htmlall}</td>
	  </tr>
          {/if}
	  <tr>
	    <td class="result_detail_data_head">{#src#|escape:htmlall}</td>
	    <td class="result_detail_data_data">
	    {$row.literature|escape:htmlall}
	    {if $row.page ne ""}<br>{#page#}: {$row.page|escape:htmall}{/if}
	    {if $row.figure ne ""}<br>{#figure#}: {$row.figure|escape:htmall}{/if}
	    {if $row.table ne ""}<br>{#plate#}: {$row.table|escape:htmall}{/if}
	    {if $row.isbn ne ""}<br>{#isbn#}: {$row.isbn|escape:htmall}{/if}
	    </td>
	  </tr>
        </table>
        </td>
     </tr>
  </table>
  <tr>
    <td>
     <table class="result_detail_imagedetails" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        {if $row.isbn ne ""}
        <td class="result_detail_amazonimage">
        <a target="_blank" href="http://www.amazon.de/exec/obidos/ASIN/{$row.isbn|replace:"-":""|escape:url}/wwwdilpsnet-21"><IMG SRC="amazon_image.php?size=medium&ASIN={$row.isbn|replace:"-":""|escape:url}" border="0" alt="cover" hspace="3" vspace="3"></A>
        {/if}
        </td>

        <td>
        <table width="100%" cellpadding="0" cellspacing="0">
				{if $row.name ne ""}
				<tr>
                                <td class="result_detail_data_data">
                                <a class="result_detail_furtherinfo_link" target="_blank" href="http://images.google.de/images?q={$row.name|escape:url}{if $row.vorname ne ""},+{$row.vorname|escape:url}{/if}&ie=UTF-8&oe=UTF-8&hl=de">Google Images</a> [{$row.name|escape:htmlall}{if $row.vorname ne ""}, {$row.vorname|escape:htmlall}
                                </td>
                                </tr>
                                {/if}]
				{if $row.title ne ""}
				<tr>
                                <td class="result_detail_data_data">
  			        <a class="result_detail_furtherinfo_link" target="_blank" href="http://images.google.de/images?q={$row.name|escape:url}{if $row.vorname ne ""},+{$row.vorname|escape:url}{/if}+{$row.title|truncate:20:""|escape:url}&ie=UTF-8&oe=UTF-8&hl=de">Google Images</a> [{$row.name|escape:htmlall}{if $row.vorname ne ""}, {$row.vorname|escape:htmlall}{/if} {$row.title|escape:htmlall}]
                                </td>
                                </tr>
                                {/if}
				<tr>
                                <td class="result_detail_data_data">
   			        <a class="result_detail_furtherinfo_link" target="_blank" href="http://de.wikipedia.org/wiki/Spezial:Search?search={$row.name|escape:url}{if $row.vorname ne ""},%20{$row.vorname|escape:url}{/if}&go=Artikel">Wikipedia.de</a> [{$row.name|escape:htmlall}{if $row.vorname ne ""}, {$row.vorname|escape:htmlall}{/if}]
                                </td>
                                </tr>
				<tr>
                                <td class="result_detail_data_data">
                                <a class="result_detail_furtherinfo_link" target="_blank" href="http://www.google.com/search?hl=en&ie=UTF-8&oe=UTF-8&q={$row.name|escape:url}{if $row.vorname ne ""},%20{$row.vorname|escape:url}{/if}">Google</a> [{$row.name|escape:htmlall}{if $row.vorname ne ""}, {$row.vorname|escape:htmlall}{/if}]
                                </td>
                                </tr>
				{/if}
				{if $row.isbn ne ""}
				<tr>
                                <td class="result_detail_data_data">
				<a class="result_detail_furtherinfo_link" target="_blank" href="http://kvk.ubka.uni-karlsruhe.de/hylib-bin/kvk/nph-kvk2.cgi?maske=kvk-last&title=UB+Karlsruhe%3A+KVK+Ergebnisanzeige&header=http%3A%2F%2Fwww.ubka.uni-karlsruhe.de%2Fkvk%2Fkvk%2Fkvk-header_de_04_07_02.html&spacer=http%3A%2F%2Fwww.ubka.uni-karlsruhe.de%2Fkvk%2Fkvk%2Fkvk-spacer.html&footer=http%3A%2F%2Fwww.ubka.uni-karlsruhe.de%2Fkvk%2Fkvk%2Fkvk-footer_de_04_07_02.html&VERBUENDE=&kataloge=SWB&kataloge=BVB&kataloge=NRW&kataloge=HEBIS&kataloge=KOBV&kataloge=GBV&TI=&PY=&AU=&SB={$row.isbn|escape:url}&CI=&SS=&ST=&PU=&sortiert=nein&css=http%3A%2F%2Fwww.ubka.uni-karlsruhe.de%2Fkvk%2Fkvk%2Fkvk-neu.css&target=_blank&Timeout=60">KVK (incl. HEBIS)</a> [{$row.isbn|escape:htmlall}]
                                </td>
                                </tr>
				<tr>
                                <td class="result_detail_data_data">
                                <a class="result_detail_furtherinfo_link" target="_blank" href="http://www.amazon.de/exec/obidos/ASIN/{$row.isbn|replace:"-":""|escape:url}/wwwdilpsnet-21">Amazon.de</a> [{$row.isbn|escape:htmlall}]
                                </td>
                                </tr>
				{/if}
	</td>
       </tr>
    </table>
  </td>
  </tr>
 </table>
<!--
		<tr>
			   <td class="result_detail_data_head">{#magick#|escape:htmlall}</td>
		      <td class="result_detail_data_data">{$row.magick|escape:htmlall}</tr>
		</tr>
		<tr>
			   <td class="result_detail_data_head">{#imagesize#|escape:htmlall}</td>
				<td class="result_detail_data_data">{$row.width|escape:htmlall}x{$row.height|escape:htmlall} ({$row.xres|escape:htmlall}x{$row.yres|escape:htmlall})</td>
		</tr>
		<tr>
				<td class="result_detail_data_head">{#filename#|escape:htmlall}</td>
				<td class="result_detail_data_data">{$row.base|escape:htmlall}/{$row.filename|escape:htmlall}</td>
		</tr>
		<tr>
				<td class="result_detail_data_head">{#filesize#|escape:htmlall}</td>
				<td class="result_detail_data_data">{$row.size|escape:htmlall}</td>
		</tr>
-->
</td>
</tr>
</table>
<!-- ============================================
END default_detail.tpl
============================================= -->
