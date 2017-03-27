<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | mysql_install.php                                                        |
// |                                                                          |
// | SQL definitions                                                          |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009-2017 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// | Lee Garner             lee AT leegarner DOT com                          |
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

if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}
global $_TABLES;

$_SQL['smap_maps'] = "CREATE TABLE `{$_TABLES['smap_maps']}` (
    `pi_name` varchar(40) NOT NULL,
    `orderby` int(2) NOT NULL DEFAULT 999,
    `priority` decimal(2,1) NOT NULL DEFAULT '0.5',
    `html_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `xml_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
    `freq` varchar(10) NOT NULL DEFAULT 'weekly',
    PRIMARY KEY (`pi_name`),
    KEY `orderby` (`orderby`)
) ENGINE=MyISAM
";

$_DATA = array();
$_DATA[] = "INSERT INTO `{$_TABLES['smap_maps']}` (pi_name) VALUES ('article')";

global $_SMAP_UPG_SQL;

$_SMAP_UPG_SQL = array(
    '2.0.0' => array(
        "CREATE TABLE `{$_TABLES['smap_maps']}` (
            `pi_name` varchar(40) NOT NULL,
            `orderby` int(2) NOT NULL DEFAULT 999,
            `priority` decimal(2,1) NOT NULL DEFAULT '0.5',
            `html_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
            `xml_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
            `freq` varchar(10) NOT NULL DEFAULT 'weekly',
            PRIMARY KEY (`pi_name`),
            KEY `orderby` (`orderby`)
        ) ENGINE=MyISAM",
        "INSERT INTO `{$_TABLES['smap_maps']}` (pi_name) VALUES ('article')",
    ),
);
?>
