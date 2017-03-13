<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | mysql_install.php                                                        |
// |                                                                          |
// | SQL definitions                                                          |
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
global $_TABLES;

// New table
$_SQL = array(
    'smap_maps' => "CREATE TABLE `{$_TABLES['smap_maps']}` (
      `pi_name` varchar(40) NOT NULL,
      `orderby` int(2) unsigned NOT NULL DEFAULT 999,
      `priority` decimal(1,1) NOT NULL DEFAULT '0.5',
      `smap_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
      `gsmap_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
      `freq` varchar(10) NOT NULL DEFAULT 'weekly',
      PRIMARY KEY (`pi_name`))",
);


// Default data
$DEFAULT_DATA['smap_conf'] = "INSERT INTO {$_TABLES['smap_config']} (`name`,`value`) VALUES ('anon_access', 'true'),
    ('date_format', '\[\[Y-m-d\] \]'),
    ('sitemap_in_xhtml', 'false'),
    ('sitemap_article', 'true'),
    ('sitemap_staticpages', 'true'),
    ('sitemap_calendar', 'true'),
    ('sitemap_links', 'true'),
    ('sitemap_polls', 'true'),
    ('sitemap_forum', 'true'),
    ('sitemap_filemgmt', 'true'),
    ('sitemap_faqman', 'true'),
    ('sitemap_dokuwiki', 'true'),
    ('sitemap_comments', 'true'),
    ('sitemap_trackback', 'true'),
    ('sitemap_mediagallery', 'true'),
    ('sitemap_evlist','true'),
    ('sitemap_classifieds','true'),
    ('google_sitemap_name', 'sitemap.xml'),
    ('time_zone', '-05:00'),
    ('gsmap_article', 'true'),
    ('gsmap_staticpages', 'true'),
    ('gsmap_calendar', 'true'),
    ('gsmap_links', 'true'),
    ('gsmap_polls', 'true'),
    ('gsmap_forum', 'true'),
    ('gsmap_filemgmt', 'true'),
    ('gsmap_faqman', 'true'),
    ('gsmap_dokuwiki', 'true'),
    ('gsmap_comments', 'false'),
    ('gsmap_trackback', 'false'),
    ('gsmap_mediagallery', 'true'),
    ('gsmap_evlist','true'),
    ('gsmap_classifieds','true'),
    ('freq_article', 'daily'),
    ('freq_staticpages', 'weekly'),
    ('freq_calendar', 'daily'),
    ('freq_links', 'weekly'),
    ('freq_polls', 'weekly'),
    ('freq_forum', 'daily'),
    ('freq_filemgmt', 'daily'),
    ('freq_faqman', 'weekly'),
    ('freq_dokuwiki', 'daily'),
    ('freq_comments', 'daily'),
    ('freq_trackback', 'daily'),
    ('freq_mediagallery', 'daily'),
    ('freq_evlist','daily'),
    ('freq_classifieds','weekly'),
    ('priority_article', '0.5'),
    ('priority_staticpages', '0.5'),
    ('priority_calendar', '0.5'),
    ('priority_links', '0.5'),
    ('priority_polls', '0.5'),
    ('priority_forum', '0.5'),
    ('priority_filemgmt', '0.5'),
    ('priority_faqman', '0.5'),
    ('priority_dokuwiki', '0.5'),
    ('priority_comments', '0.5'),
    ('priority_trackback', '0.5'),
    ('priority_mediagallery', '0.5'),
    ('priority_evlist','0.5'),
    ('priority_classifieds','0.5'),
    ('order_article',       1),
    ('order_comments',      2),
    ('order_trackback',     3),
    ('order_staticpages',   4),
    ('order_calendar',      5),
    ('order_links',         6),
    ('order_polls',         7),
    ('order_dokuwiki',      8),
    ('order_forum',         9),
    ('order_filemgmt',     10),
    ('order_faqman',       11),
    ('order_mediagallery', 12),
    ('order_evlist',       13),
    ('order_classifieds',  14),
    ('sp_type',            2),
    ('sp_except',   'formmail');";

global $_SMAP_UPG_SQL;
$_SMAP_UPG_SQL = array(
'2.0.0' => array(
    "CREATE TABLE `{$_TABLES['smap_maps']}` (
      `pi_name` varchar(40) NOT NULL,
      `orderby` int(2) unsigned NOT NULL DEFAULT 999,
      `priority` decimal(1,1) NOT NULL DEFAULT '0.5',
      `smap_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
      `gsmap_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
      `freq` varchar(10) DEFAULT 'weekly',
      PRIMARY KEY (`pi_name`))",
    "DROP TABLE `{$_TABLES['smap_config']}`",
    "INSERT INTO `{$_TABLES['smap_maps']}`
      (pi_name) VALUES ('articles')";
    ),
);

?>
