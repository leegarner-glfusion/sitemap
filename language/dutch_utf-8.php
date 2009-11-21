<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | english.php                                                              |
// |                                                                          |
// | English Language File                                                    |
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

$LANG_SMAP = array (
    'plugin'            => 'sitemap Plugin',
	'access_denied'     => 'Toegang Geweigerd',
	'access_denied_msg' => 'Alleen Root Gebruikers hebben toegang tot deze pagina.  Uw gebruikersnaam en IP adres zijn gelogd.',
	'admin'		        => 'sitemap Plugin Beheer',
	'install_header'	=> 'Installeer/Verwijder de sitemap Plugin',
	'install_success'	=> 'Installatie Succesvol',
	'install_fail'	    => 'Installatie Mislukt -- See your error log to find out why.',
	'uninstall_success'	=> 'Verwijderen Succesvol',
	'uninstall_fail'    => 'Installatie Mislukt -- See your error log to find out why.',
	'uninstall_msg'		=> 'sitemap plugin is succesvol verwijderd.',
	'menu_label'        => 'Sitemap',
	'sitemap'           => 'Sitemap',
	'submit'            => 'Opslaan',
	'all'               => 'Alle',
	'article'           => 'Artikelen',
	'comments'          => 'Reacties',
	'trackback'         => 'Trackbacks',
	'staticpages'       => 'Statische Paginas',
	'calendar'          => 'Kalender',
	'links'             => 'Favorieten',
	'polls'             => 'Enquetes',
	'dokuwiki'          => 'DokuWiki',
	'forum'             => 'Forum',
	'filemgmt'          => 'Bestandsbeheer',
	'faqman'            => 'FAQ',
	'mediagallery'      => 'Media Gallery',
	'evlist'            => 'evList',
	'sitemap_setting'   => 'Sitemap Instellingen',
	'sitemap_setting_misc' => 'Scherm Instellingen',
	'order'             => 'Scherm Volgorde',
	'up'                => 'Omhoog',
	'down'              => 'Omlaag',
	'anon_access'       => 'Allows anonymous users to access sitemap',
	'sitemap_in_xhtml'  => 'Toont sitemap in XHTML',
	'date_format'       => 'Datum formaat',
	'desc_date_format'  => 'At <strong>Date format</strong>, enter the format string used in the format parameter of PHP\' <a href="http://www.php.net/manual/en/function.date.php">date() function</a>.',
	'sitemap_items'     => 'Items to be included in sitemap',
	'gsmap_setting'     => 'Google sitemap instellingen',
	'file_creation'     => 'File creation settings',
	'google_sitemap_name' => 'Bestandsnaam: ',
	'time_zone'         => 'Tijdzone: ',
	'update_now'        => 'Nu bijwerken!',
	'last_updated'      => 'Laatst bijgewerkt: ',
	'unknown'           => 'onbekend',
	'desc_filename'     => 'At <strong>File Name</strong>, enter the file name(s) of Google Sitemap.  You can specifiy more than one file name separated by a semicolon(;).  For Mobile Sitemap, enter "mobile.xml".',
	'desc_time_zone'    => 'At <strong>Time zone</strong>, enter the time zone of the Web server you installed glFusion on in <a href="http://en.wikipedia.org/wiki/Iso8601">ISO 8601</a> format ((+|-)hh:mm).  e.g. +09:00(Tokyo), +01:00(Paris), +01:00(Berlin), +00:00(London), -05:00(New York), -08:00(Los Angeles)',
	'gsmap_items'       => 'Items to be included in Google sitemap',
	'item_name'         => 'Item Naam',
	'freq'              => 'Frequentie',
	'always'            => 'altijd',
	'hourly'            => 'elk uur',
	'daily'             => 'dagelijks',
	'weekly'            => 'wekelijks',
	'monthly'           => 'maandelijks',
	'yearly'            => 'jaarlijks',
	'never'             => 'nooit',
	'priority'          => 'Prioriteit',
	'desc_freq'         => '<strong>Frequency</strong> tells Google web crawlers how often the item is likely to be updated.  Even if you choose "never", Google crawlers will sometimes check if there is any update in the item.',
	'desc_priority'     => 'At <strong>Priority</strong>, enter the value between <strong>0.0</strong> (lowest) and <strong>1.0</strong> (highest).  The default value is <strong>0.5</strong>.',
	// Since version 1.1.4
	'common_setting'    => 'Algemene Instellingen',
	'sp_setting'        => 'Statische Paginas',
	'sp_type'           => 'Types of static pages to be listed on the sitemap',
	'sp_type0'          => 'Alle',
	'sp_type1'          => 'Only pages that appear on the center block',
	'sp_type2'          => 'Only pages that do NOT appear on the center block',
	'sp_except'         => 'IDs of pages that should not be listed on the sitemap (space separated)',
	'back_to_top'       => 'Terug Omhoog',
);
?>