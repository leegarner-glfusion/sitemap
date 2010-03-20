<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | functions.inc                                                            |
// |                                                                          |
// | glFusion Plugin API functions and support functions                      |
// +--------------------------------------------------------------------------+
// | $Id::                                                                   $|
// +--------------------------------------------------------------------------+
// | Based on the Data Proxy Plugin for Geeklog CMS                           |
// | Copyright (C) 2007-2008 by the following authors:                        |
// |                                                                          |
// | Authors: mystral-kk        - geeklog AT mystral-kk DOT net               |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

// this file can't be used on its own
if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

/**
* Language file Include
*/
$langfile = $_CONF['path'].'plugins/sitemap/language/'.$_CONF['language'].'.php';
clearstatcache();
if (file_exists($langfile)) {
    include_once $langfile;
} else {
    include_once $_CONF['path'].'plugins/sitemap/language/english.php';
}

/**
* Include sitemap info file
*/
require_once $_CONF['path'].'plugins/sitemap/sitemap.php';

// +---------------------------------------------------------------------------+
// | glFusion Plugin API Implementations                                       |
// +---------------------------------------------------------------------------+

/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*
* Universal Plugin:  Uncomment and fill in variables;
*
* @return    array   Associative array of menu items to be added to menu
*
*/

function plugin_getmenuitems_sitemap() {
	global $_CONF, $_SMAP_CONF;

    if ( COM_isAnonUser() && ( $_SMAP_CONF['anon_access'] === false || $_CONF['loginrequired'] == 1) ) {
        return;
    }

    $menuitems = array();
	$menuitems[SITEMAP_str('menu_label')] = $_CONF['site_url'] . '/sitemap/index.php';
    return $menuitems;
}

/**
* Check to see if this Plugin uses comments
*
* @return   boolean true or false
*/

function plugin_commentsupport_sitemap() {
    return false;
}

/**
* This will put an option for the plugin in the command and control block on
* moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*/
function plugin_cclabel_sitemap() {
    global $_CONF;

	if (SEC_hasRights('sitemap.admin')) {
    	$pi_name   = SITEMAP_str('menu_label');
    	$admin_url = $_CONF['site_admin_url'] . '/plugins/sitemap/index.php';
    	$icon_url  = $_CONF['site_url'] . '/sitemap/images/sitemap.png';
        return array($pi_name, $admin_url, $icon_url);
    }
}

/**
* Returns the administrative option for this plugin
*
* @return   array   Array containing (plugin name, plugin admin url, # of items
*                   in plugin or '')
*/
function plugin_getadminoption_sitemap() {
	global $_CONF, $_TABLES;

	if (SEC_hasRights('sitemap.admin')) {
		$pi_name   = SITEMAP_str('menu_label');
		$admin_url = $_CONF['site_admin_url'] . '/plugins/sitemap/index.php';
		$num_items = '';
		return array($pi_name, $admin_url, $num_items);
	}
}

/**
* Returns the version for this plugin
*
* @return string VersionNo
*/
function plugin_chkVersion_sitemap() {
	global $_SMAP_CONF;

	return $_SMAP_CONF['pi_version'];
}

/**
* Returns the user option for this plugin
*
* @return   array   Array containing (plugin name, plugin user url, 0)
*/
function plugin_getuseroption_sitemap() {
}

