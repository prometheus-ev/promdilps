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
BEGIN default_edit.tpl
--------------------------------------------- -->
		<tr>
		   <td class="result_detail_data_head">{#title#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[title]" size="60" value="{$row.title|escape:html}"></td>
		</tr>
		<tr>
		    <td class="result_detail_data_head">{#name#|escape:htmlall}</td>
		   
            <td colspan="2" class="result_detail_data_data">
			   <input type="text" name="edit[name1text]" size="60" value="{$row.name1text|escape:html}" readonly="readonly"><br />
			   <input type="hidden" name="edit[name1id]" value="{$row.name1id|escape:html}">
			   [<a onClick="editNameElement( '{$sessionid}', '{$id}', 'name', document.Main.elements );">e</a>]
            </td>
			
		</tr>
		<tr>
		    <td class="result_detail_data_head">{#name2#|escape:htmlall}</td>
		   
            <td colspan="2" class="result_detail_data_data">
			   <input type="text" name="edit[name2text]" size="60" value="{$row.name2text|escape:html}" readonly="readonly">
			   <input type="hidden" name="edit[name2id]" value="{$row.name2id|escape:html}">
			   [<a onClick="editNameElement( '{$sessionid}', '{$id}', 'name', document.Main.elements );">e</a>]
            </td>
			
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#addition#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[addition]" size="60" value="{$row.addition|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#date#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[dating]" size="60" value="{$row.dating|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#material#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[material]" size="60" value="{$row.material|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#technics#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[technique]" size="60" value="{$row.technique|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#format#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[format]" size="60" value="{$row.format|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#city#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data">
			   <input type="text" name="edit[city]" size="60" value="{$row.city|escape:html}" readonly>
				[<a onClick="editLocElement( '{$sessionid}', '{$id}', 'loc', document.Main.elements );">e</a>]
			</td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#institution#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data">
		   		<input type="text" name="edit[institution]" size="60" value="{$row.institution|escape:html}" readonly>
				[<a onClick="editLocElement( '{$sessionid}', '{$id}', 'loc', document.Main.elements );">e</a>]
		   </td>		   
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#src#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[literature]" size="60" value="{$row.literature|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#page#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[page]" size="6" value="{$row.page|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#figure#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[figure]" size="6" value="{$row.figure|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#plate#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[table]" size="6" value="{$row.table|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#isbn#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[isbn]" size="16" value="{$row.isbn|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#exportedto#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data">
            <input type="hidden" name="edit[export][prometheus]" value="true">{#prometheus#|escape:html}<br />
		   </td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#metacreator#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[metacreator]" size="60" value="{$row.metacreator|escape:html}" readonly="readonly"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#metaeditor#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[metaeditor]" size="60" value="{$row.metaeditor|escape:html}" readonly="readonly"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#imagerights#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><input type="text" name="edit[imagerights]" size="60" maxlength="250" value="{$row.imagerights|escape:html}"></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head">{#commentary#|escape:htmlall}</td>
		   <td colspan="2" class="result_detail_data_data"><textarea name="edit[commentary]" cols="50" rows="5">{$row.commentary|escape:html}</textarea></td>
		</tr>
		<tr>
		   <td class="result_detail_data_head"><input type="hidden" name="edit[currentuser]" value="{$user.login|escape:html}"></td>
		   <td colspan="2" class="result_detail_data_data"><input type="button" name="edit[save]" value="{#save#|escape:htmlall}" onClick="saveImage();"></td>
		</tr>

<!-- --------------------------------------------
END default_edit.tpl
--------------------------------------------- -->
