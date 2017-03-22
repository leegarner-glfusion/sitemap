<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | functions.inc                                                            |
// |                                                                          |
// | glFusion Plugin API functions and support functions                      |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009-2015 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Site Map Plugin                                             |
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
$langfile = dirname(__FILE__) . '/language/'.$_CONF['language'].'.php';
clearstatcache();
if (file_exists($langfile)) {
    include_once $langfile;
} else {
    include_once dirname(__FILE__) . '/language/english.php';
}

global $_SMAP_MAPS;
$_SMAP_MAPS = array();

/** Import the configuration class, if needed */
global $_SMAP_CONF;
require_once $_CONF['path_system'] . 'classes/config.class.php';
$config = config::get_instance();
$_SMAP_CONF = $config->get_config('sitemap');
require_once dirname(__FILE__) . '/sitemap.php';

/** Import static config values */
if (file_exists(dirname(__FILE__) . '/config.php')) {
    /** Import local configuration overrides, if any */
    include_once dirname(__FILE__) . '/config.php';
}

function USES_sitemap_class_config()
{   require_once dirname(__FILE__) . '/classes/smapConfig.class.php'; }

function USES_sitemap_class_base()
{   require_once dirname(__FILE__) . '/classes/sitemap_base.class.php'; }


// +---------------------------------------------------------------------------+
// | glFusion Plugin API Implementations                                       |
// +---------------------------------------------------------------------------+

/**
*   Returns the items for this plugin that should appear on the main menu
*
*   @return    array   Array of text=>url values.
*/
function plugin_getmenuitems_sitemap()
{
    global $_CONF, $_SMAP_CONF;

    if (SMAP_canView()) {
        return array(
            SITEMAP_str('menu_label') => $_CONF['site_url'] . '/sitemap/index.php',
        );
    } else {
        return array();
    }
}


/**
*   Check to see if this Plugin uses comments. It doesn't.
*
*   @return   boolean true or false
*/
function plugin_commentsupport_sitemap()
{
    return false;
}


/**
*   This will put an option for the plugin in the command and control block on
*   moderation.php
*
*   @return array   Array of (plugin name, admin url, url of plugin icon)
*/
function plugin_cclabel_sitemap() {
    global $_CONF;

    if (SEC_hasRights('sitemap.admin')) {
        return array(
            SITEMAP_str('menu_label'),
            $_CONF['site_admin_url'] . '/plugins/sitemap/index.php',
            $_CONF['site_url'] . '/sitemap/images/sitemap.png',
        );
    } else {
        return array();
    }
}


/**
*   Returns the administrative menu option for this plugin
*
*   @return array   Array of (plugin name, plugin admin url, # of items
*                   in plugin or '')
*/
function plugin_getadminoption_sitemap()
{
    global $_CONF, $_TABLES;

    if (SEC_hasRights('sitemap.admin')) {
        return array(
            SITEMAP_str('menu_label'),
            $_CONF['site_admin_url'] . '/plugins/sitemap/index.php',
            $num_items = '',
        );
    } else {
        return array();
    }
}


/**
*   Returns the current code version for this plugin
*
*   @return string VersionNo
*/
function plugin_chkVersion_sitemap()
{
    global $_SMAP_CONF;
    return $_SMAP_CONF['pi_version'];
}


/**
*   Returns the user option for this plugin. There is none.
*
*   @return   array   Array containing (plugin name, plugin user url, 0)
*/
function plugin_getuseroption_sitemap()
{
    return false;
}


/**
*   Upgrade the plugin
*
*   @return   boolean true (= success)
*/
function plugin_upgrade_sitemap()
{
    require_once dirname(__FILE__) . '/upgrade.inc.php';
    return sitemap_do_upgrade();
}


/**
 *  Get additional Javascript into the site header.
 *
 *  @return array   List of paths to Javascript files
 */
function plugin_getheaderjs_sitemap()
{
    $path = dirname(__FILE__) . '/js';
    $files = array(
        $path . '/toggleEnabled.js',
    );
    return $files;
}


