<?php
    global $config;
    require_once($config['includepath'].'thesauri/soundex_fr.php');
    
    class thesaurusDB 
    {
        var $sounds = array();
        var $pattern = "[[:space:],]+";
        var $names_table = 'artist';
        var $location_table = 'location';
        var $meta_table = 'meta';
        
        var $db; 
        
        function thesaurusDB($db, $db_prefix) {    
            
            $this->names_table = $db_prefix.$this->names_table;
            $this->location_table = $db_prefix.$this->location_table;
            $this->meta_table = $db_prefix.$this->meta_table;
            
            $this->db = $db;
        }            
        
        function get_html_location($location) {
            return $location;
        }

        
        
        function query($subject, $searchterm, $searchtype = 'location') {
            
            switch ($subject) {
                
            	case 'names':

            	   switch ($searchtype) {
            	       
            	   	case 'parentid':
            	   	   $query = $this->get_names_query_from_parent_id($searchterm);
            	   		break;
            	   		
            	   	case 'metaid':
            	   	   $query = $this->get_names_query_from_meta_id($searchterm);
            	   		break;
            	   		
            	   	case 'name':	
            	   	default:
            	       $query = $this->get_names_query($searchterm);
            	   		break;
            	   }
            	   break;
            		
            	case 'locations':
            	
            	   switch ($searchtype) {
            	       
            	   	case 'metaid':
            	   	   $query = $this->get_location_from_meta_id_query($searchterm);
            	   	   break;
            	   	   
            	   	case 'location':
            	   	default:
            	   	   $query = $this->get_location_query($searchterm);
                       break;
                       
            	   }
            	   break;
            	   
            	default:
            	   die ("Unrecognized subject to search: $subject");
            	   break;
            }
//var_export($query); exit;
            $res = $this->db->GetAll($query);

            if ($res === false) {
                die ($this->db->ErrorMsg());
            } 

            return $res;
        }
        
        /*
        * query the artist names based on the search string and the sounds in the search string
        */
        function get_names_query($searchterm) {
            
            // with version 4.0.x of mysql, the efficient execution of this query requires in fact two queries.
            // with version >= 4.1, it should be possible to have a quick, efficient single query (using a subquery).
            
            $fields = "if(names.preferred_name_id = 0, id, names.preferred_name_id) as id";
            $from = "$this->names_table names";
            $where = '(0';
            $limit = "500";
            $score = '(0';
            
            $words = split($this->pattern, $searchterm);
            if (empty($words)) {
                $words = array();
            }
            
            foreach ($words as $key=>$word) {
                
                $where .= " or lower(names.name) like lower('%$word%')";
                $score .= " + if(lower(names.name) like lower('%$word%'), 2, 0)";

                $sound = trim(soundex2($word));
                
                if ($sound != '') {
                    $where .= " or names.sounds like '%.{$sound}.%'";
                    $score .= " + if(sounds like '%.{$sound}.%', 1, 0) ";
                }
                
            }
            
            $score .= ') as score';
            
            $where .= ')';
            
            
            // execute the first query, the results of which will be used to build the second query
            $query = "select distinct $fields from $from where $where limit $limit";

            $res = $this->db->GetCol($query);
            if ($res === false) {
                die ($this->db->ErrorMsg());
            } 
            
            $parentids = implode(',', $res);
            if (empty($parentids)) {
                $parentids = '0';
            }
            
            $where = "names.id in ($parentids)";
            $fields = "names.id, names.name as name";
            //$orderby = "score desc, name like '$searchterm%' desc, name";
            $orderby = "lower(names.name) like lower('$searchterm%') desc, names.name";
            $limit = "500";
            
            $query = "select distinct $fields, $score from $from where $where order by $orderby limit $limit";

            return $query;
        }
        

        function get_names_query_from_parent_id($searchterm) {
            
            $fields = "names.id, names.name as name";
            $from = "$this->names_table names";
            $where = "names.preferred_name_id = $searchterm";
            //$orderby = "names.preferred_name_id";
            $orderby = "names.name";
            $limit = "500";
            
            $query = "select $fields from $from where $where order by $orderby limit $limit";
            
            return $query;
        }
        
        
        function get_names_query_from_meta_id($searchterm) {
            
            list($collectionid, $imageid) = explode(':', $searchterm);
            
            $fields = "names.id, names.name as name";
            $from = "$this->names_table names, $this->meta_table meta";
            $where = "meta.collectionid = '$collectionid' and meta.imageid = '$imageid'".
                " and names.id in  (meta.name1id, meta.name2id)";
            $orderby = "meta.name1id = names.id desc";
            
            $query = "select $fields from $from where $where order by $orderby";

            return $query;
        }
        

        function get_location_query($searchterm) {
            
            $fields = "locations.id, locations.location as location, locations.loc_type as loc_type, locations.hierarchy as hierarchy";
            $from = "$this->location_table locations";
            $where = '0';
            $orderby = "score desc, location like '$searchterm%' desc, location";
            $limit = "500";
            $score = '(0';
            
            $words = split($this->pattern, $searchterm);
            if (empty($words)) {
                $words = array();
            }
            
            foreach ($words as $key=>$word) {
                
                $sound = trim(soundex2($word));
                
                if ($sound != '') {
                    $where .= " or lower(locations.location) like lower('%$word%')" .
                              " or locations.sounds like '%.{$sound}.%'";
                    $score .= " + if(lower(locations.location) like lower('%$word%'), 2, 0)" .
                              " + if(locations.sounds like '%.{$sound}.%', 1, 0) ";
                }
                
            }
            $score .= ') as score';
            
            $query = "select $fields, $score from $from where $where order by $orderby limit $limit";
            
            return $query;
        }

        function get_location_from_meta_id_query($searchterm) {
            
            $fields = "locations.id, locations.location as location, locations.loc_type as loc_type, locations.hierarchy as hierarchy";
            list($collectionid, $imageid) = explode(':', $searchterm);
            
            $from = "$this->location_table locations, $this->meta_table meta";
            $where = "meta.collectionid = $collectionid and meta.imageid = $imageid".
                    " and meta.locationid = locations.id";
            
            $query = "select $fields from $from where $where";
            
            return $query;
        }
        
        
        /* loads legacy artist names and cities into thesaurus tables from a legacy data table
        *    and updates meta data table with the ids of the newly inserted records.
        *
        */
        function _load_legacy_data($legacy_table, $meta_table) {
            /* the legacy table was prepared with sql like this:
            create table legacy_data as select collectionid, imageid, name, givenname, city from ng_meta;
            */
            
            // get the legacy data
            $res = $this->db->GetAll("select * from $legacy_table");
            
            if (!$res) {
                die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg());
            } 

            foreach ($res as $row) {
                
            // prepare the legacy data and insert into location table
                $location = trim($row['city']);
                if ($location != '') {
                    
                    //search for existing matching location
                    $existing = $this->db->GetOne("select id from $this->location_table where lower(location)=lower(".$this->db->qstr($location).")");
                    
                    if ($existing != false) {
                        
                        $locationid = $existing;
                        
                    } else {
                        
                        // if there was an error, die, otherwise we know that no row matched
                        $err = $this->db->ErrorMsg();
                        if (!empty($err)) {
                            die (__FILE__ . ':' . __LINE__ . ': ' . $err);
                        }
                        
                        $location_sound = $this->get_sounds_string($location);
                        
                        //$location_source_id = -1 * ( $row['collectionid'] * 1000000 + $row['imageid']);
                        $location_source_id = "{$row['collectionid']}:{$row['imageid']}";
                        
                        $insert = $this->db->Execute("insert into $this->location_table (src, source_id, location, loc_type, sounds) values ('dilps', '$location_source_id', ".$this->db->qstr($location).", '83002/inhabited place', '$location_sound')");
                        if (!$insert) {
                           die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg());
                        } 

                        $locationid = $this->db->Insert_ID();
                    }
                    
                } else {
                    $locationid = 0;
                }
                
                /*$insert = $this->db->Execute("update $meta_table set locationid = '$locationid'" .
                    " where collectionid = {$row['collectionid']} and imageid = {$row['imageid']}");
                    
                if (!$insert) {
                   die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg());
                } */
            
            
            
            // prepare the legacy data and insert into artist name's table
                $name = trim($row['name']);
                $givenname = trim($row['givenname']);
                if ($name != '' && $givenname != '') {
                    $name .= ', ';
                }
                $name .= $givenname;
                if ($name != '') {
                    
                    //search for existing matching name
                    $existing = $this->db->GetOne("select id from $this->names_table where lower(name)=lower(".$this->db->qstr($name).")");
                    
                    if ($existing != false) {
                        
                        $nameid = $existing;
                        
                    } else {
                        
                        // if there was an error, die, otherwise we know that no row matched
                        $err = $this->db->ErrorMsg();
                        if (!empty($err)) {
                            die (__FILE__ . ':' . __LINE__ . ': ' . $err);
                        }
                        
                        $name_sound = $this->get_sounds_string($name);
                        $insert = $this->db->Execute("insert into $this->names_table (name, src, sounds) values (".$this->db->qstr($name).", 'dilps', '$name_sound')");
                        if (!$insert) {
                           die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg());
                        } 

                        $nameid = $this->db->Insert_ID();
                    }
                    
                } else {
                    $nameid = 0;
                }
                
                $sql = "update $meta_table set".
                    " name1id = '$nameid', name1 =  ".$this->db->qstr($name).", name1sounds = '$name_sound'".
                    ", locationid = '$locationid', location=".$this->db->qstr($location).", locationsounds = '$location_sound'".
                    " where collectionid = {$row['collectionid']} and imageid = {$row['imageid']}";
                    
                $insert = $this->db->Execute($sql);
                    
                if (!$insert) {
                   die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n [$sql]");
                } 
            
            }
        }
        
        /*
        * loads artist name information from a Prometheus thesaurus file
        */
        function loadDB_names($sourcefile) {    
            global $config;
            
            ini_set('include_path',  $config['includepath'].'pear'.':'.ini_get('include_path'));
            ini_set("memory_limit", "64M");
            
            setlocale(LC_CTYPE, 'en_US.iso88591');

            $handle = fopen($sourcefile, "rb");
            $contents = fread($handle, filesize($sourcefile));
            fclose($handle);            
            $start = strpos($contents, '<');
            $contents = substr($contents, $start);
            
            include_once('XML/Unserializer.php');
            $options = array('forceEnum' => array('name'), 'encoding'=>'ISO-8859-1');
            $unserializer = &new XML_Unserializer($options);
            $result = $unserializer->unserialize($contents);    
            if (PEAR::isError($result)) {
                echo "\nerror unserializing\n";
                die($result->getMessage());
            }
            
            $xml = $unserializer->getUnserializedData();
            
            foreach ($xml['term'] as $term) {
                $name = utf8_decode($term['name'][0]);
                
                // insert this name into the table
                $sounds = $this->get_sounds_string($name);

                $result = $this->db->Execute("insert into $this->names_table (name, src, sounds) values (" . $this->db->qstr($name) . ", 'Prometheus', '$sounds')");
                if ($result !== false) {
                    $id = $this->db->insert_id();
                    
                } else {
                    $id = 0;
                    die('Invalid query: ' . $this->db->ErrorMsg());
                }
                if (!empty($term['sub']['name'])) {
                    foreach ($term['sub']['name'] as $subname) {
                        $subname = utf8_decode($subname);
                        $sounds = $this->get_sounds_string($subname);

                        $result = $this->db->Execute("insert into $this->names_table (name, src, sounds, preferred_name_id) values (" . $this->db->qstr($subname) . ", 'Prometheus Künstler Norm Datei', '$sounds', '$id')");
                        if ($result === false) {
                            die('Invalid query: ' . $this->db->ErrorMsg());
                        }
                    }
                }
            }
        }
        

        /*
        * loads location information from a getty TGN thesaurus file
        * you should backup the location table and truncate the location table before running this function!
        * @param string $sourcedir directory where the main tgn xml files are (and only the main files!)
        */
        //function loadDB_locations($sourcedir) {    
        function loadDB_locations($sourcefile) {    
//bk: just do one file at a time, because php runs out of memory if we try to loop through too many huge xml files using pear's Unserializer             
            
            global $config;
            
            //get the list of files
            /*$files = array();
            if ($handle = opendir($sourcedir)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $files[] = "$sourcedir/$file";
                    }
                }
                closedir($handle);
            } else {
                die ("Unable to open directory ($sourcedir) for reading.  Check that the path is correct.");
            }
            
            if (empty($files)) {
                die ("No files found in directory ($sourcedir).");
            }*/
            
            
            $source = 'Getty TGN';
            
            ini_set('include_path', $config['includepath'].'pear'. ':' . ini_get('include_path'));
            //ini_set("memory_limit", "64M");
            ini_set("memory_limit", "1024M");
            setlocale(LC_CTYPE, 'en_US.iso88591');
            include_once('XML/Unserializer.php');
            

            $options = array('parseAttributes' => true, 'encoding'=>'ISO-8859-1');
            
            //foreach ($files as $sourcefile) {
echo "processing file $sourcefile\n";                
                // strange character at beginning of file was causing problems, voilà cette hack:
                $handle = fopen($sourcefile, "rb");
                $contents = fread($handle, filesize($sourcefile));
                fclose($handle);            
                $start = strpos($contents, '<');
                $contents = substr($contents, $start);
                $unserializer = &new XML_Unserializer($options);
                $result = $unserializer->unserialize($contents);    

                unset($contents);  // free up some MB of memory
                
                if (PEAR::isError($result)) {
                    echo("Error unserializing while processing file $sourcefile: " . $result->getMessage());
                    return 1;
                }
                $xml = $unserializer->getUnserializedData();
                $passed_intro = false;

                $rowid = $this->_get_max_column_value($this->location_table, "id");
                echo "starting database load: max id in $this->location_table is $rowid\n";                                                                                
                foreach ($xml['Subject'] as $subject) {
                    if (!$passed_intro && $subject['Subject_ID'] == 1000000) {
                        
                        $passed_intro = true;
                        
                    } else {
                        
                        $location = $subject['Terms']['Preferred_Term']['Term_Text'];
                        $sounds = $this->get_sounds_string($location);
                        $location_type = $subject['Place_Types']['Preferred_Place_Type']['Place_Type_ID'];
                        $hierarchy = preg_replace('/ \| World$/', '', $subject['Hierarchy']);
                        $sourceid = $subject['Subject_ID'];
                        $parentid = $subject['Parent_Relationships']['Preferred_Parent']['Parent_Subject_ID'];
    
                        $sql = "insert into $this->location_table (src, source_id, parent_source_id, location, loc_type, hierarchy, sounds) values ('$source','$sourceid', '$parentid', " . $this->db->qstr($location) . ", '$location_type', ".$this->db->qstr($hierarchy).", '$sounds')";
                        
                        $result = $this->db->Execute($sql);
                        if ($result == false) {
                            echo("Error inserting data into DB while processing file $sourcefile: " . $this->db->ErrorMsg(). "<br/>\nquery:<br/>\n$sql\n");
                            return 1;
                        }
                    }
                    
                }
                
                $rowid = $this->_get_max_column_value($this->location_table, "id");
                echo "finished database load: max id in $this->location_table is $rowid\n";                                                                           /*                unset ($xml);
                unset ($unserializer);
                unset($result);*/
            //}
            return 0;
        }
        
        function _get_max_column_value($table, $column, $exitOnFailure = true) {
            $sql = "select max($column) from $table";
            $value =& $this->db->getOne($sql);
    
            if (PEAR::isError($value)) {
                echo "Error in _get_max_column_value: ".$data->getMessage()."\n";
                if ($exitOnFailure) {
                    exit(1);
                } else {
                    $value = "Error getting value";
                }
            }
            
            return $value;
        }
        
        
        function get_sounds_string($string) {
            $string = strtoupper($string);
            $sounds_string = '.';
            $words = split($this->pattern, $string);
            if (!empty($words)) {
                foreach ($words as $word) {
                    $sound = trim(soundex2($word));
//if (empty($sound)) { echo "***empty sound string***$string"; debug ($words, false);}
                    if ($sound != '') {
                        $sounds_string .= $sound .'.';
                    }
                }
            }
            
            return $sounds_string;
        }

        /* updates meta table records according to newly loaded thesaurus.  Also updates location thesaurus with entries from dilps that
        *  don't exist in the thesaurus
        */
        function _update_records_from_location_thesaurus() {
            
            $legacy_data_marker = 'manual entry';
            
            // step 0: delete existing legacy entries in the location table
            $deletesql = "delete from $this->location_table where src = 'dilps'";
            $delete = $this->db->Execute($deletesql);
            if (!$delete) {
               die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n[$deletesql]");
            }
            
            // update the meta table locationsounds to '..'; this will be later filled with the sounds from the location table
            $sql = "update $this->meta_table set locationsounds = '..'";
            $update = $this->db->Execute($sql);
            if (!$update) {
               die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n[$sql]");
            }            
             

            
            // step 1: update the matching names in the meta table with their sounds and the id from the location thesaurus table

            // loop through all the meta table records, and if there is only one match for the location in the location table, update the meta table
            $legacy_locations = array();
            $sql = "select id, location, collectionid, imageid from $this->meta_table where location != ''";
            $meta_records = $this->db->GetAll($sql);
            
            foreach ($meta_records as $meta_record) {
                $location = addslashes($meta_record['location']);
                //$sql = "select id, sounds from $this->location_table where location = '{$meta_record['location']}' and src='Getty TGN'";
                $sql = "select id from $this->location_table where location = '$location' and src='Getty TGN'";
                $location_records = $this->db->GetAll($sql);
                $record_count = count($location_records);
                
                if (($location_records !== false) && ($record_count == 1)) {
                    
                    $updatesql = "update $this->meta_table set locationid = {$location_records[0]['id']} where id = {$meta_record['id']}";
                        
                } else {
                
                   // if no matching record exists in location table OR if multiple matching records exist in location table
                   //    then add to the records that will be inserted as 'legacy data' into the locations table 
                   // only one record will be created, so only the first match will be used to create the src id for the location table
                   
                   // update the record's locationid to 0;
                   $updatesql = "update $this->meta_table set locationid = 0 where id = {$meta_record['id']}";
                    
                   if (!isset($legacy_locations[$meta_record['location']])) {
                        $legacy_locations[$meta_record['location']] = "{$meta_record['collectionid']}:{$meta_record['imageid']}";
                   }
                }
                
                $update = $this->db->Execute($updatesql);
                if (!$update) {
                   die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n[$updatesql]");
                } 
            }
            
            // step 2: insert 'legacy data' records
            foreach ($legacy_locations as $legacy_location=>$legacy_id) {
                
                $location_sound = $this->get_sounds_string($legacy_location);
                $location = addslashes($legacy_location);
                
                $insertsql = "insert into $this->location_table (src, source_id, location, loc_type, hierarchy, sounds) values ('dilps', '$legacy_id', '$location', '83002/inhabited place', '$legacy_data_marker', '$location_sound')";
                
                $insert = $this->db->Execute($insertsql);
                if (!$insert) {
                   die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n[$insertsql]" );
                }            
            }
            
            //step 3: update the meta table with the locationids of the 'legacy data' location ids
            $sql = "update $this->meta_table m, $this->location_table l set m.locationid = l.id where l.hierarchy='$legacy_data_marker' and m.locationid=0 and m.location = l.location";
            $update = $this->db->Execute($sql);
            if (!$update) {
               die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n[$sql]");
            }            
            
            // step 4: update the meta table with the sounds from the location table
            $sql = "update $this->meta_table m, $this->location_table l set m.locationsounds = l.sounds where m.locationid = l.id";
            $update = $this->db->Execute($sql);
            if (!$update) {
               die (__FILE__ . ':' . __LINE__ . ': ' . $this->db->ErrorMsg() . "\n[$sql]");
            }            
            
        }
        
