<?php

	/**
	 *	recursive permission reset
	 *
	 *	resets all file and directory permissions for the dilps system
	 *
	 *	@access		public
	 *	@param 		string $dilpsdir
	 *	@return		bool
	 *
	 */

	function reset_perms_recursive($dilpsdir)
	{
		if(is_dir($dilpsdir))
		{
			$dir_handle=opendir($dilpsdir);
		}
		else
		{
			echo ("reset_recursive error: DILPS directory ($dir) cannot be opened\n<br>\n");
			return false;
		}

		while($file = readdir($dir_handle))
		{
			if($file != "." && $file != "..")
			{


				if(!is_dir($dilpsdir."/".$file))
				{
					$ret = @chmod ($dilpsdir."/".$file, 0644);
					if (!$ret)
					{
						echo ("Error resetting permissions of ".$dilpsdir."/".$file."\n<br>\n");
						return false;
					}
				}
				else
				{
					if ($file == 'template_c' or $file == 'upload' or $file == 'cache')
					{
						// echo ("File: ".$file." \n");

						$ret = @chmod($dilpsdir."/".$file,0777);

						if (!$ret)
						{
							return false;
						}
						else
						{
							reset_perms_recursive($dilpsdir."/".$file);
						}
					}
					else
					{
						$ret = @chmod($dilpsdir."/".$file,0755);

						if (!$ret)
						{
							return false;
						}
						else
						{
							reset_perms_recursive($dilpsdir."/".$file);
						}
					}
				}
			}
		}

		closedir($dir_handle);
		return true;
	}

	
	$success = reset_perms_recursive('../');

	if ($success)
	{
		echo ("DILPS file permissions successfully reset\n");
	}
	else
	{
		echo ("(An) error(s) occured - see above for details\n");
	}


?>
