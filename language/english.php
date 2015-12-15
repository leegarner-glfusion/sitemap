<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | english.php                                                              |
// |                                                                          |
// | English Language File                                                    |
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

$LANG_SMAP = array (
    'plugin'            => 'sitemap Plugin',
	'access_denied'     => 'Access Denied',
	'access_denied_msg' => 'Only Root Users have Access to this Page.  Your user name and IP have been recorded.',
	'admin'		        => 'sitemap Plugin Admin',
	'error'             => 'Installation Error',
	'install_header'	=> 'Install/Uninstall the sitemap Plugin',
	'install_success'	=> 'Installation Successful',
	'install_fail'	    => 'Installation Failed -- See your error log to find out why.',
	'uninstall_success'	=> 'Uninstallation Successful',
	'uninstall_fail'    => 'Installation Failed -- See your error log to find out why.',
	'uninstall_msg'		=> 'sitemap plugin was successfully uninstalled.',
	'dataproxy_required' => 'The Data Proxy plugin must be installed and enabled prior to installing the Site Map Plugin',
	'version_required'  => 'The Site Map Plugin requires glFusion v1.1.0 or newer',
	'menu_label'        => 'Sitemap',
	'sitemap'           => 'Sitemap',
	'submit'            => 'submit',
	'all'               => 'All',
	'article'           => 'Stories',
	'comments'          => 'Comments',
	'trackback'         => 'Trackbacks',
	'staticpages'       => 'Staticpages',
	'calendar'          => 'Calendar',
	'links'             => 'Links',
	'polls'             => 'Polls',
	'dokuwiki'          => 'DokuWiki',
	'forum'             => 'Forum',
	'filemgmt'          => 'File Management',
	'faqman'            => 'FAQ',
	'mediagallery'      => 'Media Gallery',
	'evlist'            => 'evList',
	'classifieds'       => 'Classified Ads',
	'sitemap_setting'   => 'Sitemap configuration',
	'sitemap_setting_misc' => 'Display settings',
	'order'             => 'Display Order',
	'up'                => 'Up',
	'down'              => 'Down',
	'anon_access'       => 'Allows anonymous users to access sitemap',
	'sitemap_in_xhtml'  => 'Displays sitemap in XHTML',
	'date_format'       => 'Date format',
	'desc_date_format'  => 'At <strong>Date format</strong>, enter the format string used in the format parameter of PHP\' <a href="http://www.php.net/manual/en/function.date.php">date() function</a>.',
	'sitemap_items'     => 'Items to be included in sitemap',
	'gsmap_setting'     => 'Google sitemap configuration',
	'file_creation'     => 'File creation settings',
	'google_sitemap_name' => 'File name: ',
	'time_zone'         => 'Time zone: ',
	'update_now'        => 'Update now!',
	'last_updated'      => 'Last updated: ',
	'unknown'           => 'unknown',
	'desc_filename'     => 'At <strong>File Name</strong>, enter the file name(s) of Google Sitemap.  You can specifiy more than one file name separated by a semicolon(;).  For Mobile Sitemap, enter "mobile.xml".',
	'desc_time_zone'    => 'At <strong>Time zone</strong>, enter the time zone of the Web server you installed glFusion on in <a href="http://en.wikipedia.org/wiki/Iso8601">ISO 8601</a> format ((+|-)hh:mm).  e.g. +09:00(Tokyo), +01:00(Paris), +01:00(Berlin), +00:00(London), -05:00(New York), -08:00(Los Angeles)',
	'gsmap_items'       => 'Items to be included in Google sitemap',
	'item_name'         => 'Item Name',
	'freq'              => 'Frequency',
	'always'            => 'always',
	'hourly'            => 'hourly',
	'daily'             => 'daily',
	'weekly'            => 'weekly',
	'monthly'           => 'monthly',
	'yearly'            => 'yearly',
	'never'             => 'never',
	'priority'          => 'Priority',
	'desc_freq'         => '<strong>Frequency</strong> tells Google web crawlers how often the item is likely to be updated.  Even if you choose "never", Google crawlers will sometimes check if there is any update in the item.',
	'desc_priority'     => 'At <strong>Priority</strong>, enter the value between <strong>0.0</strong> (lowest) and <strong>1.0</strong> (highest).  The default value is <strong>0.5</strong>.',
	'common_setting'    => 'Common Settings',
	'back_to_top'       => 'Back to top',
);
?>