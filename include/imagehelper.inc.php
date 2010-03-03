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
	 * image helper
	 * -------------------------------------------------------------
	 * File:     		imagehelper.inc.php
	 * Purpose:  helper functions for the image
	 * 				processor
	 * -------------------------------------------------------------
	 */
	 

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
					if(is_dir($dir.'/'.$file))
					{
						$remove[] = $dir.'/'.$file;
						read_recursive($dir.'/'.$file, $files,$remove);
					}
					else
					{
						$files[] = $dir.'/'.$file;
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
	
?>