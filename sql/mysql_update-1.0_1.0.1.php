<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | sql_1.0_1.0.1.php                                                        |
// |                                                                          |
// | SQL definitions                                                          |
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

// Default data
$DATA_100_TO_101 = array(
	// Whether to include data source into sitemap
	array('order_article',       1),
	array('order_comments',      2),
	array('order_trackback',     3),
	array('order_staticpages',   4),
	array('order_calendar',      5),
	array('order_links',         6),
	array('order_polls',         7),
	array('order_dokuwiki',      8),
	array('order_forum',         9),
	array('order_filemgmt',     10),
	array('order_faqman',       11),
	array('order_mediagallery', 12),
	array('order_evlist',       13),
	array('order_classifieds',  14),
);

$VALUES_100_TO_101 = array();

// Builds SQL's into $DEFVALUES[]
foreach ($DATA_100_TO_101 as $data) {
	list($name, $value) = $data;
	$name  = DB_escapeString($name);
	$value = DB_escapeString($value);
	$VALUES_100_TO_101['smap_config'][] = "INSERT INTO {$_TABLES['smap_config']} "
		. "VALUES ('" . $name . "', '" . $value . "')";
}
?>