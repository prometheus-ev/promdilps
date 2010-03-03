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

	/*
	 * PHP helper functions
	 * -------------------------------------------------------------
	 * File:     		tools.inc.php
	 * Purpose:  provide helper functions for the dilps 
	 *					system
	 * -------------------------------------------------------------
	 */
	 
	
	/*
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	*/
	
	global $config;
	//include_once($config['dilpsdir'].'includes.inc.php');
	include_once($config['includepath'].'mime'.DIRECTORY_SEPARATOR.'Type.php');
	//include_once($config['includepath'].'dating.inc.php');

	/**
	 *	execute stripslashes on a string
	 *
	 *	applies stripslashes to a string reference
	 *
	 *	@access		public
	 *	@param 		string $str
	 *	@return		void
	 *
	 */
	 

	function __stripslashes( &$str )
	{
		$str = stripslashes( $str );
	}
	
	/**
	 *	execute stripslashes on an array
	 *
	 *	applies stripslashes to all elements of an array (passed via reference)
	 *
	 *	@access		public
	 *	@param 		array $arr
	 *	@return		void
	 *
	 */
	
	function __stripslashes_array( &$arr )
	{
		array_walk( $arr, '__stripslashes' );
	}
	
	
	/**
	 *	deep-converts object to array
	 *
	 *	@access		public
	 *	@param 		object $o
	 * 	@return		array
	 *
	 */
    function _stdclass2array($o) {
        $a = array();
        foreach ($o as $p => $v){
            if (is_object($v)) {
                $v = (array) $v;
            }
            if (is_array($v)) {
                $a1 = array();
                foreach ($v as $p1 => $v1){
                    if (is_object($v1)  || is_array($v1)) {
                        $a1[$p1] = _stdclass2array($v1);
                    } else {
                        $a1[$p1] = $v1;
                    }
                }
                $a[$p] = $a1;
            } else { 
                $a[$p] = $v;
            }
        }
        return  $a;
    }
    
    function utf8_encode_recursive($array) {
        foreach ($array as $key => $val){
            if (is_string($val)) {
                    $array[$key] = utf8_encode($val);
            }
            elseif (is_array($val)){
                $array[$key] = utf8_encode_recursive($val);
            }
        }
        return $array;
    }
    
    function utf8_decode_recursive($array) {
        foreach ($array as $key => $val){
            if (is_string($val)) {
                    $array[$key] = utf8_decode($val);
            }
            elseif (is_array($val)){
                $array[$key] = utf8_decode_recursive($val);
            }
        }
        return $array;
    }
    
	/**
	 *	extracts collectionid and imageid from a string
	 *
	 *	applies stripslashes to all elements of an array (passed via reference)
	 *
	 *	@access		public
	 *	@param		string	$id
	 *	@param		int		$sammlung
	 *	@param		int		$imageid
	 *	@return		void
	 *
	 */
	
	function extractID( $id, &$sammlung, &$imageid )
	{
		$p = strpos( $id, ':' );
		if( !$p )
		{
			$sammlung = false;
			$imageid = false;
			return;
		}
		$sammlung = intval( substr( $id, 0, $p ));
		$imageid = substr( $id, $p+1 );
	}
	
	
	/**
	 *	check, if a directory exists, is writeable and create
	 *
	 *	this function checks, if the specified directory already
	 * exists. Optional actions are a check for write access
	 * and a create in the case of non-existance
	 *
	 *	@access		public
	 *	@param 		string 	$dir
	 *	@param 		bool		$test_writeable
	 *  @param		bool		$create_nonexistant
	 *  @param		bool		$create_perms
	 *	@return		bool
	 *
	 */
	
	function check_dir($dir, $test_writeable = false, $create_nonexistant = false, $create_perms = 0755)
	{		
		if (!is_dir($dir))
		{
			if ($create_nonexistant)
			{
				$ret = mkdir($dir,$create_perms);
				return $ret;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if ($test_writeable)
			{
				$ret = is_writeable($dir);
				return $ret;
			}
		}
	}
	
	/**
	 *	recursive copy function for php
	 *
	 *	recursive copy function for php with some error checking
	 *
	 *	@access		public
	 *	@param 		string $dirsource
	 *	@param 		string $dirdest
	 *	@return		bool
	 *
	 */
	
	function copy_recursive($dirsource, $dirdest)
	{
		if(is_dir($dirsource))
		{
			$dir_handle=opendir($dirsource);
		}
		else
		{
			echo ("copy_recursive error: source cannot be opened\n<br>\n");
			return false;
		}
	
		if (!is_dir($dirdest))
		{
			echo ("copy_recursive error: destination is not a directory\n<br>\n");
			return false;
		}
		else
		{
			if (!is_dir($dirdest.'/'.$dirsource))
			{
				mkdir($dirdest.'/'.$dirsource, 0755);
			}
	
			while($file = readdir($dir_handle))
			{
				if($file != "." && $file != "..")
				{
					if(!is_dir($dirsource.'/'.$file))
					{
						copy ($dirsource.'/'.$file, $dirdest.'/'.$dirsource.'/'.$file);
					}
					else
					{
						copy_recursive($dirsource.'/'.$file, $dirdest);
					}
				}
			}
			closedir($dir_handle);
		return true;
		}
	}	
	
	/**
	 *	recursive delete function for php
	 *
	 *	recursive delete function for php with some error checking
	 *
	 *	@access		public
	 *	@param 		string $dir
	 *	@return		bool
	 *
	 */
	
	function delete_recursive($dir)
	{
		if(is_dir($dir))
		{
			$dir_handle = opendir($dir);
		}
		else
		{
			echo ("delete_recursive error: directory cannot be opened\n<br>\n");
			return false;
		}
	
		while($file = readdir($dir_handle))
		{
			if($file != "." && $file != "..")
			{
				if(!is_dir($dir.'/'.$file))
				{
					unlink($dir.'/'.$file);
				}
				else
				{
					delete_recursive($dir.'/'.$file);
				}
			}
		}
		closedir($dir_handle);
		return true;
	}
	
	/**
	 *	recursive directory read for image upload
	 *
	 *	read a directory and all its subdirectories and
	 *	store the relevant path information in an array,
	 *	additionally stores the directories to be
	 *	removed in another array
	 *
	 *	@access		public
	 *	@param 		string 	$dir
	 *	@param 		array 	$files
	 *	@return		bool
	 *
	 */
	
	function read_recursive($dir, &$files, &$remove)
	{
		if(is_dir($dir))
		{
			$dir_handle=opendir($dir);
	
			while($file = readdir($dir_handle))
			{
				if($file != "." && $file != "..")
				{
					if(is_dir($dir.addslashes(DIRECTORY_SEPARATOR.$file)))
					{
						$remove[] = $dir.addslashes(DIRECTORY_SEPARATOR.$file);
						read_recursive($dir.addslashes(DIRECTORY_SEPARATOR.$file), $files,$remove);
					}
					else
					{
						$files[] = $dir.DIRECTORY_SEPARATOR.$file;
					}
				}
			}
	
			closedir($dir_handle);
		}
		else
		{
			if (is_file($dir))
			{
				$files[] = $dir;
			}
			else
			{
				echo ("read_recursive error: \n<br>\n");
				echo ("directory cannot be opened: ".$dir."\n<br>\n");
				return false;
			}
		}
	
		return true;
	}
	
	/**
	 *	user defined compare function
	 *
	 *	compares string regarding to string length
	 *
	 *	@access		public
	 *	@param 		string 	$a
	 *	@param 		string 	$b
	 *	@return		int
	 *
	 */
	
	function cmp ($a, $b) {
	   if (strlen($a) == ($b)) return 0;
	   return (strlen($a) < strlen($b)) ? 1 : -1;
	}
	
	/**
	 *	determines the time that passed since a given year
	 *
	 *	determines the time that passed since the year passed to the
	 *	function, the resolution can be specified via a and b
	 *
	 *	@access		public
	 *	@param		string	$year
	 *	@param		double	$a
	 *	@param		double	$b
	 *	@return		void
	 *
	 */
	
	function intervalFromYear( $year, $a = 0.0001, $b = 0.00008 )
	{
		$jd = GregorianToJD( 1, 1, intval( $year ));
		$now = UnixToJD( time());
		$d = $now - $jd;
		$res = $d*$a + $d*$b*$b;
		return round(max( 1, $res)) * ($year < -3500 ? 1 : 365);
	}
	
	/**
	 *	wrapper for GregorianToJD
	 *
	 *	applies some extra checking on GregorianToJD
	 *
	 *	@access		public
	 *	@param		int	$month
	 *	@param		int	$day
	 *	@param		int	$year
	 *	@return		void
	 *
	 */
	
	function _GregorianToJD( $month, $day, $year )
	{
	//	echo "_GregorianToJD( $month, $day, $year )\n";
	   if( $year < -3500 ) return $year;
		if( $year == 0 ) return GregorianToJD( $month, $day, 1 );
	   return GregorianToJD( $month, $day, $year );
	}
	
	/**
	 *	formats a datespring according the DILPS-rules
	 *
	 *	formats a given datestring according to the rules
	 *	in the DILPS database, returns the result in datelist
	 *
	 *	@access		public
	 *	@param		object	$db
	 *	@param		string	$datestring
	 *	@param		array	$datelist
	 *	@return		void
	 *
	 */
	
	function dating( $db, &$datestring, &$datelist )
	{
		global $db_prefix;
		static $dating_match = NULL;
		static $dating_replacement = NULL;
		static $dating_pattern = NULL;
		if( $dating_match == NULL )
		{
			$sql = "SELECT * FROM {$db_prefix}dating_rules WHERE `type`='match' ORDER BY seq ASC";

			$dating_match = $db->GetArray( $sql );
			
			for( $i = 0; $i < sizeof( $dating_match ); $i++ )
			{
				$dating_match[$i]['regexp'] = strtolower( stripslashes( $dating_match[$i]['regexp'] ));
			}
		}
		if( $dating_replacement == NULL )
		{
			$sql = "SELECT * FROM {$db_prefix}dating_rules WHERE `type`='replace' ORDER BY seq ASC";
			$rs = $db->Execute( $sql );
			
			$dating_pattern = array();
			$dating_replacement = array();
			while( !$rs->EOF )
			{
				$dating_pattern[] = strtolower( stripslashes( $rs->fields['from'] ));
				$dating_replacement[] = strtolower( stripslashes( $rs->fields['to'] ));
				$rs->MoveNext();
			}
			$rs->Close();
		}
		$datelist = array();
	//	echo "[$datestring] --> ";
		$datestring = trim( preg_replace( $dating_pattern, $dating_replacement, strtolower( $datestring )));
	//	echo "[$datestring]\n";
		$dates = explode( ';', $datestring );
	//    print_r( $dates );
		foreach( $dates as $date )
		{ 
			for( $i = 0; $i < sizeof( $dating_match ); $i++ )
			{
				$matches = array();
				if( preg_match( $dating_match[$i]['regexp'], $date, $match ))
				{
					$result = array( 'from' => eval( $dating_match[$i]['from'] ),
										 'to' => eval( $dating_match[$i]['to'] ));
					$datelist[] = $result;
					break;
				}
			}
		}
		return true;
	}
	
	/**
	 *	read a file's mimetype
	 *
	 *	first tries the PHP internal mime_content_type,
	 * then falls back to PEAR library, the to unix file
	 * to detect mimetype.
	 *
	 *	@access		public
	 *	@param 		string	$file
	 *  @param		string	&$mime
	 *	@return		bool
	 *
	 */
	
	function read_mime($file, &$mime, $fallback_extension)
	{
		global $file_binary, $ext_to_mime;
		
		$mime = '';
		
		if (function_exists('mime_content_type'))
		{
			$mime = mime_content_type($file);
		}
		
		if ($mime != '')
		{
			return true;
		}
		
		if (function_exists('MIME_TYPE::autoDetect'))
		{
			$mime = MIME_Type::autoDetect($file);
		}
		
		if ($mime != '')
		{
			return true;
		}
		
		unset($result);
		
		$cmd = $file_binary." -b -i \"".$file."\"";
		exec($cmd, $result);
		
		if (isset($result[0]))
		{
			if( preg_match( "/^[a-zA-Z0-9-]+\/[a-zA-Z0-9-]+$/", trim($result[0])))				
			{
				$mime = trim($result[0]);
			}
		}
		else 
		{
			// probably this version of file is too old to understand "-b", try without
			$cmd = $file_binary." \"".$file."\"";
			exec($cmd, $result);
			
			// strip path from result
			
			$result[0] = trim(substr($result[0],strlen($file)+1));
			
			// combine the results
			
			$tmp_result = @explode(" ",$result[0]);
			
			if (isset($tmp_result[2]))
			{			
				if($tmp_result[2] == 'file')
				{
					$mime = $tmp_result[1].'/'.$tmp_result[0];
				}
			}
		}
		
		if ($mime != '')
		{
			return true;
		}
		else 
		{		
			if ($fallback_extension)
			{				
				if (key_exists($fallback_extension,$ext_to_mime))
				{
					$mime = @$ext_to_mime[$fallback_extension];
				}
			}
		}		
	
		if ($mime != '')
		{
			return true;
		}
		else
		{			
			return false;
		}
		
	}
	
	/**
	 *	extract information from an image
	 *
	 *	tries to extract image information
	 * with imagemagick identify
	 *
	 *	@access		public
	 *	@param 		string	$file
	 *  @param		array	&$imagedata
	 *	@return		bool
	 *
	 */
	
	function read_image($file, &$imagedata)
	{
		global $imagemagick_identify;
		
		unset($result);		 
		
		$cmd = $imagemagick_identify
						." -depth 8 -format "
						."\"%m %w %h %b\" \"".$file."\"";
		
		exec($cmd, $result);
		
		if (!$result)
		{
			return false;
		}
		
		if(!preg_match_all( "/[\w(\/|\.)]+/", trim($result[0]), $matches))
		{
			return false;
		}
		else
		{
			$imagedata["type"] 	= $matches[0][0];
			$imagedata["width"]	= $matches[0][1];
			$imagedata["height"]	= $matches[0][2];
			
			$imagedata["depth"]	= "8 bit";			
			$rawsize						= $matches[0][3];
			
			// some versions of image magick give incorrectly formatted sizes
			if (strpos($rawsize,'m') > 0)
			{
				$imagedata["size"] = intval(doubleval($rawsize) * 1048576.0);
			}
			else if (strpos($rawsize,'k') > 0)
			{
				$imagedata["size"] = intval(doubleval($rawsize) * 1024.0);
			}
			else
			{
				$imagedata["size"] = $rawsize;
			}			
			
			/*
			echo ("Imagedata:\n<br>\n");
			print_r($imagedata);
			*/
			
			return true;
		}		
		
		
	}
	
	/**
	 *	convert an image to a JPEG-file
	 *
	 *	converts an image to a JPEG file of the
	 * specified resolution,
	 * sharpens thumbnails, applies some
	 * corrections to ensure conversion
	 * success
	 *
	 *	@access		public
	 *	@param 		string	$input
	 *	@param 		string	$output
	 *	@param		string	$to_res
	 *	@param		bool	$is_thumbnail
	 *	@return		bool
	 *
	 */
	
	function convert_image($input, $output, $to_res,$is_thumbnail = false)
	{
		global $imagemagick_convert;	
		
		if ($is_thumbnail)
		{
			// we sharpen thumbnails
			$cmd = $imagemagick_convert
							." \"".$input."\" "
							."-flatten -resize ".$to_res
							." -colorspace RGB -sharpen 4 \"".$output."\"";
		}
		else
		{
			$cmd = $imagemagick_convert
							." \"".$input."\" "
							."-flatten -resize ".$to_res
							." -colorspace RGB \"".$output."\"";
		}
		
		unset($result);
		exec($cmd, $result, $ret);
						
		if (empty($result))
		{
			// probably this version of convert is too old, try parameter "-scale"
			// instead of "-resize"
			
			if ($is_thumbnail)
			{
				// we sharpen thumbnails
				$cmd = $imagemagick_convert
								." \"".$input."\" "
								."-flatten -scale ".$to_res
								." -colorspace RGB -sharpen 4 \"".$output."\"";
			}
			else
			{
				$cmd = $imagemagick_convert
								." \"".$input."\" "
								."-flatten -scale ".$to_res
								." -colorspace RGB \"".$output."\"";
			}
			
			unset($result);
			exec($cmd, $result, $ret);
			
		}
		
		if ($ret == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	
	/**
	 *	insert image data into database
	 *
	 *	
	 *
	 *	@access		public
	 *	@param 		int		$collectionid
	 *	@param 		int		$imageid
	 *	@param 		int		$img_baseid
	 * @param		string	$filename
	 * @param		array	$img_data
	 *	@param 		string	$time
	 *	@return		bool
	 *
	 */
	
	function insert_img($collectionid, $imageid, $img_baseid, $filename, $img_data, $time)
	{
		global $db, $db_prefix;		
		
		// insert information into database	
		$sql = 	"INSERT INTO ".$db_prefix."img "
				."(`collectionid`, `imageid`, `img_baseid`, `filename`, `width`, "
				."`height`, `size`, `magick`, `insert_date`) "
				."VALUES ("
				.$db->qstr($collectionid).","
				.$db->qstr($imageid).","
				.$db->qstr($img_baseid).","
				.$db->qstr($filename).","
				.$db->qstr($img_data['width']).","
				.$db->qstr($img_data['height']).","
				.$db->qstr($img_data['size']).","
				.$db->qstr($img_data['type']).","
				.$db->qstr($time).")";
	
		$rs = @$db->Execute($sql);
	
		if (!$rs)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	 *	insert image metadata into database
	 *
	 *	insert image metadata into database
	 *
	 *	@access		public
	 *	@param 		int		$collectionid
	 *	@param 		int		$imageid
	 *	@param 		string	$time
	 *	@param 		string	$creator
	 *	@return		bool
	 *
	 */
	
	function insert_meta($collectionid, $imageid, $time, $creator)
	{
		global $db, $db_prefix;		
		
		// insert information into database	
		$sql = 	"INSERT INTO ".$db_prefix."meta "
					."(`collectionid`, `imageid`, `type`, `status`, `insert_date`, `metacreator`) VALUES ("
					.$db->qstr($collectionid).","
					.$db->qstr($imageid).","
					."'image',"
					."'new',"
					.$db->qstr($time).","
					.$db->qstr($creator)
					.")";
	
		$rs = @$db->Execute($sql);		
	
		if (!$rs)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	
	/**
	 *	insert image into a group
	 *
	 *	insert the image into the specified img_group
	 *
	 *	@access		public
	 *	@param 		int		$collectionid
	 *	@param 		int		$imageid
	 *	@param 		string	$time
	 *	@param 		string	$creator
	 *	@return		bool
	 *
	 */
	
	function insert_img_group($groupid, $collectionid, $imageid)
	{
		global $db, $db_prefix;		

		$sql = "INSERT INTO ".$db_prefix."img_group "
				."(groupid, collectionid, imageid) "
				."VALUES ("
				.$db->qstr($groupid)." ,"
				.$db->qstr($collectionid)." ,"
				.$db->qstr($imageid).")";

		$rs  = @$db->Execute ($sql);		
		
		if (!$rs)
		{
			return false;
		}
		else
		{
			return true;
		}
	}	
	
	/**
	 *	modified version of get_groupid_where_clause
	 *
	 *	this version is working without the query array
	 *
	 *	@access		public
	 *	@param 		int		$groupid
	 *	@param 		object	$db
	 *	@param 		string	$db_prefix
	 *	@param 		bool	$subgroups
	 *	@return		string
	 *
	 */
	
	// 

	function get_groupid_where($groupid, &$db, $db_prefix, $subgroups = true) {
	    
	    $where = '';
	    
		if (!empty($groupid))
		{
			// our result
			$groups = array();
			// first id is the give one
			$groups[] = $groupid;
			$db->SetFetchMode(ADODB_FETCH_ASSOC);
			$sql = "SELECT id FROM ".$db_prefix."group WHERE "
						."parentid = ".$db->qstr($groupid)
						." ORDER BY id"; 	
			$rs  = $db->Execute($sql);
			if (!$rs || !$subgroups)
			{
				// we have no subgroups, just query one id
				$where .= " AND {$db_prefix}img_group.groupid = ".$db->qstr($groupid);
			}
			else
			{
				// get next sublevel		
				while (!$rs->EOF)
				{
					// add group to result array
					$groups[] = $rs->fields['id'];
					// get next but one sublevel, if available
					$sql2 = "SELECT id FROM ".$db_prefix."group WHERE "
								."parentid = ".$db->qstr($rs->fields['id'])
								." ORDER BY id"; 	
					$rs2 = $db->Execute($sql2);
					
					while(!$rs2->EOF)
					{
						$groups[] = $rs2->fields['id'];				
						$rs2->MoveNext();
					}			
					$rs->MoveNext();
				}
				$where .= " AND (0 ";
				foreach ($groups as $gid)
				{
					$where .= " OR {$db_prefix}img_group.groupid = ".$db->qstr($gid);
				}
				$where .= ") ";
			}
		}
	    return $where;
	}

?>