/*        function _empty_location_record() 
        {
            return array('id'=>0, 'src'=>'', 'source_id'=>0, 'parent_src_id'=>0, 'location'=>'', 'loc_type'=>'', 'hierarchy'=>'', 'sounds'=>'');
        }*/
        
        
    }
    

    

    
    
    
// stub to load db from location thesaurus file:    
/*include_once( $config['includepath'].'adodb/adodb.inc.php' );

$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
    die ('no connection');
}

$thesaurus = new thesaurusDB($db, $db_prefix);
$thesaurus->loadDB_locations('/home/brian/projects/dilps/temp/tgn1.xml'); 
*/

// stub to query locations from db:    
/*include_once( $config['includepath'].'adodb/adodb.inc.php' );

$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
    die ('no connection');
}

$thesaurus = new thesaurusDB($db, $db_prefix);
var_export($thesaurus->query('locations', 'Siena')); 
*/

    
// to load names into db from thesaurus file:    
/*include_once( $config['includepath'].'adodb/adodb.inc.php' );

$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
    die ('no connection');
}

$thesaurus = new thesaurusDB($db, $db_prefix);
$thesaurus->loadDB('/home/brian/projects/dilps/test/pknd_0.1.xml'); 
*/



// to perform a query against the database:
/*
if (!empty($_SERVER['argv'][1])) {
    $searchterm = $_SERVER['argv'][1];
} else {
    //$searchterm = "Abd al-Latif al-Baghdadi, Muwaffak al-Din Abu Muhammad b. Yusuf";
    $searchterm = '';
}


$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
    die ('no connection');
}
$thesaurus = new thesaurusDB($db, $db_prefix);
$results = $thesaurus->query($searchterm); 
printr_r($results)
*/
?>
