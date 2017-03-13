<?php
/**
*   Class to handle plugin configurations for sitemaps.
*
*   @author     Lee Garner <lee@leegarner.com>
*   @copyright  Copyright (c) 2017 Lee Garner <lee@leegarner.com>
*   @package    sitemap
*   @version    0.2.1
*   @license    http://opensource.org/licenses/gpl-2.0.php
*               GNU Public License v2 or later
*   @filesource
*/

/**
*   Class for sitemap items
*/
class smapConfig
{
    var $isNew;
    var $properties = array();
    public static $local;

    /**
    *   Constructor.  Sets the local properties using the array $item.
    *
    *   @param  integer $id     ID (plugin name) to read. Optional.
    */
    public function __construct($pi_name = '')
    {
        global $_USER, $_SMAP_CONF;

        $this->isNew = true;
        if ($pi_name == '') {
            $this->pi_name = '';
            $this->freq = 'weekly';
            $this->gsmap_enabled = 1;
            $this->smap_enabled = 1;
            $this->orderby = 999;
            $this->priority = 0.5;
        } else {
            $this->pi_name = $pi_name;
            if ($this->Read()) {
                $this->isNew = false;
            }
        }
    }


    /**
    *   Read this field definition from the database.
    *
    *   @see smapConfig::SetVars
    *   @param  string  $pi_name    Plugin name
    *   @return boolean     Status from SetVars()
    */
    public function Read($pi_name = '')
    {
        global $_TABLES;

        if ($pi_name != '') {
            $this->pi_name = $pi_name;
        }
        $sql = "SELECT * FROM {$_TABLES['smap_maps']}
                WHERE pi_name='" . DB_escapeString($this->pi_name) . "'";
        $res = DB_query($sql, 1);
        if (!$res || DB_numRows($res < 1)) return false;
        return $this->SetVars(DB_fetchArray($res, false), true);
    }


    /**
    *   Set a value into a property
    *
    *   @param  string  $name       Name of property
    *   @param  mixed   $value      Value to set
    */
    public function __set($name, $value)
    {
        global $LANG_FORMS;
        switch ($name) {
        case 'orderby':
            $this->properties[$name] = (int)$value;
            break;

        case 'priority':
            $this->properties[$name] = (float)$value;
            break;

        case 'gsmap_enabled':
        case 'smap_enabled':
            $this->properties[$name] = $value == 0 ? 0 : 1;
            break;

        default:
            $this->properties[$name] = trim($value);
            break;
        }
    }


