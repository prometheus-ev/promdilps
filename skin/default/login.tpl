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

BEGIN login.tpl

--------------------------------------------- -->

{if $config.utf8 eq 'true'}

	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/login.conf.utf8"}

{else}

	{config_load file="`$config.skinBase``$config.skin`/`$config.language`/login.conf"}

{/if}



<html>

<head>

  <meta name="robots" content="index,follow">

  <meta http-equiv="pragma" content="no-cache">

  <meta http-equiv="expires" content="0">

  <meta http-equiv="cache-control" content="no-cache">

  <meta name="keywords" content="Bilddatenbanksystem, Bilddatenbank, Diathek, digitalisiert">

  {if $config.utf8 eq 'true'}

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  {else}

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

  {/if}

  <meta http-equiv="Content-Script-Type" content="text/javascript">

  <meta http-equiv="Content-Style-Type" content="text/css">

  <meta name="author" content="jrgen enge, thorsten wbbena">

  <meta name="date" content="2003-01-23">

  <link rel="shortcut icon" href="favicon.ico">

<style type="text/css">

{include file="`$config.skin`/dilps.css"}

</style>

  <title>. : {#title#|escape:"htmlall"} : .</title>

</head>

<body class="logon">

<table style="margin: 0 0 0 0; padding: 0 0 0 0;" width="100%" border="0" height="100%">

<tr>

<td valign="top">

<table class="logonheader" width="100%" border="0">

<tr>

<td>

   <img src="login.jpg">

</td>

</tr>

</table>

</td>

</tr>

<tr>

<td valign="top">

  <form name="Login" action="{$SCRIPT_NAME}" method="POST">

  <center>

  <p />

  <table>

  <tr>

    <td>

    &nbsp; <br>

    &nbsp; <br>

    </td>

  </tr>

  <tr><td class="heading" colspan="2">{#login#|escape:"htmlall"}<br />&nbsp;</td></tr>

  <!--
  <tr>

     <td class="field_name">{#logintype#|escape:"htmlall"}</td>

	 <td>
	 	<select name="auth[type]" size="1">
	 		<option value='{$config.authdomain|escape:"htmlall"}' {if $config.authdefault neq 'static'}selected='selected'{/if}>{#intern#}</option>
	 		<option value='static' {if $config.authdefault eq 'static'}selected='selected'{/if}>{#extern#}</option>
	 	</select>
	 	
	 </td>

  </tr>
  -->

  <tr>

     <td class="field_name">{#userid#|escape:"htmlall"}</td>

	  <td><input type="text" name="auth[uid]" size="20"></td>

  </tr>

  <tr>

     <td class="field_name">{#password#|escape:"htmlall"}</td>

	  <td><input type="password" name="auth[pwd]" size="20"></td>

  </tr>

  <tr>

     <td>&nbsp</td>

	  <td>
	  	<input type="hidden" name="auth[domain]" value='{$config.authdomain|escape:"htmlall"}'>

	  	<input type="submit" name="Login" value="Anmelden">

	  </td>

  </tr>

  <tr>

    <td>

    &nbsp; <br>

    &nbsp; <br>

    </td>

  </tr>

  </table>

  </form>

</td>

</tr>

<tr>

<td valign="bottom">

<table class="logonheader" width="100%" border="0" align="center">

  <tr>

    <td width="76%">

    &nbsp; <br>

    </td>

  </tr>



  <tr>

    <td width="76%" align="center">

        <a class="login" href="http://www.hfg-karlsruhe.de/" target="_blank">Staatliche Hochschule f&uuml;r Gestaltung Karlsruhe</a>

    </td>

  </tr>

  <tr>

    <td width="76%" align="center">

        <a class="login" href="http://www.kunst.uni-frankfurt.de/" target="_blank">Kunstgeschichtliches

        Institut der J.W.Goethe-Universit&auml;t, Frankfurt/M.</a>

    </td>

  </tr>

  <tr>

    <td width="76%">

    &nbsp; <br>

    </td>

  </tr>

</table>

</td>

</tr>

</table>

</body>

</html>

<!-- --------------------------------------------

END login.tpl

--------------------------------------------- -->
