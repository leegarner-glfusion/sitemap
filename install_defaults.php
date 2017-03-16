<?php
/**
*   Installation Defaults used when loading the online configuration.
*   These settings are only used during the initial installation
*   and upgrade not referenced any more once the plugin is installed.
*
*   @author     Lee Garner <lee@leegarner.com>
*   @copyright  Copyright (c) 2017 Lee Garner <lee@leegarner.com>
*   @package    sitemap
*   @version    2.0.0
*   @license    http://opensource.org/licenses/gpl-2.0.php
*               GNU Public License v2 or later
*   @filesource
*/

if (!defined('GVERSION')) {
    die('This file can not be used on its own!');
}

/*
*   Sitemap default settings
*   @global array
*/
global $_SMAP_DEFAULT;
$_SMAP_DEFAULT = array();

/**
*   Names of sitemap files. By default, sitemap.xml and a moblie version
*   are created
*/
$_SMAP_DEFAULT['xml_filenames'] = 'sitemap.xml;mobile.xml';

/**
*   Can anonymous users view the sitemap?
*   Only applies to the /sitemap url, everyone can see the sitemap files
*   1 = Yes, 0 = No
*/
$_SMAP_DEFAULT['anon_access'] = 1;

/**
*   Initialize Banner plugin configuration
*
*   @return   boolean     true: success; false: an error occurred
*/
function plugin_initconfig_sitemap()
{
    global $_SMAP_CONF, $_SMAP_DEFAULT;

    $me = $_SMAP_CONF['pi_name'];
    $c = config::get_instance();
    if (!$c->group_exists($me)) {
        $c->add('sg_main', NULL, 'subgroup', 0, 0, NULL, 0, true, $me);
        $c->add('fs_main', NULL, 'fieldset', 0, 0, NULL, 0, true, $me);
        $c->add('xml_filenames', $_SMAP_DEFAULT['xml_filenames'],
                'text', 0, 0, 0, 10, true, $me);
        $c->add('anon_access', $_SMAP_DEFAULT['anon_access'],
                'select', 0, 0, 3, 20, true, $me);
    }
    return true;
}

?>
