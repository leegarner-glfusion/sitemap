<?php
/**
*   Class to handle plugin configurations for sitemaps.
*
*   @author     Lee Garner <lee@leegarner.com>
*   @copyright  Copyright (c) 2017 Lee Garner <lee@leegarner.com>
*   @package    sitemap
*   @version    2.0.0
*   @license    http://opensource.org/licenses/gpl-2.0.php
*               GNU Public License v2 or later
*   @filesource
*/

/**
*   Class for sitemap configurations.
*/
class smapConfig
{
    var $isNew;
    var $properties = array();
    public static $local;

    /**
    *   Constructor.  Sets the local properties using the array $item.
    *
    *   @param  string  $pi_name    Plugin name to read. Optional.
    */
    public function __construct($pi_name = '')
    {
        global $_USER, $_SMAP_CONF;

        $this->isNew = true;
        if ($pi_name == '') {
            $this->pi_name = '';
            $this->freq = 'weekly';
            $this->xml_enabled = 1;
            $this->html_enabled = 1;
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
        if (!$res || DB_numRows($res < 1)) {
            return false;
        } else {
            return $this->SetVars(DB_fetchArray($res, false));
        }
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

        case 'xml_enabled':
        case 'html_enabled':
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
        $this->xml_enabled = $A['xml_enabled'];
        $this->html_enabled = $A['html_enabled'];
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
                xml_enabled = {$this->xml_enabled},
                html_enabled = {$this->html_enabled},
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
                (pi_name, html_enabled, xml_enabled, orderby, priority)
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
    *   The order field is incremented by 10, so this adds or subtracts 11
    *   to change the order, then reorders the fields.
    *
    *   @uses   smapConfig::reOrder()
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
                    COM_errorLog("smapConfig::reOrder() SQL error: $sql");
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
    *
    *   First loads all the configured sitemaps where the driver belongs
    *   to an installed plugin, then calls updateConfigs() to scan for
    *   additional plugins with drivers.
    *
    *   @param  boolean $del    True to delete configs for disabled plugins
    */
    public static function loadConfigs($del = false)
    {
        global $_SMAP_MAPS, $_TABLES, $_PLUGINS;

        $_SMAP_MAPS = array();
        $sql = "SELECT * FROM {$_TABLES['smap_maps']}
                ORDER BY orderby ASC";
        $result = DB_query($sql, 1);
        if (DB_error()) {
            COM_errorLog("smapConfig::loadConfigs() SQL error: $sql");
            return;
        }

        // Only load configs for enabled plugins
        while ($A = DB_fetchArray($result, false)) {
            if (in_array($A['pi_name'], $_PLUGINS)) {
                $_SMAP_MAPS[$A['pi_name']] = $A;
            }
        }

        // Update configs for missing plugins, and optionally delete removed
        // plugins
        self::updateConfigs($del);
    }


    /**
    *   Update the priority of a sitemap element.
    *   The valid priorities are defined in sitemap.php.
    *
    *   @param  string  $newvalue   New priority to set
    *   @return float       New value, or old value on error
    */
    public function updatePriority($newvalue)
    {
        global $_SMAP_CONF, $_TABLES, $_SMAP_MAPS;

        // Ensure that the new value is a valid priority. If not,
        // return the original value.
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
            COM_errorLog("smapConfig::updatePriority() SQL error");
        } else {
            // Change the current object's value and Update the in-memory config
            $this->priority = $newvalue;
            $_SMAP_MAPS[$this->pi_name]['priority'] = $this->priority;
        }
        return $this->priority;
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

        // Make sure the new value is valid
        if (array_key_exists($newfreq, $LANG_SMAP['freqs'])) {
            DB_change($_TABLES['smap_maps'], 'freq', $newfreq,
                'pi_name', $this->pi_name);
            if (DB_error()) {
                // Log error and return the old value
                COM_errorLog("smapConfig::updateFreq error: $sql");
            } else {
                // Update the in-memory config and return the new value
                $this->freq = $newfreq;
                $_SMAP_MAPS[$pi_name]['freq'] = $this->freq;
            }
        }
        return $this->freq;
    }


    /**
    *   Clean up sitemap configs by removing uninstalled plugins
    *   and adding new ones.
    *
    *   Updates the $_SMAP_MAPS config table directly; no return value.
    *
    *   @param  boolean $del    True to delete maps, False to only add new
    */
    public static function updateConfigs($del = false)
    {
        global $_PLUGINS, $_PLUGIN_INFO, $_SMAP_MAPS, $_CONF;

        $reload_maps = false;     // Flag to indicate maps need reloading

        // Get any enabled plugins that aren't already in the sitemap table
        // and add them
        $values = array();
        foreach ($_PLUGINS as $pi_name) {
            if (!isset($_SMAP_MAPS[$pi_name])) {
                // Plugin not in config table, see if there's a driver for it
                $classfile = self::getClassPath($pi_name);
                if (is_file($classfile)) {
                    $values[] = $pi_name;
                }
            }
        }
        if (!empty($values)) {
            self::Add($values);
        }

        // Now clean out entries for removed plugins, if any.
        // Ignore local drivers and just remove configs for uninstalled
        // plugins, e.g. not in the $_PLUGIN_INFO array, or those for
        // which a driver can't be found.
        $values = array();
        foreach ($_SMAP_MAPS as $pi_name=>$info) {
            if (in_array($pi_name, self::$local)) continue;
            if (!isset($_PLUGIN_INFO[$pi_name]) ||
                !is_file(self::getClassPath($pi_name)) ) {
                $values[] = $pi_name;
            }
        }
        if (!empty($values)) {
            self::Delete($values);
        }
    }


    /**
    *   Get the path to a sitemap driver.
    *   Checks the plugin directory for a class file. If $builtin is true
    *   then also check for a built-in driver included with this plugin.
    *
    *   @param  string  $pi_name    Name of plugin
    *   @param  boolean $builtin    True to include built-in drivers
    *   @return string      Path to driver file, or NULL if not found
    */
    public static function getClassPath($pi_name, $builtin=true)
    {
        global $_CONF, $_SMAP_CONF;

        $dirs = array($pi_name);
        // If checking the included drivers also, add the sitemap plugin to the
        // directory list.
        if ($builtin) $dirs[] = $_SMAP_CONF['pi_name'];

        foreach ($dirs as $dir) {
            $path = $_CONF['path'] . '/plugins/' . $dir .
                    '/sitemap/' . $pi_name . '.class.php';
            if (is_file($path)) return $path;
        }
        return NULL;
    }

}

// Set the static variable to the names of non-plugin sitemap drivers
// included with the Sitemap plugin. These cannot be deleted.
smapConfig::$local = array('article');

?>
