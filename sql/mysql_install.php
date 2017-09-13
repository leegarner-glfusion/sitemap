<?php
/**
* glFusion CMS
*
* Site Map Plugin for glFusion
*
* Database scheme / default data
*
* @license GNU General Public License version 2 or later
*     http://www.opensource.org/licenses/gpl-license.php
*
*  Copyright (C) 2009-2017 by the following authors:
*   Mark R. Evans   mark AT glfusion DOT org
*   Lee Garner      lee AT leegarner DOT com                          |
*
*  Based on the SiteMap Plugin
*  Copyright (C) 2007-2008 by the following authors:
*  Authors: mystral-kk - geeklog AT mystral-kk DOT net
*
*/

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

global $_TABLES;

$_SQL['smap_maps'] = "CREATE TABLE `{$_TABLES['smap_maps']}` (
    `pi_name` varchar(40) NOT NULL,
    `orderby` int(2) NOT NULL DEFAULT 100,
    `priority` decimal(2,1) NOT NULL DEFAULT '0.5',
    `html_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `xml_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `freq` varchar(10) NOT NULL DEFAULT 'weekly',
    PRIMARY KEY (`pi_name`),
    KEY `orderby` (`orderby`)
) ENGINE=MyISAM
";

$_DATA = array();
$_DATA['default_maps'] = "INSERT INTO `{$_TABLES['smap_maps']}` (pi_name,orderby) VALUES ('article',10)";

?>
