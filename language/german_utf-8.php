<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | german_utf-8.php                                                         |
// |                                                                          |
// | German Language File (UTF-8 Version)                                     |
// | Modifiziert: August 09 Tony Kluever									  |
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
    'plugin'            => 'Sitemap-Plugin',
	'access_denied'     => 'Zugriff verweigert',
	'access_denied_msg' => 'Nur Root-Benutzer haben Zugriff auf diese Seite. Dein Benutzername und IP wurden aufgezeichnet.',
	'admin'		        => 'Sitemap-Plugin-Admin',
	'error'             => 'Installationsfehler',
	'install_header'	=> 'Sitemap-Plugin - Installation/Deinstallation',
	'install_success'	=> 'Installation erfolgreich',
	'install_fail'	    => 'Installation fehlgeschlagen -- Schau in die Datei error.log für mehr Infos.',
	'uninstall_success'	=> 'Deinstallation erfolgreich',
	'uninstall_fail'    => 'Deinstallation fehlgeschlagen -- Schau in die Datei error.log für mehr Infos.',
	'uninstall_msg'		=> 'Sitemap-Plugin wurde erfolgreich installiert.',
	'dataproxy_required' => 'Das Dataproxy-Plugin muss installiert und aktiviert sein, bevor das Sitemap-Plugin installiert wird.',
	'version_required'  => 'Das Sitemap-Plugin benötigt glFusion v1.1.0 oder neuer',
	'menu_label'        => 'Sitemap',
	'sitemap'           => 'Sitemap',
	'submit'            => 'Senden',
	'all'               => 'Alle',
	'article'           => 'Artikel',
	'comments'          => 'Kommentare',
	'trackback'         => 'Trackbacks',
	'staticpages'       => 'Stat. Seiten',
	'calendar'          => 'Kalender',
	'links'             => 'Links',
	'polls'             => 'Umfragen',
	'dokuwiki'          => 'DokuWiki',
	'forum'             => 'Forum',
	'filemgmt'          => 'Dateiverwaltung (FileMgmt)',
	'faqman'            => 'FAQ',
	'mediagallery'      => 'Mediengalerie',
	'evlist'            => 'evList',
	'classifieds'       => 'Classified Ads',
	'sitemap_setting'   => 'Sitemap-Konfiguration',
	'sitemap_setting_misc' => 'Anzeigeeinstellungen',
	'order'             => 'Sortierung',
	'up'                => 'Hoch',
	'down'              => 'Runter',
	'anon_access'       => 'Erlaube Gästen auf die Sitemap zuzugreifen',
	'sitemap_in_xhtml'  => 'Zeigt Sitemap in XHTML',
	'date_format'       => 'Datumsformat',
	'desc_date_format'  => 'Bei <strong>Datumsformat</strong>, gib den Formatierungsstring ein, so wie er auch in PHP \' <a href="http://www.php.net/manual/en/function.date.php">date() function</a> verwendet wird.',
	'sitemap_items'     => 'Zu verwendene Objekte in Sitemap',
	'gsmap_setting'     => 'Google-Sitemap - Konfiguration',
	'file_creation'     => 'Einstellungen zur Dateierstellung',
	'google_sitemap_name' => 'Dateiname: ',
	'time_zone'         => 'Zeitzone: ',
	'update_now'        => 'Jetzt aktualisieren!',
	'last_updated'      => 'Zuletzt aktualisiert: ',
	'unknown'           => 'unbekannt',
	'desc_filename'     => 'Bei <strong>Dateiname</strong>, gib den Dateinamen der Google-Sitemap ein. Du kannst mehr als einen Dateinamen angeben, getrennt durch Semikolon(;). Für Mobiltelefon-Sitemap, gib "mobile.xml" ein.',
	'desc_time_zone'    => 'Bei <strong>Zeitzone</strong>, gib die Zeitzone des Servers ein, auf dem glFusion installiert ist. Verwende <a href="http://en.wikipedia.org/wiki/Iso8601">ISO 8601</a> Format ((+|-)hh:mm).  e.g. +09:00(Tokyo), +01:00(Paris), +01:00(Berlin), +00:00(London), -05:00(New York), -08:00(Los Angeles)',
	'gsmap_items'       => 'Zu verwendene Objekte in Google-Sitemap',
	'item_name'         => 'Objektname',
	'freq'              => 'Häufigkeit',
	'always'            => 'immer',
	'hourly'            => 'stündlich',
	'daily'             => 'täglich',
	'weekly'            => 'wöchentlich',
	'monthly'           => 'monatlich',
	'yearly'            => 'jährlich',
	'never'             => 'niemals',
	'priority'          => 'Priorität',
	'desc_freq'         => '<strong>Häufigkeit</strong> teilt den Google-Webcrawlern mit, wir oft das Objekt vorraussichtlich aktualisert wird. Auch wenn Du "niemals" wählst, überprüfen die Google-Crawler irgendwann, ob das Objekt aktualisiert wurde.',
	'desc_priority'     => 'Bei <strong>Priorität</strong>, gib einen Wert zwischen <strong>0.0</strong> (niedrigster) und <strong>1.0</strong> (höchster) ein. Der Standardwert ist <strong>0.5</strong>.',
	// Since version 1.1.4
	'common_setting'    => 'Allgemeine Einstellungen',
	'sp_setting'        => 'Stat. Seiten',
	'sp_type'           => 'Art der stat. Seiten, die in der Sitemap aufgelistet werden',
	'sp_type0'          => 'Alle',
	'sp_type1'          => 'Nur Seiten, die im Centerblock auftauchen',
	'sp_type2'          => 'Nur Seiten, die NICHT im Centerblock auftauchen',
	'sp_except'         => 'IDs der Seiten, die nicht in der Sitemap aufgelistet werden sollen (mittels Leerzeichen getrennt)',
	'back_to_top'       => 'Zurück nach oben',
);
?>