    /**
    *   Get a property's value
    *
    *   @param  string  $name       Name of property
    *   @return mixed       Value of property, or empty string if undefined
    */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
           return $this->properties[$name];
        } else {
            return '';
        }
    }


    /**
    *   Set all variables for this field.
    *   Data is expected to be from $_POST or a database record
    *
    *   @param  array   $item   Array of fields for this item
    *   @param  boolean $fromdb Indicate whether this is read from the DB
    */
    public function SetVars($A)
    {
        if (!is_array($A))
            return false;

        $this->orderby = $A['orderby'];
        $this->gsmap_enabled = $A['gsmap_enabled'];
        $this->smap_enabled = $A['smap_enabled'];
        $this->priority = $A['priority'];
        $this->freq = $A['freq'];
        return true;
    }


    /**
    *   Save the field definition to the database.
    *
    *   @param  mixed   $val    Value to save
    *   @return string          Error message, or empty string for success
    */
    public function Save($A = '')
    {
        global $_TABLES, $_CONF_FRM;

        if (is_array($A))
            $this->SetVars($A);

        if ($this->isNew) {
            $sql1 = "INSERT INTO {$_TABLES['smap_maps']} SET 
                pi_name = '" . DB_escapeString($this->pi_name). "', ";
            $sql3 = '';
        } else {
            // Existing record, perform update
            $sql1 = "UPDATE {$_TABLES['smap_maps']} SET ";
            $sql3 .= " WHERE pi_name = '" . DB_escapeString($this->pi_name) . "'";
        }

        $sql2 = "orderby = {$this->orderby},
                gsmap_enabled = {$this->gsmap_enabled},
                smap_enabled = {$this->smap_enabled},
                priority = {$this->priority},
                freq = '" . DB_escapeString($this->freq) . "'";
        $sql = $sql1 . $sql2 . $sql3;

        DB_query($sql, 1);

        if (!DB_error()) {
            // After saving, reorder the fields
            self::reOrder();
            return true;
        } else {
            COM_errorLog("smapConfig::Save Error: $sql");
            return false;
        }
    }


    /**
    *   Delete a sitemap plugin item from the configuration.
    *   Used to remove un-installed plugins.
    *
    *   @param  mixed   $pi_names   Single name or array of names
    *   @return boolean     True on success, False on failure
    */
    public static function Delete($pi_names)
    {
        global $_TABLES;

        if (!is_array($pi_names)) $pi_names = array($pi_names);
        foreach ($pi_names as $pi_name) {
            // Skip non-plugin sitemaps such as articles
            if (in_array($pi_name, self::$local)) continue;
            $values[] = "'" . DB_escapeString($pi_name) . "'";
        }
        if (!empty($values)) {
            $values = implode(', ', $values);
            $sql = "DELETE FROM {$_TABLES['smap_maps']}
                    WHERE pi_name IN ($values)";
            DB_query($sql, 1);
            if (DB_error()) {
                COM_errorLog("smapConfig::Delete() error: $sql");
                return false;
            } else {
                self::reOrder();
                self::loadConfigs();
            }
        }
        return true;
    }


    /**
    *   Add one or more sitemap configs to the config table.
    *   Used to add newly-installed plugins
    *
    *   @param  mixed   $pi_names   Single name or array of names
    *   @return boolean     True on success, False on failure
    */
    public static function Add($pi_names)
    {
        global $_TABLES;

        if (!is_array($pi_names)) $pi_names = array($pi_names);
        foreach ($pi_names as $pi_name) {
            $values[] = "('" . DB_escapeString($pi_name) . "',
                1, 1, 999, 0.5)";
        }
        if (!empty($values)) {
            $values = implode(', ', $values);
            $sql = "INSERT INTO {$_TABLES['smap_maps']}
                (pi_name, smap_enabled, gsmap_enabled, orderby, priority)
                VALUES $values";
            DB_query($sql, 1);
            if (DB_error()) {
                COM_errorLog("smapConfig::Add() error: $sql");
                return false;
            } else {
                self::reOrder();
                self::loadConfigs();
            }
        }
        return true;
    }

   
    /**
    *   Move a sitemap item up or down in the list.
    *
    *   @param  integer $pi_name    Item to move
    *   @param  string  $where      Direction to move ('up' or 'down')
    */
    public static function Move($pi_name, $where)
    {
        global $_CONF, $_TABLES, $LANG21;

        $retval = '';
        $pi_name = DB_escapeString($pi_name);

        switch ($where) {
        case 'up':
            $sign = '-';
            break;

        case 'down':
            $sign = '+';
            break;

        default:
            // Invalid option, return true but do nothing
            return true;
            break;
        }
        $sql = "UPDATE {$_TABLES['smap_maps']}
                SET orderby = orderby $sign 11
                WHERE pi_name = '$pi_name'";
        //echo $sql;die;
        DB_query($sql, 1);
        if (!DB_error()) {
            // Reorder fields to get them separated by 10
            return self::reOrder();
        } else {
            COM_errorLog("smapConfig::Move() error: $sql");
            return false;
        }
    }


    /**
    *   Reorder the sitemap items.
    *   Updates the database, and also the in-memory config array.
    *
    *   @return boolean     True on success, False on DB error
    */
    public static function reOrder()
    {
        global $_TABLES, $_SMAP_MAPS;

        $sql = "SELECT pi_name, orderby FROM {$_TABLES['smap_maps']}
                ORDER BY orderby ASC";
        $result = DB_query($sql);

        $order = 10;
        $stepNumber = 10;
        while ($A = DB_fetchArray($result, false)) {
            if ($A['orderby'] != $order) {  // only update incorrect ones
                $sql = "UPDATE {$_TABLES['smap_maps']}
                    SET orderby = '$order'
                    WHERE pi_name = '" . DB_escapeString($A['pi_name']) . "'";
                DB_query($sql, 1);
                if (DB_error()) {
                    return false;
                }
                // Update the in-memory config array
                $_SMAP_MAPS[$A['pi_name']]['orderby'] = $order;
            }
            $order += $stepNumber;
        }
        return true;
    }


    /**
    *   Load all the sitemap configs into the config array.
    *   Updates the global array variable, no return value.
    */
    public static function loadConfigs()
    {
        global $_SMAP_MAPS, $_TABLES;

        $_SMAP_MAPS = array();
        $sql = "SELECT * FROM {$_TABLES['smap_maps']}
                ORDER BY orderby ASC";
        $result = DB_query($sql);
        if (DB_error()) {
            COM_errorLog('SITEMAP_loadConfig: cannot load config.');
            exit;
        }

        while (($A = DB_fetchArray($result, false)) !== FALSE) {
            $_SMAP_MAPS[$A['pi_name']] = $A;
        }
    }


    /**
    *   Update the priority of a sitemap element
    *
    *   @param  string  $newvalue   New priority to set
    *   @return float       New value, or old value on error
    */
    public function updatePriority($newvalue)
    {
        global $_SMAP_CONF, $_TABLES, $_SMAP_MAPS;

        $oldvalue = $this->priority;
        $good = false;
        foreach ($_SMAP_CONF['priorities'] as $prio) {
            if ($newvalue == $prio) {
                $good = true;
                break;
            }
        }
        if (!$good) return $this->priority;

        DB_change($_TABLES['smap_maps'], 'priority', $newvalue,
            'pi_name', $this->pi_name);
        if (DB_error()) {
            return $oldvalue;
        } else {
            // Update the in-memory config
            $_SMAP_MAPS[$this->pi_name]['priority'] = (float)$newvalue;
            return $newvalue;
        }
    }
        

    /**
    *   Update the frequency for the current config item.
    *   Called via Ajax from the admin screen.
    *
    *   @param  string  $newfreq    New frequency value
    *   @return string      New value, or old value on error
    */
    public function updateFreq($newfreq)
    {
        global $LANG_SMAP, $_TABLES, $_SMAP_MAPS;

        // Save the old value to return in case of error
        $oldfreq = $this->freq;

        // Make sure the new value is valid
        if (array_key_exists($newfreq, $LANG_SMAP['freqs'])) {
            $sql = "UPDATE {$_TABLES['smap_maps']}
                    SET freq = '" . DB_escapeString($newfreq) . "'
                    WHERE pi_name = '" . DB_escapeString($this->pi_name) . "'";
            DB_query($sql, 1);
            if (DB_error()) {
                // Log error and return the old value
                COM_errorLog("smapConfig::updateFreq error: $sql");
                return $oldfreq;
            } else {
                // Update the in-memory config and return the new value
                $_SMAP_MAPS[$pi_name]['freq'] = $newfreq;
                return $newfreq;
            }
        } else {
            // Invalud value sumbmitted, return the existing value
            return $oldfreq;
        }
    }


    /**
    *   Clean up sitemap configs by removing uninstalled plugins
    *   and adding new ones.
    *
    *   Updates the $_SMAP_MAPS config table directly; no return value.
    */
    public static function cleanConfigs()
    {
        global $_PLUGINS, $_SMAP_MAPS, $_CONF;

        $reload_maps = false;     // Flag to indicate maps need reloading

        // Get any plugins that aren't already in the sitemap table and add them
        $add_values = array();
        foreach ($_PLUGINS as $pi_name) {
            $classfile = self::getClassPath($pi_name);
            if (!isset($_SMAP_MAPS[$pi_name]) &&
                    is_file($classfile)) {
                $values[] = $pi_name;
           }
        }
        if (!empty($values)) {
            self::Add($values);
        }
        // Now clean out entries for removed plugins, if any
        $values = array();
        foreach ($_SMAP_MAPS as $pi_name=>$info) {
            if (in_array($pi_name, self::$local)) continue;
            $classfile = self::getClassPath($pi_name);
            if (!is_file($classfile)) {
                $values[] = $pi_name;
            }
        }
        if (!empty($values)) {
            self::Delete($values);
        }
    }


    public static function getClassPath($pi_name)
    {
        global $_CONF, $_SMAP_CONF;

        if (in_array($pi_name, self::$local)) {
            $pi_dir = $_SMAP_CONF['pi_name'];
        } else {
            $pi_dir = $pi_name;
        }

        $path = $_CONF['path'] . '/plugins/' . $pi_dir .
                    '/sitemap/' . $pi_name . '.class.php';
        return $path;
    }

}

smapConfig::$local = array('article');

?>
