<?php

/**
 * ER Member Ids
 * 
 * This file must be placed in the
 * /system/extensions/ folder in your ExpressionEngine installation.
 *
 * @package ERMemberIds
 * @version 1.0.0
 * @author Erik Reagan http://erikreagan.com
 * @copyright Copyright (c) 2009 Erik Reagan
 * @see http://erikreagan.com/projects/er_member_ids/
 */


if ( ! defined('EXT')) exit('Invalid file request');


class Er_member_ids
{
   
   var $settings = array();

   var $name = 'ER Member Ids';
   var $version = '1.0.0';
   var $description = 'Adds member ids to the View Members page in the CP';
   var $settings_exist = 'n';
   var $docs_url = '';


   /**
   * PHP4 Constructor
   *
   * @see __construct()
   */

   function Er_member_ids($settings='')
   {
      $this->__construct($settings);
   }

   
   /**
   * PHP 5 Constructor
   *
   * @param array|string  Extension settings associative array or an empty string
   */
   function __construct($settings='')
   {
      $this->settings = $settings;
   }


   
   /**
   * Activates the extension
   *
   * @return bool
   */
   function activate_extension()
   {
      global $DB;

      $hooks = array(
         'show_full_control_panel_end' => 'show_full_control_panel_end'
      );

      foreach ($hooks as $hook => $method)
      {
         $sql[] = $DB->insert_string('exp_extensions',
            array(
               'extension_id' => '',
               'class'        => get_class($this),
               'method'       => $method,
               'hook'         => $hook,
               'settings'     => '',
               'priority'     => 10,
               'version'      => $this->version,
               'enabled'      => "y"
            )
         );
      }

      // run all sql queries
      foreach ($sql as $query)
      {
         $DB->query($query);
      }
      
      return TRUE;
   }
   
   
   
   /**
    * Update the extension
    *
    * @param string
    * @return bool
    **/
   function update_extension($current='')
   {
       global $DB;

       if ($current == '' OR $current == $this->version)
       {
           return FALSE;
       }

       $DB->query("UPDATE exp_extensions 
                   SET version = '".$DB->escape_str($this->version)."' 
                   WHERE class = 'Er_member_ids'");
   }
   
   
   
   /**
   * Disables the extension the extension and deletes settings from DB
   */
   function disable_extension()
   {
       global $DB;
       $DB->query("DELETE FROM exp_extensions WHERE class = 'Er_member_ids'");
   }
   
   
   
   /**
    * Add table column to View Members page
    *
    * @return string
    **/
   function show_full_control_panel_end( $out )
   {
      global $EXT, $IN;
      
      if($EXT->last_call !== FALSE)
      {
         $out = $EXT->last_call;
      }
      
      // if its a CP request in the Members module and on the View Members page
      if (REQ == 'CP' && $IN->GBL('M') == "members" && $IN->GBL('P') == "view_members")
      {
         $matches[0] = '/<td(\s.+)>\sUsername/';
         $replacements[0] = "<td$1>\nID\n</td>\n$0";
         
         $matches[1] = '/<td(\s.+)>\s<a.+C=myaccount&amp;id=(\d+)/';
         $replacements[1] = "<td$1>\n$2\n</td>\n$0";
         
         $out = preg_replace($matches, $replacements, $out);
      }
      
      return $out;
      
   }
   
   
}
// END class

/* End of file ext.er_member_ids.php */
/* Location: ./system/extensions/ext.er_er_member_ids.php */