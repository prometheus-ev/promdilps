<?php

require_once( $config['includepath'].'dilpsQuery.class.php' );

/*
queries are made up of pieces.  each piece is made up of one or more phrases.
e.g.:
(name like '%gog%') and (title like '%ven%' or title like '%zzz%')
is two pieces.  the second piece has two phrases.
*/

// "standard" fields mentioned in comments are fields that are always present in both simple and advanced queries, and that have a value
// "standard" html fields maintain their form in both simple and advanced queries. (e.g. query[collectionid])
// advanced query dynamic query fields have a form like query[querypiece][0][...]

function prepare_html_query($query) {

    $query['submitted_query'] = $query;  // save a copy of the clean, submitted html query - to use for creating the databse query    
       
    if ($query['querytype'] == 'advanced') {
        if ($query['fromquerytype'] == 'simple') {
            // when switching from simple to advanced, add non-empty, non-standard fields from the simple query to the advanced query html data structure
            addSimpleQueryPieces($query, false);
        }
        //
        // add new html fields for the advanced query, if required
        //
        if ($count = count($query['querypiece'])) {
            if (!empty($query['querypiece'][$count-1]['piece_connector'])) {
                // user wants another phrase: they've clicked on "and" or "or"
                append_query_piece($query, new_simple_qpiece('','','','','0','',''),$query['querypiece'][$count-1]['piece_connector']);
            }
        } else {
            // zero (non-standard) query pieces present, so add one
            append_query_piece($query, new_simple_qpiece('','','','','0','',''));
        }
        
        // add a new phrase to one of the query pieces if the user has clicked to add a new phrase
        foreach ($query['querypiece'] as $key=>$qp) {
            $count = count($qp['field']);
            if (!empty($qp['connector'][$count-1])) {
                $query['querypiece'][$key] = add_new_phrase($query['querypiece'][$key]);
            }
        }
    } 
    return $query;
}

// transforms submitted html query into an intermediate structure that includes all relevant fields
function html_to_db_query($query) {
	
    if ($query['querytype'] == 'simple') {
        if ($query['fromquerytype'] != 'advanced') {
            addSimpleQueryPieces($query, true);
        }
    } else if ($query['querytype'] == 'advanced') {
        if ($query['fromquerytype'] == 'simple') {
            addSimpleQueryPieces($query, true);
        } else {
            addStandardQueryPieces($query);
        }
    }
    return $query;
}


/* transforms query pieces into the data structure expected by the dilpsQuery class
*/
function transform_query($querypieces, $forRemote = false) 
{
    /*
    query = array(phrases, connectors) 
    phrases = array(atoms, connectors)
    connectors = array ({and | or})
    atoms = array(field, val, operator, not)
    */
    if (empty($querypieces)) {
        return array();
    }
    
    $atomfields = array('field', 'operator', 'val', 'not');
    $phrases = array();
    $qconnectors = array();
    $j = 0;
    foreach ($querypieces as $querypiece) {
        $atoms = array();
        $aconnectors = array();
        
        $i = 0;
        foreach ($querypiece['field'] as $qpfield) {
            $atom = array();
            foreach ($atomfields as $atomfield) {
                // don't include collection id for remote queries
                if ($forRemote && $atomfield == 'field' && $querypiece[$atomfield][$i] == 'collectionid') {
                    if (isset($atoms[$i])) { 
                        unset($atoms[$i]); 
                        unset($aconnectors[$i]); 
                    }
                    continue 2;                    
                }
            	$atom[$atomfield] = $querypiece[$atomfield][$i];
            }
            $atoms[$i] = $atom;
            $aconnectors[$i] = $querypiece['connector'][$i]; 
            $i++;
        }
        
        if (!empty($atoms)) {
            $phrases[$j] = array(
                'atoms'=>$atoms,
                'connectors'=>$aconnectors);
            if (isset($querypiece['piece_connector'])) {
                $qconnectors[$j] = $querypiece['piece_connector'];
            }
        }
        $j++;
    }
    
    // "unset" the last connector
    if ($count = count($qconnectors)) {
        $qconnectors[$count-1] = '';
    }
    
    $query = array(
        'phrases' => $phrases,
        'connectors' => $qconnectors);
    return $query;
}