/**
* Upgrade the plugin
*
* Universal Plugin:  modify/uncomment use it
*
* @return   boolean true (= success)
*/
function plugin_upgrade_sitemap() {
	global $_CONF, $_TABLES, $_SMAP_CONF, $VALUES_1_0_TO_1_0_1;

	$result     = DB_query("SELECT pi_version FROM {$_TABLES['plugins']} WHERE (pi_name = 'sitemap')");
	$A          = DB_fetchArray($result, false);
	$pi_version = $A['pi_version'];

	switch ($pi_version) {
		case '1.0':
			require_once $_CONF['path'].'plugins/sitemap/sql/mysql_update-1.0_1.0.1.php';
			foreach ($VALUES_100_TO_101 as $table => $sqls) {
				COM_errorLog("Inserting default data into $table table", 1);
				foreach ($sqls as $sql) {
					DB_query($sql, 1);
				}
			}
			// fall through on purpose...
		case '1.0.1':
		case '1.0.2':
		case '1.0.3':
		case '1.1.0':
		case '1.1.1':
		case '1.1.2':
		case '1.1.3':
			require_once $_CONF['path'].'plugins/sitemap/sql/mysql_update-1.0.1_1.1.4.php';
			COM_errorLog("Inserting default data into table", 1);
			foreach ($DATA_101_TO_114 as $sql) {
				DB_query($sql, 1);
			}
        case '1.1.4' :
        case '1.1.5' :
            break;
		default:
			COM_errorLog('Sitemap: upgrade procedure for version ' . $pi_version . ' is not implemented in plugin_upgrade_sitemap().');
			break;
	}

	// Update plugin info

	$result = DB_query("UPDATE {$_TABLES['plugins']} SET pi_version = \"{$_SMAP_CONF['pi_version']}\" WHERE (pi_name = 'sitemap')");
	COM_errorLog(ucfirst("sitemap") . " plugin was successfully updated to version {$_SMAP_CONF['pi_version']}.");

	return true;
}


/**
* Loads vars from DB into $_SMAP_CONF[]
*/
function SITEMAP_loadConfig() {
	global $_TABLES, $_SMAP_CONF;

	$sql = "SELECT * FROM {$_TABLES['smap_config']}";
	$result = DB_query($sql);
	if (DB_error()) {
		COM_errorLog('SITEMAP_loadConfig: cannot load config.');
		exit;
	}

	while (($A = DB_fetchArray($result)) !== false) {
		list($name, $value) = $A;
		if ($value == 'true') {
			$value = true;
		} else if ($value == 'false') {
			$value = false;
		}

		if ($name == 'date_format') {
			$value = substr($value, 1, -1);
		} else if (substr($name, 0, 6) == 'order_') {
			$value = (int) $value;
		}

		$_SMAP_CONF[$name] = $value;
	}
}

/**
* Saves vars into DB from $_SMAP_CONF[]
*/
function SITEMAP_saveConfig() {
	global $_TABLES, $_SMAP_CONF;

	foreach ($_SMAP_CONF as $name => $value) {
		if ($value === true) {
			$value = 'true';
		} else if ($value === false) {
			$value = 'false';
		}

		// To prevent MySQL from trimming trailing spaces
		if ($name == 'date_format') {
			$value = '[' . $value . ']';
		}

		$name  = DB_escapeString($name);
		$value = DB_escapeString($value);

		$sql = "UPDATE {$_TABLES['smap_config']} "
			 . "SET value = '{$value}' "
			 . "WHERE (name = '{$name}')";
		$result = DB_query($sql);
		if (DB_error()) {
			COM_errorLog('SITEMAP_saveConfig: cannot save config.');
		}
	}
}


/**
* Escapes a string for HTML output
*/
function SITEMAP_escape($str) {
	$str = str_replace(
		array('&lt;', '&gt;', '&amp;', '&quot;', '&#039;'),
		array(   '<',    '>',     '&',      '"',      "'"),
		$str
	);
	return htmlspecialchars($str, ENT_QUOTES, COM_getEncodingt());
}

function SITEMAP_str($index, $noesc = false) {
	global $LANG_SMAP;

	if (isset($LANG_SMAP[$index])) {
		if ($noesc) {
			return $LANG_SMAP[$index];
		} else {
			return SITEMAP_escape($LANG_SMAP[$index]);
		}
	} else {
		COM_errorLog('SITEMAP_str: undefined index "' . $index . '".');
		return '(undefined)';
	}
}

/**
* Strips slashes if magic_quotes_gpc is on
*/
function SITEMAP_stripslashes($var) {
	if (get_magic_quotes_gpc()) {
		if (is_array($var)) {
			return array_map('SITEMAP_stripslashes', $var);
		} else {
			return stripslashes($var);
		}
	} else {
		return $var;
	}
}

/**
* Converts a timestamp into ISO8601 format
*/
function SITEMAP_convertDate($timestamp) {
	global $_SMAP_CONF;

	$retval = date('Y-m-d', $timestamp) . 'T'
			. date('H:i:s', $timestamp) . $_SMAP_CONF['time_zone'];
	return $retval;
}

