<?
/**
 * This is the class that provides the methods that are called by a PEAR::SOAP_Server
 * 
 * some of the function accept a dilps querystruct, that is used to form queries against the "meta" table.
 *   a dilps querystruct is defined by:
 *        querystruct = array(phrases, connectors)
 *        phrases = array(atoms, connectors)
 *        connectors = array ({and | or})
 *        atoms = array(field=>'...', val=>'...', operator=>'...', not=>{0|1})
 * 
 *   only the fields that are used in the "where" part of the query are given.  the select fields as well as the order by are
 *   specified in the functions defined in this class.  Some additional conditions may be added by this classes' functions, for 
 *   example to exclude all images from the results that don't have a status of 'reviewed'
 * 
 *  here is an example querystruct.  
 *  this evaluates to:
 *    where status = reviewed
 *    and (name like '%degas%'
 *         and title like '%steilkuste%') :
 * 
      'querystruct' => 
      array (
        'phrases' => 
        array (
          0 => 
          array (
            'atoms' => 
            array (
              0 => 
              array (
                'field' => 'status',
                'operator' => 'equals',
                'val' => 'reviewed',
                'not' => '0',
              ),
            ),
            'connectors' => 
            array (
              0 => '',
            ),
          ),
          1 => 
          array (
            'atoms' => 
            array (
              0 => 
              array (
                'field' => 'name',
                'operator' => 'like',
                'val' => 'degas',
                'not' => '0',
              ),
              1 => 
              array (
                'field' => 'title',
                'operator' => 'like',
                'val' => 'steilkuste',
                'not' => '0',
              ),
            ),
            'connectors' => 
            array (
              0 => 'and',
              1 => '',
            ),
          ),
        ),
        'connectors' => 
        array (
          0 => 'and',
          1 => '',
        ),
      )
 */


require_once("{$config['includepath']}db.inc.php");
require_once("{$config['includepath']}htmlquery.inc.php");
require_once("{$config['includepath']}dilpsQuery.class.php");
require_once("{$config['includepath']}tools.inc.php");


class DilpsSoapServer {
    
    /* $dispatch_map helps SOAP_Server figure out which parameters
	are used with the methods: */
    var $dispatch_map = array(); 	
    var $needsUtf8Conversion = true;

    function DilpsSoapServer ($needsUtf8Conversion) {
	    $this->needsUtf8Conversion = $needsUtf8Conversion;
	    $this->dispatch_map = array(
    	    'hello' => array(
    	           'in'=> array('inmessage'=>'string'),
    	           'out'=> array('outmessage'=>'string')
        	       ),
    	    'queryOverview' => array(
    	           'in'=> array('querystruct'=>'Struct', 'options'=>'Struct'),
    	           'out'=> array('result'=>'Struct')
        	       ),
    	    'queryCount' => array(
    	           'in'=> array('querystruct'=>'Struct'),
    	           'out'=> array('result'=>'Struct')
        	       ),
    	    'queryDetail' => array(
    	           /*'in'=> array('queryfields'=>'Struct'),*/
    	           'in'=> array('collectionid'=>'integer', 'imageid'=>'string'),
    	           'out'=> array('result'=>'Struct')
        	       ),
	       );
	}

    /**
     * queryOverview
     *
     * querystruct:
     * 
     * options:
     *    page:  page number of query results to return
     *    pagecount:  how many rows per page
     * 
     * @param array $querystruct - a dilps query structure
     * @param array $options - contains 'page' and 'pagesize'
     * @return array
     */
	function queryOverview($querystruct, $options) {
        global $db, $db_prefix;
        if (is_object($querystruct)) {
            $querystruct = _stdclass2array($querystruct);    
        }
        if (is_object($options)) {
            $options = _stdclass2array($options);    
        }
        
        // take care of empty arrays which are mysteriously turned into empty strings by the soap monster:
        if (!is_array($querystruct['connectors'])) {$querystruct['connectors'] = array();}
        if (!is_array($querystruct['phrases'])) {$querystruct['phrases'] = array();}
        if (!is_array($options)) {$options = array();}
        
    	$dbQuery = new dilpsQuery($db, $db_prefix);
    	
	    $fields = "{$db_prefix}meta.type as type,{$db_prefix}meta.collectionid as collectionid,{$db_prefix}meta.imageid as imageid,ifnull({$db_prefix}meta.name1, '') as name1,ifnull({$db_prefix}meta.name2, '') as name2,{$db_prefix}meta.addition as addition, {$db_prefix}meta.title as title, {$db_prefix}meta.dating as dating";
        //return array('result'=>array('count'=>'here'));            	         
    	$where = $dbQuery->buildWhere($querystruct)
    	         ." and {$db_prefix}meta.status = 'reviewed'";
    	$from = "{$db_prefix}meta ";
    	$orderby = "ORDER BY insert_date DESC, imageid DESC";
    	$sql = "SELECT DISTINCT $fields FROM $from WHERE $where $orderby";

    	$page = empty($options['page']) ? 1 : $options['page'];
    	$pagesize = empty($options['pagesize']) ? 12 : $options['pagesize'];
    	$sqls = $sql;
    	$rs = $db->PageExecute( $sql, $pagesize, $page );
        if( !$rs ) {
            return array('result'=>array('error'=>$db->ErrorMsg()));
        }
    	$result = array();
    	$result['lastpage'] = $rs->LastPageNo();
    	$result['maxrecords'] = $rs->MaxRecordCount();
    	$result['rs'] = array();
    	while( !$rs->EOF )
    	{
    		array_walk( $rs->fields, '__stripslashes' );
    		$result['rs'][] = $rs->fields;
    		$rs->MoveNext();
    	}
    	$rs->Close();
    	if( $page > $result['lastpage'] ) $page = $result['lastpage'];
    	$result['page'] = $page;
    	$result['pagesize'] = $pagesize;
    	
    	$response = array('result'=>$result);
    	if ($this->needsUtf8Conversion) {
    	   $response = utf8_encode_recursive($response);
	    }
        return $response;	
    }	