// handle delete-phrase requests & cleans up the data structure
// modifies $query['querypiece']
function cleanQuery(&$query) {
    if (empty($query['querypiece'])) {
        return;
    }

    // delete on request
    if (!empty($query['delete_phrase'])) {
        list($qpiece, $index) = explode(':', $query['delete_phrase']);
        foreach ($query['querypiece'][$qpiece] as $key=>$item) {
            if (is_array($item) && isset($item[$index])) {
                unset($query['querypiece'][$qpiece][$key][$index]);
            }
        }
        
        // unset the connector of the preceeding item, if this was the last item
        $piece_content_count = count($query['querypiece'][$qpiece]['field']);
        if ($index == $piece_content_count) {
            if ($index == 0 && $qpiece > 0) {
                // this was the only phrase in this query piece, so unset the piece connector of the preceding piece
                $qpiece--;
                $query['querypiece'][$qpiece]['piece_connector'] = '';
            } else {
                if ($index > 0) {
                    // unset the connector that was connecting this phrase to the previous phrase in this query piece
                    $index--;
                    if (isset($query['querypiece'][$qpiece]['connector'][$index])) {
                        $query['querypiece'][$qpiece]['connector'][$index] = '';
                    }
                }
            }
        }
    }
    
    // reorder the query pieces (qp) to start from a 0 index and
    // get rid of phrases in the query with no value
    $i = 0;
    $newquerypieces = array();
    foreach ($query['querypiece'] as $key=>$qp) {
       $j = 0;
       $newquerypiece = array();
       
       // get the numerical keys of the query phrases
       $qpkeys = array_keys($qp['field']);
       $lastphrase = max (0, count($qpkeys) - 1);
       foreach ($qpkeys as $qpkey) {
            if (!empty($qp['val'][$qpkey])  || is_switching_type($query, $key, $qpkey)) {
                $queryphraseelements = array_keys($qp);    //elements: e.g. field, val, operator
                foreach ($queryphraseelements as $element) {
                    if (is_array($qp[$element])) {
                        if (isset($qp[$element][$qpkey])) {
                            $newquerypiece[$element][$j] = $qp[$element][$qpkey];
                        } else {
                            $newquerypiece[$element][$j] = '';
                        }
                    } else {
                        $newquerypiece[$element] = $qp[$element];
                    }
                }
                $j++;
            } else if ($qpkey == $lastphrase && $lastphrase > 0) {
                // this is the last phrase, it is empty, and there is a preceding phrase: "unset" the connector of the preceding phrase
                $newquerypiece['connector'][$lastphrase-1] = '';
            }
        }
        if (!empty($newquerypiece)) {
            $newquerypieces[$i] = $newquerypiece;
            $i++;
        }
    }

    foreach ($newquerypieces as $key=>$piece) {
        if (!isset($piece['connector'])) {
            $newquerypieces[$key]['connector'] = array('');
        }
    }
    
    $query['querypiece'] = $newquerypieces;
}