/**
*   Get the URL to the configuration help file
*
*   @param  string  $doclang    Document language
*   @return string              URL to document
*/
function plugin_getconfigelementhelp_sitemap($option, $doclang='english')
{
    global $_CONF, $_SMAP_CONF;

    $doc_path = $_CONF['path_html'] . $_SMAP_CONF['pi_name'] .
                "/docs/$doclang/config.html";
    if (!is_file($doc_path . $file)) {
        $doclang = 'english';
    }
    $doc_url = $_CONF['site_url'] . '/' . $_SMAP_CONF['pi_name'] .
        '/docs/' . $doclang . '/config.html';

    if (!empty($option)) {
        $doc_url .= '#desc_' . $option;
    }
    return array($doc_url, 0);
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
*   Converts a timestamp into ISO8601 format
*
*   @param  integer $timestamp  Unix timestamp value
*   @return string      Formatted timestamp
*/
function SITEMAP_convertDate($timestamp)
{
    global $_CONF;

    static $D = NULL;   // save some effort during repeat calls

    if ($D === NULL) {
        $D = new Date($timestamp, $_CONF['timezone']);
    } else {
        $D->setTimeStamp($timestamp);
    }
    return $D->format('c', true);
}


/**
*   Write out the XML Sitemap
*
*   @param  string  $filename   Filename to write
*   @param  string  $sitemap    Sitemap content
*   @return boolean     True on success, False on failure
*/
function SITEMAP_write($filename, $sitemap)
{
    global $_CONF, $_SMAP_CONF;

    $retval = false;
    $filename = trim($filename);
    if (empty($filename)) return false;

    /**
    *   Alterations for Google Mobile Sitemap
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
        } else {
            $retval = true;
        }
        @flock($fh, LOCK_UN);
    } else {
        COM_errorLog('Sitemap: cannot write-lock Google sitemap file: ' . $path);
    }
    @fclose($fh);
    return $retval;
}


/**
*   Creates the Google Sitemap files.
*   No return value.
*   @uses   SITEMAP_write()
*/
function SITEMAP_createGoogleSitemap()
{
    global $_CONF, $_SMAP_CONF, $_SMAP_MAPS;

    USES_sitemap_class_base();
    USES_sitemap_class_config();

    smapConfig::loadConfigs();

    $retval = false;
    $T = new Template(dirname(__FILE__) . '/templates');
    $T->set_file('smap', 'smap_google.thtml');

    $T->set_block('smap', 'smap_item', 'item');

    foreach ($_SMAP_MAPS as $pi_name=>$config) {
        // Skip disabled sitemaps
        if (!$config['xml_enabled']) continue;

        // Load the driver for the sitemap, if available
        $classfile = smapConfig::getClassPath($pi_name);
        if (is_file($classfile)) {
            include_once $classfile;
            $cls = 'sitemap_' . $pi_name;
            if (class_exists($cls)) {
                $C = new $cls();
                $C->setXML();   // Mark as an XML sitemap
                $items = $C->getItems();
            }
        }

        // No driver found, or no items returned
        if (empty($items)) continue;

        foreach ($items as $item) {
            if ($item['date'] !== false) {
                $date = SITEMAP_convertDate($item['date']);
            } else {
                $date = SITEMAP_convertDate(time());
            }
            $T->set_var(array(
                'url'   => $item['uri'],
                'date'  => $date,
                'freq'  => $config['freq'],
                'priority'  => $config['priority'] != 0.5 ?
                        sprintf('%1.1f', (float)$config['priority']) : '',
            ) );
            $T->parse('item', 'smap_item', true);
        }
    }
    $T->parse('output', 'smap');
    $sitemap = $T->finish($T->get_var('output'));

    // Writes the Google Sitemap
    foreach (explode(';', $_SMAP_CONF['xml_filenames']) as $filename) {
        $retval = ($retval || !SITEMAP_write($filename, $sitemap));
    }
    return !$retval;
}


/**
*   Update the 'enabled' value for a sitemap.
*   Called from the admin ajax.php script.
*
*  @param  integer $oldval  Current value
*  @param  string  $fld     Field, smap or gsmap
*  @return integer     New value, or old value if an error occurred
*/
function SMAP_toggleEnabled($pi_name, $fld, $oldval)
{
    global $_TABLES;

    // Sanitize and set values
    $oldval = $oldval == 1 ? 1 : 0;
    $newval = $oldval == 0 ? 1 : 0;
    DB_change($_TABLES['smap_maps'],
                $fld . '_enabled', $newval,
                'pi_name', DB_escapeString($pi_name));
    return DB_error() ? $oldval : $newval;
}


/**
*   Check whether a visitor has access to view the online sitemap
*
*   @return boolean     True if access is allowed, False if not
*/
function SMAP_canView()
{
    global $_CONF, $_SMAP_CONF;

    $retval = NULL;

    if ($retval === NULL) {
        if ( COM_isAnonUser() &&
            (!$_SMAP_CONF['anon_access'] || $_CONF['loginrequired']) ) {
            $retval = false;
        } else {
            $retval = true;
        }
    }
    return $retval;
}


/**
* callback function for the pseudo cron job
*/
function plugin_runScheduledTask_sitemap()
{
    SITEMAP_createGoogleSitemap();
}

?>
