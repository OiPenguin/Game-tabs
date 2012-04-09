<?php 
function define_xml_variable()
{
$tableName="wp_strommen_xml_feed"; 
$sql = "SELECT * FROM $tableName";
$config_data = mysql_query($sql);
    while($config_result = mysql_fetch_array($config_data)) {
         $$config_result['xml_tag']=$config_result['xml_url'];                  
         }                                                             
       if(!defined('XML_MATCH_GLOBAL'))
       {   
           define('XML_MATCH_GLOBAL',$XML_MATCH); 
       }
       if(!defined('XML_TOURNAMENT_GLOBAL'))
       {
          define('XML_TOURNAMENT_GLOBAL',$XML_TOURNAMENT); 
       }
       if(!defined('XML_MATCH_TOURNAMENT_GLOBAL'))
       {
          define('XML_MATCH_TOURNAMENT_GLOBAL',$XML_MATCH_TOURNAMENT); 
       }                                                             
}
?>