/**
* Writes a Google Sitemap
*/
function SITEMAP_write($filename, $sitemap) {
	global $_CONF, $_SMAP_CONF;

	$retval = false;

	/**
	* for Google Mobile Sitemap
	*/
	if (preg_match("/mobile/i", $filename)) {
		$sitemap = str_replace(
			'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
			'<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">',
			$sitemap
		);
		$sitemap = str_replace(
			'</url>',
			'  <mobile:mobile/>' . "\n" . '  </url>',
			$sitemap
		);
	}

	$path = $_CONF['path_html'] . basename($filename);
	clearstatcache();
	if (!file_exists($path)) {
		$fh = @fopen($path, 'wb');
		if ($fh === false) {
			COM_errorLog('Sitemap: cannot create Google sitemap file.  Please create the file "' . $path . '" manually.');
			return false;
		}
	} else {
		$fh = @fopen($path, 'r+b');
		if ($fh === false) {
			COM_errorLog('Sitemap: Google sitemap file is NOT writable: ' . $path);
			return false;
		}
	}

	if (flock($fh, LOCK_EX) === true) {
		ftruncate($fh, 0);
		rewind($fh);
		$result = @fwrite($fh, $sitemap);
		if (($result === false) OR ($result != strlen($sitemap))) {
			COM_errorLog('Sitemap: cannot write into Google sitemap file fully: ' . $path);
		}

		@flock($fh, LOCK_UN);
		$retval = true;
	} else {
		COM_errorLog('Sitemap: cannot write-lock Google sitemap file: ' . $path);
	}

	@fclose($fh);

	return $retval;
}

/**
* Creates a Google Sitemap
*/
function SITEMAP_createGoogleSitemap() {
	global $_CONF, $_SMAP_CONF;

	$retval = false;

	// Loads config
	SITEMAP_loadConfig();

	// Header
	$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . LB
			 . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . LB;

	// Collects items
	$dataproxy =& new Dataproxy(1);
	$drivers   = $dataproxy->getAllDriverNames();

	foreach ($drivers as $driver) {
		if (($driver == 'trackback') OR ($driver == 'comments')
		 OR ($_SMAP_CONF['gsmap_' . $driver] === false)) {
			continue;
		}

		$items = $dataproxy->$driver->getAllItems(true);
		if (count($items) == 0) {
			continue;
		}

		foreach ($items as $item) {
			if ($driver == 'staticpages') {
				// Skip formmail
				if ($item['id'] == 'formmail') {
					continue;
				}
				$temp     = $dataproxy->$driver->getItemById($item['id']);
				$raw  = $temp['raw_data'];
				if ( $raw['sp_centerblock'] == 1 ) {
				    continue;
				}
				if ( $raw['sp_search'] != 1 ) {
				    continue;
				}
			}

			$sitemap .= '  <url>' . LB
					 .  '    <loc>' . $item['uri'] . '</loc>' . LB;

			if ($item['date'] !== false) {
				$sitemap .= '    <lastmod>' . SITEMAP_convertDate( $item['date'] ) . '</lastmod>' . LB;
			} else {
				$sitemap .= '    <lastmod>' . SITEMAP_convertDate(time()) . '</lastmod>' . LB;
			}

			$sitemap .= '    <changefreq>' . $_SMAP_CONF['freq_' . $driver]
					 . '</changefreq>' . LB;
			$priority = (float) $_SMAP_CONF['priority_' . $driver];
			if (($priority != 0.5) AND ($priority >= 0.0) AND ($priority <= 1.0)) {
				$sitemap .= '    <priority>' . $priority . '</priority>' . LB;
			}

			$sitemap .= '  </url>' . LB;
		}
	}

	// Footer
	$sitemap .= '</urlset>' . LB;

	// Writes the Google Sitemap
	foreach (explode(';', $_SMAP_CONF['google_sitemap_name']) as $filename) {
		$retval = ($retval OR !SITEMAP_write($filename, $sitemap));
	}

	return !$retval;
}

/**
* callback function for the pseudo cron job
*/
function plugin_runScheduledTask_sitemap() {
	SITEMAP_createGoogleSitemap();
}
?>