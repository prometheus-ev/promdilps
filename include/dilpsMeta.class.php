<?php
/*      
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
*/


class queryAND
{
	var $or;
	
	function queryAND()
	{
		$this->or = array();
	}
	
	function addOR( $or )
	{
		$this->or[] = $or;
	}
	
	function toSQL()
	{
		$sql = '(0';
		foreach( $this->or as $val )
		{
			$sql .= ' OR '.$val->toSQL();
		}
		$sql .= ')';
		return $sql;
	}
}

class queryOR
{
	var $fld, $val, $exact;
	var $db;
	
	function queryOR( $db, $fld, $val )
	{
		$this->db = $db;
		$this->fld = $fld;
		$this->val = $val;
	}
	
	function toSQL()
	{
		return '`'.$this->fld.'`='.$this->val;
	}
}

class queryString extends queryOR
{
	var $exact;
	
	function queryString( $db, $fld, $val, $exact = true )
	{
		parent::queryOR( $db, $fld, $val );
		$this->exact = $exact;
	}
	
	function toSQL()
	{
		return '`'.$this->fld.'`'.($this->exact?'=':' LIKE ').$this->db->qstr($this->val);
	}
}

class queryRange extends queryOR
{
	
	function queryRange( $db, $fld, $val )
	{
		parent::queryOR( $db, $fld, $val );
	}
	
	function toSQL()
	{
		return '(`'.$this->fld.'_min`<='.$this->val.' AND '.$this->val.'<=`'.$this->fld.'_max`)';
	}
}

class dilpsMeta
{
	var $db;
	var $and;
	
	function dilpsMeta( $db )
	{
		$this->db = $db;
		$this->and = array();
	}
	
	function addQuery( $query )
	{
		$this->and[] = $query;
	}
	
	function toSQL()
	{
		$sql = '(1';
		foreach( $this->and as $val )
		   $sql .= ' AND '.$val->toSQL();
		$sql .= ')';
		return $sql;
	}
}

?>
