<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | sitemap.php                                                              |
// |                                                                          |
// | Plugin Data                                                              |
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

global $_DB_table_prefix, $_TABLES;

// set Plugin Table Prefix the Same as glFusion

$_SMAP_table_prefix = $_DB_table_prefix;

// Add to $_TABLES array the tables your plugin uses

$_TABLES['smap_config'] = $_SMAP_table_prefix . 'smap_config';

$_SMAP_CONF = array();

// Plugin info

$_SMAP_CONF['pi_name']          = 'sitemap';
$_SMAP_CONF['pi_display_name']  = 'SiteMap';
$_SMAP_CONF['pi_version']       = '1.1.7';
$_SMAP_CONF['gl_version']       = '1.1.9';
$_SMAP_CONF['pi_url']           = 'http://www.glfusion.org/';
?>