	function queryDetail($collectionid, $imageid) {
        global $db, $db_prefix;
        
        $collectionid = empty($collectionid) ? '0' : intval($collectionid);
        $imageid = empty($imageid) ? '0' : intval($imageid);
        
		$fields = "{$db_prefix}meta.collectionid AS collectionid
                  ,{$db_prefix}meta.imageid AS imageid
                  ,{$db_prefix}meta.type AS type
                  ,{$db_prefix}meta.status AS status
                  ,{$db_prefix}meta.name1id, {$db_prefix}meta.name2id
				  ,{$db_prefix}meta.name1 as name1text
                  ,{$db_prefix}meta.name2 as name2text
				  ,{$db_prefix}meta.addition,{$db_prefix}meta.title,{$db_prefix}meta.dating
				  ,{$db_prefix}meta.material,{$db_prefix}meta.technique,{$db_prefix}meta.format
				  ,{$db_prefix}meta.location as city
                  ,{$db_prefix}meta.locationid as locationid
                  ,{$db_prefix}meta.exp_prometheus, {$db_prefix}meta.exp_sid, {$db_prefix}meta.exp_unimedia
                  ,{$db_prefix}meta.commentary
                  ,{$db_prefix}meta.metacreator, {$db_prefix}meta.metaeditor
                  ,{$db_prefix}meta.imagerights
                  ,{$db_prefix}meta.institution,{$db_prefix}meta.literature
				  ,{$db_prefix}meta.page,{$db_prefix}meta.figure,{$db_prefix}meta.`table`
				  ,{$db_prefix}meta.isbn,base,filename,width,height,xres,yres,size,magick ";
		
		$from = "{$db_prefix}meta, {$db_prefix}img, {$db_prefix}img_base";
		
		$where =  "{$db_prefix}meta.collectionid=$collectionid".
    				" AND {$db_prefix}meta.imageid=$imageid".
    				" and {$db_prefix}meta.status='reviewed'".  // only reviewed images are available via soap queries
		            " and {$db_prefix}meta.collectionid={$db_prefix}img.collectionid".
    				" AND {$db_prefix}meta.imageid={$db_prefix}img.imageid".
    				" AND {$db_prefix}img.img_baseid={$db_prefix}img_base.img_baseid".
    				" AND {$db_prefix}img.collectionid={$db_prefix}img_base.collectionid";
    	
    	
		$sql = "select $fields from $from where $where";
		$row = $db->GetRow( $sql );
		$result = array();
		if( $row ) array_walk( $row, "__stripslashes" );
		$result['id'] = ($row ? "$collectionid:$imageid" : '' );
		$result['rs'] = $row;
    	$response = array('result'=>$result);
    	if ($this->needsUtf8Conversion) {
        	$response = utf8_encode_recursive($response);
    	}
        return $response;	
    }	
    
    
	function queryCount($querystruct) {
        global $db, $db_prefix;
        if (is_object($querystruct)) {
            $querystruct = _stdclass2array($querystruct);    
        }
        
        // take care of empty arrays which are mysteriously turned into empty strings by the soap monster:
        if (!is_array($querystruct['connectors'])) {$querystruct['connectors'] = array();}
        if (!is_array($querystruct['phrases'])) {$querystruct['phrases'] = array();}
        
    	$dbQuery = new dilpsQuery($db, $db_prefix);
    	
       $fields = 'count(*) as count'; 
       
        //return array('result'=>array('count'=>'here'));            	         
    	$where = $dbQuery->buildWhere($querystruct)
    	         ." and {$db_prefix}meta.status = 'reviewed'";
    	$from = "{$db_prefix}meta ";
    	$sql = "SELECT DISTINCT $fields FROM $from WHERE $where";

	    if ($rs = $db->GetRow($sql)) {
	        $result = $rs;
	    } else  {
	        $result = array('error'=>$db->ErrorMsg());
        	if ($this->needsUtf8Conversion) {
            	$result = utf8_encode_recursive($result);
        	}
	    }
    	    
    	$response = array('result'=>$result);
        return $response;	
	}

	function hello($name) {
		return 'Hello '.$name;
	}
}
?>