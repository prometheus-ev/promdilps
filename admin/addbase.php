<?php

/*

   +----------------------------------------------------------------------+

   | DILPS - Distributed Image Library System                             |

   +----------------------------------------------------------------------+

   | Copyright (c) 2002-2004 Juergen Enge                                 |

   | juergen@info-age.net                                                 |

   | http://www.dilps.net                                                 |

   |                                                                      |

   | This admin/install tool is based upon                                |

   | Mantis - a php based bugtracking system                              |

   | Copyright (C) 2000 - 2002  Kenzaburo Ito                             |

   |    kenito@300baud.org                                                |

   | Copyright (C) 2002 - 2004  Mantis Team                               |

   |   mantisbt-dev@lists.sourceforge.net                                 |

   |                                                                      |

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

	// ini_set('display_errors',1);
	// error_reporting (E_ALL);

	$g_absolute_path	= dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR;

	define( 'BAD', 0 );

	define( 'GOOD', 1 );

	$g_failed = false;



	function print_test_result( $p_result, $p_hard_fail=true, $p_message='' ) {

		global $g_failed;

		echo '<td ';

		if ( BAD == $p_result ) {

			if ( $p_hard_fail ) {

				$g_failed = true;

				echo 'bgcolor="red">BAD';

			} else {

				echo 'bgcolor="pink">BAD';

			}

		}



		if ( GOOD == $p_result ) {

			echo 'bgcolor="green">GOOD';

		}

		if ( '' !== $p_message ) {

			echo '<br />' . $p_message;

		}

		echo '</td>';

	}



?>



<html>

<head>

<title> DILPS Installer  </title>

<link rel="stylesheet" type="text/css" href="admin.css" />

</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">

	<tr class="top-bar">

		<td class="links">

			[ <a href="index.php">Back to Administration</a> ]

		</td>

		<td class="title">

		Add image base to collection

		</td>

	</tr>

</table>

<br /><br />

<table width="100%" bgcolor="#222222" border="0" cellpadding="10" cellspacing="1">

<tr>

	<td bgcolor="#e8e8e8" colspan="2">

		<span class="title">Checking Installation...</span>

	</td>

</tr>



<!-- Check Php Version -->

<tr>

	<td bgcolor="#ffffff">

		Checking  PHP Version (Your version is <?php echo phpversion(); ?>)

	</td>

	<?php

		if (phpversion() == '4.0.6') {

			print_test_result( GOOD );

		} else {

			if ( function_exists ( 'version_compare' ) ) {

				if ( version_compare ( phpversion() , '4.0.6', '>=' ) ) {

					print_test_result( GOOD );

				} else {

					print_test_result( BAD );

				}

			} else {

			 	print_test_result( BAD );

			}

		}

	?>

</tr>



<tr>

	<td bgcolor="#ffffff">

		Checking config file

	</td>

	<?php

		if (file_exists( $g_absolute_path . DIRECTORY_SEPARATOR . 'config.inc.php' ))	 {

			print_test_result( GOOD );

			require_once($g_absolute_path . DIRECTORY_SEPARATOR . 'config.inc.php');

			$f_includepath = $config['includepath'];

		} else {

			print_test_result( BAD );

		}

	?>

</tr>



<!-- Setting config variables -->

<tr>

	<td bgcolor="#ffffff">

		AdoDB

	</td>

	<?php

	  if (!empty($f_includepath) && file_exists($f_includepath.DIRECTORY_SEPARATOR.'adodb/adodb.inc.php')) {

		if (!empty($f_includepath) && file_exists($f_includepath.DIRECTORY_SEPARATOR.'adodb/adodb.inc.php'))

	    require_once($f_includepath.DIRECTORY_SEPARATOR.'adodb/adodb.inc.php');

	    print_test_result( GOOD );

	  } else {

		print_test_result( BAD );

	  }

	?>

</tr>

<tr>

	<td bgcolor="#ffffff">

		Attempting to connect to database as user

	</td>

	<?php

		$g_db = ADONewConnection('mysql');

		$t_result = @$g_db->Connect($db_host, $db_user, $db_pwd, $db_db);



		if ( $t_result == true ) {

			print_test_result( GOOD );



			$sql = "SELECT * FROM {$db_prefix}config";

            $result = $g_db->Execute( $sql );

            while( !$result->EOF ) {

              $name = $result->fields["name"];

              $config[$name] = $result->fields["val"];

              $result->MoveNext();

           }



		} else {

			print_test_result( BAD);

		}

	?>

</tr>

</table>

<br>

<?php

$status = 0;



 if (!empty($_POST['path']))

   $status = 1;

?>



<?php if ($status == 0) { ?>

<form method='POST'>

<table width="100%" bgcolor="#222222" border="0" cellpadding="10" cellspacing="1">

<tr>

	<td bgcolor="#e8e8e8" colspan="2">

		<span class="title">Add image base</span>

	</td>

</tr>

<tr>

	<td bgcolor="#ffffff">

		Collection

	</td>

	<td bgcolor="#ffffff">

    <select name="collection">

	<?php

         $sql = "select collectionid, name from {$db_prefix}collection;";

         $result = $g_db->Execute($sql);



         while (!$result->EOF) {

             echo '<option value="'.$result->fields[0].'">'.$result->fields[1].'</option>';



         	$result->MoveNext();  //  Moves to the next row

         }

    ?>

    </select>

	</td>

</tr>



<tr>

	<td bgcolor="#ffffff">

		Host

	</td>

	<td bgcolor="#ffffff">

	  <input type="text" name="host" size="80" value="local">

	</td>

</tr>



<tr>

	<td bgcolor="#ffffff">

		Path

	</td>

	<td bgcolor="#ffffff">

	  <input type="text" name="path" size="80" value="/path/to/store/your/collection">

	</td>

</tr>



<tr>

	<td bgcolor="#ffffff">

		Add image base

	</td>

	<td bgcolor="#ffffff">

	  <input name="go" type="submit" value="Go"></input>

	</td>

</tr>



</table>

</form>

<?php } ?>



<?php if ($status == 1) { ?>

<table width="100%" bgcolor="#222222" border="0" cellpadding="10" cellspacing="1">

<tr>

	<td bgcolor="#e8e8e8" colspan="2">

		<span class="title">Change database</span>

	</td>

</tr>

<tr>

	<td bgcolor="#ffffff">

	 	Create directories (if necessary)

	</td>

	<?php

	  $result = true;

      $failure = false;
      
      $path = $_POST['path'];


   	  if ($result &&  ($_POST['host']=='local')) {        
        
        if ($path{0} != '/' && $path{0} != DIRECTORY_SEPARATOR && $path{1} != ':')
        {
        	$sql = "SELECT val FROM {$db_prefix}config WHERE name='dilpsdir'";

            $add_to_path = $g_db->GetOne( $sql );
            
            $path = $add_to_path.'images'.DIRECTORY_SEPARATOR.$path;
        }
        
		mkdir($path);

        mkdir($path.DIRECTORY_SEPARATOR.'cache');

        mkdir($path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'120x90');

        mkdir($path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'1600x1200');

        mkdir($path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'1280x1024');

        mkdir($path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'1024x768');

        mkdir($path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'640x480');

        mkdir($path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'800x600');


   	    $failure = !is_writable($path);

   	    if ($failure)

          print_test_result(BAD);

        else

          print_test_result(GOOD);

   	  }

   ?>



</tr>



<tr>

	<td bgcolor="#ffffff">

	 	Write settings to database

	</td>

	<?php

	   if (!$failure) {

	   $cid = -1;



	   $g_db->StartTrans();

	   $cid = $_POST['collection'];



	   $values = 	$g_db->qstr($cid). ",".$g_db->qstr($path). ",".$g_db->qstr('unused').","
	   				.$g_db->qstr($_POST['host']);

	   $sql = "insert into {$db_prefix}img_base (collectionid, base, getbild, host) values (";

	   $sql .= ($values);

	   $sql .= ');';

       $result = $g_db->Execute( $sql );



       $g_db->CommitTrans();

	   }



       if ($result && !$failure)

         print_test_result(GOOD);

       else

       print_test_result(BAD);

	?>

</tr>

</table>

<?php

?>



<p align="center">

[ <a href="index.php">Back to Administration</a> ]

</p>



<?php } ?>



</body>

</html>