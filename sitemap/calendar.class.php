<?php
/**
*   Sitemap driver for the Calendar Plugin.
*
*   @author     Mark R. Evans <mark@glfusion.org>
*   @copyright  Copyright (c) 2008 Mark R. Evans <mark@glfusion.org>
*   @copyright  Copyright (c) 2007-2008 Mystral-kk <geeklog@mystral-kk.net>
*   @package    glfusion
*   @version    2.0.0
*   @license    http://opensource.org/licenses/gpl-2.0.php
*               GNU Public License v2 or later
*   @filesource
*/

// this file can't be used on its own
if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}


class sitemap_calendar extends sitemap_base
{

    /**
    *   Get the child categories under the supplied category ID.
    *   Only primary categories (event types) are supported.
    *
    *   @param  mixed   $pid    Parent category, must be false.
    *   @return array   Array of category information.
    */
    function getChildCategories($pid = false)
    {
        global $_CONF, $_TABLES;

        $entries = array();

        if ($pid !== false) {
            return $entries;        // sub-categories not supported
        }

        $sql = "SELECT DISTINCT event_type FROM {$_TABLES['events']}
                ORDER BY event_type";
        $result = DB_query($sql, 1);
        if (DB_error()) {
            COM_errorLog("sitemap_calendar::getChildCategories error: $sql");
            return $entries;
        }

        while (($A = DB_fetchArray($result, false)) !== FALSE) {
            $entries[] = array(
                'id'        => $A['event_type'],
                'pid'       => false,
                'title'     => $A['event_type'],
                'uri'       => false,
                'date'      => false,
                'image_uri' => false,
            );
        }
        return $entries;
    }


    /**
    *   Get all the items under the given category.
    *
    *   @param  string  $category   Category (event type)
    *   @return array   Array of (
    *       'id'        => $id (string),
    *       'title'     => $title (string),
    *       'uri'       => $uri (string),
    *       'date'      => $date (int: Unix timestamp),
    *       'image_uri' => $image_uri (string)
    *   )
    */
    function getItems($category = '')
    {
        global $_CONF, $_TABLES;

        $entries = array();

        $sql = "SELECT eid, title, UNIX_TIMESTAMP(datestart) AS day1,
                    UNIX_TIMESTAMP(timestart) AS day2
                FROM {$_TABLES['events']}
                WHERE (status=1
                    AND event_type = '" . DB_escapeString($category) . "') ";
        if ($this->uid > 0) {
            $sql .= COM_getPermSql('AND', $this->uid);
        }
        $sql .= ' ORDER BY day1 DESC, day2 DESC';

        $result = DB_query($sql, 1);
        if (DB_error()) {
            COM_errorLog("sitemap_calendar::getItems() error: $sql");
            return $entries;
        }

        while ($A = DB_fetchArray($result, false)) {
            $entries[] = array(
                'id'        => $A['eid'],
                'title'     => $A['title'],
                'uri'       => $_CONF['site_url'] . '/calendar/event.php?eid='
                                . $A['eid'],
                'date'      => (int)$A['day1'] + (int)$A['day2'],
                'image_uri' => false,
            );
        }
        return $entries;
    }

}

?>