// adds all of the simple query pieces to the querypiece data structure
function addSimpleQueryPieces(&$query, $includeStandardFields) {
    if ($includeStandardFields) {
        addStandardQueryPieces($query);
    }
    
    $qpieces = array();
    if (!empty($query['name'])) {
        $qpieces[] = new_simple_qpiece('name', $query['name'], 'like', 'extended');
    }
    if (!empty($query['title'])) {
        $qpieces[] = new_simple_qpiece('title', $query['title'], 'like', 'normal');
    }
    if (!empty($query['year'])) {
        $qpieces[] = new_simple_qpiece('year', $query['year'], 'equals', 'like');
    }
    if (!empty($query['imageid'])) {
        $qpieces[] = new_simple_qpiece('imageid', $query['imageid'], 'equals', 'equals');
    }
    if ($count = count($qpieces)) {
        $qpieces[$count-1]['piece_connector'] = '';
        $qpieces[$count-1]['connector'][0] = '';
        $qpiece = combine_pieces($qpieces, '');
        append_query_piece($query, $qpiece, 'and');
    }

    if (!empty($query['all']) && trim($query['all']) !== '') {
	    $queryall_fields = array('year', 'name', 'title');    
	    $qpiece = query_all_piece($queryall_fields, $query['all']);
	    append_query_piece($query, $qpiece, 'and');
    }
}

function addStandardQueryPieces(&$query) {
    if ($query['collectionid'] != '-1') {
        $qpiece = new_simple_qpiece('collectionid', $query['collectionid'], 'equals', 'equals');
        append_query_piece($query, $qpiece, 'and');
    }
    if ($query['status'] != 'all') {
        $qpiece = new_simple_qpiece('status', $query['status'], 'equals', 'equals');
        append_query_piece($query, $qpiece, 'and');
    }
}

function query_all_piece($fields, $value) {
    $value = trim($value);
    $qpieces = array();
    $dq = new dilpsQuery();
    $metainfo = $dq->getColumnMetainfo();
    foreach ($fields as $field) {
        $qpieces[] = new_simple_qpiece($field, $value, 'like', $metainfo[$field]['operators'], '0', 'or');
    }
    $queryall = combine_pieces($qpieces, '');
    return $queryall;
} 	    

// appends given query piece to $query['querypiece']
function append_query_piece(&$query, $qpiece, $connector = '') {
    $count = count($query['querypiece']);
    $query['querypiece'][$count] = $qpiece;
    if ($count) {
        $query['querypiece'][$count-1]['piece_connector'] = $connector;
    }
}

// returns the query piece with a new empty phrase added to it.  
function add_new_phrase($query_piece) {
    $array_elements = array('val', 'field', 'connector', 'operator', 'not', 'operator_list');
    $index = count($query_piece['field']);
    foreach ($array_elements as $element) {
        $query_piece[$element][$index] = '';
    }
    return $query_piece;
}

// returns a new query piece with one phrase set to the given values
function new_simple_qpiece($field, $val, $operator, $operator_list, $not = '0', $connector = 'and', $piece_connector = 'and') {
    $query_piece = array(   
        'val'=>array($val), 
        'field'=>array($field), 
        'connector'=>array($connector), 
        'operator'=>array($operator), 
        'not'=>array($not), 
        'operator_list'=>array($operator_list),
        'piece_connector'=>$piece_connector);
    return $query_piece;        
}

// accepts array of simple query pieces
// returns a query piece with all individual query pieces combined into one
function combine_pieces($qpieces, $pieceConnector = 'and') {
    $count = count($qpieces);
    if (!$count) {
        return $qpieces;
    }
    if ($count == 1) {
        $compoundPiece = current($qpieces);
    } else {
        $compoundPiece = new_simple_qpiece('', '', '', '', '', '', '');
        $i = 0;
        foreach ($qpieces as $phrase) {
            foreach ($phrase as $key=>$val) {
                if ($key != 'piece_connector') {
                    $compoundPiece[$key][$i] = $val[0];                
                }
            }
            $i++;
        }
    }
    $compoundPiece['piece_connector'] = $pieceConnector;
    return $compoundPiece;
}

/* returns true if query[querypiece][$key1][val][$key2] has been signaled as transforming it's type
*/
function is_switching_type($query, $key1, $key2) {
    if (empty($query['transforming_field'])) {
        return false;
    }

    return ("$key1:$key2" == $query['transforming_field']);
}           


?>