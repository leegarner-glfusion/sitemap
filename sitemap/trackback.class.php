<?php
// +--------------------------------------------------------------------------+
// | Data Proxy Plugin for glFusion                                           |
// +--------------------------------------------------------------------------+
// | trackback.class.php                                                      |
// |                                                                          |
// | glFusion trackback interface                                             |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009-2015 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Data Proxy Plugin                                           |
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

class sitemap_trackback extends sitemap_base
{

    public function getName()
    {
        return 'trackback';
    }


    public function getDisplayName()
    {
        global $LANG_TRB;
        return $LANG_TRB['trackback'];
    }


    public function getChildCategories($pid = false)
    {
        global $_CONF, $_TABLES, $LANG_SMAP, $_SMAP_MAPS;

        $entries = array();

        if ($pid !== false) {
            return $entries;        // No category support
        }

        $sql = "SELECT DISTINCT type FROM {$_TABLES['trackback']}
                ORDER BY type";
        $result = DB_query($sql);
        if (DB_error()) {
            return $entries;
        }

        while ($A = DB_fetchArray($result, false)) {
            // Only include items that are enabled in the config table.
            if (!array_key_exists($type, $_SMAP_MAPS) &&
                $_SMAP_MAPS[$type][$this->smap_type.'_enabled'] == 1) {
                $entries[] = array(
                    'id'        => $A['type'],
                    'pid'       => false,
                    'title'     => $A['id'],
                    'uri'       => false,
                    'date'      => false,
                    'image_uri' => false,
                );
            }
        }
        return $entries;
    }


    /**
    * Returns array of (
    *   'id'        => $id (string),
    *   'title'     => $title (string),
    *   'uri'       => $uri (string),
    *   'date'      => $date (int: Unix timestamp),
    *   'image_uri' => $image_uri (string),
    *   'raw_data'  => raw data of the item (stripslashed)
    * )
    */
    public function getItemById($id)
    {
        global $_CONF, $_TABLES;

        $retval = array();

        $sql = "SELECT * "
             . "FROM {$_TABLES['trackback']} "
             . "WHERE (cid = '" . DB_escapeString($id) . "') ";
        $result = DB_query($sql);
        if (DB_error()) {
            return $retval;
        }

        if (DB_numRows($result) == 1) {
            $A = DB_fetchArray($result, false);

            $retval['id']        = $id;
            $retval['title']     = $A['title'];
            $retval['uri']       = $A['url'];    // maybe needs cleaning
            $retval['date']      = strtotime($A['date']);
            $retval['image_uri'] = false;
            $retval['raw_data']  = $A;
        }

        return $retval;
    }


    /**
    * Returns an array of (
    *   'id'        => $id (string),
    *   'title'     => $title (string),
    *   'uri'       => $uri (string),
    *   'date'      => $date (int: Unix timestamp),
    *   'image_uri' => $image_uri (string)
    * )
    */
    public function getItems($category = 0)
    {
        global $_CONF, $_TABLES;

        $entries = array();

        $sql = "SELECT cid, title, url, UNIX_TIMESTAMP(date) AS day "
             . "FROM {$_TABLES['trackback']} "
             . "WHERE (type = '" . DB_escapeString($category) . "') "
             . "ORDER BY day DESC";
        $result = DB_query($sql);
        if (DB_error()) {
            return $entries;
        }

        while (($A = DB_fetchArray($result, false)) !== FALSE) {
            $entry = array();

            $entry['id']        = $A['cid'];
            $entry['title']     = $A['title'];
            $entry['uri']       = $this->cleanUrl($A['url']);
            $entry['date']      = $A['day'];
            $entry['image_uri'] = false;

            $entries[] = $entry;
        }

        return $entries;
    }

}

?>
