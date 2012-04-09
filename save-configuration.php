<?php
  
  
   if(isset($_POST['my_team']))
   {
   $my_team = $_POST['my_team'];
   $sql = "UPDATE $tableName SET `value` = '$my_team' WHERE tag LIKE 'MY_TEAM' LIMIT 1 ;";  
   mysql_query($sql);      
   } 
    
   if(isset($_POST['widget_header']))
   {
   $w_header = $_POST['widget_header'];
   $sql = "UPDATE $tableName SET `value` = '$w_header' WHERE tag LIKE 'WIDGET_HEADER'  LIMIT 1 ;";
   mysql_query($sql);   
   
   }
   
   if(isset($_POST['widget_width']))
   {
   $w_width = $_POST['widget_width'];
   $sql = "UPDATE $tableName SET `value` = '$w_width' WHERE tag LIKE 'WIDGET_WIDTH' LIMIT 1 ;";
   mysql_query($sql);    
   }
   
   if(isset($_POST['page_width']))
   {
   $w_page_width = $_POST['page_width'];
   $sql = "UPDATE $tableName SET `value` = '$w_page_width' WHERE tag LIKE 'PAGE_WIDTH' LIMIT 1 ;";
   mysql_query($sql);   
   }
   
   
   if(isset($_POST['widget_row']))
   {
   $w_row = $_POST['widget_row'];
   $sql = "UPDATE $tableName SET `value` = '$w_row' WHERE tag LIKE 'WIDGET_ROW' LIMIT 1 ;";
   mysql_query($sql);   
   }
   
   if(isset($_POST['show_siste'])==1)
   {
   $show_siste = $_POST['show_siste'];
   $sql = "UPDATE $tableName SET `value` = '$show_siste' WHERE tag LIKE 'SHOW_SISTE' LIMIT 1 ;";   
   }
   else
   {   
   $sql = "UPDATE $tableName SET `value` = '0' WHERE tag LIKE 'SHOW_SISTE' LIMIT 1 ;";      
   }    
   mysql_query($sql);
   $sql ='';
 
   
   if(isset($_POST['show_neste'])==1)
   {
   $show_neste = $_POST['show_neste'];
   $sql = "UPDATE $tableName SET `value` = '$show_neste' WHERE tag LIKE 'SHOW_NESTE' LIMIT 1 ;";   
   }
   else
   {   
   $sql = "UPDATE $tableName SET `value` = '0' WHERE tag LIKE 'SHOW_NESTE' LIMIT 1 ;";      
   }
   mysql_query($sql);
   $sql ='';
   
   
   if(isset($_POST['show_tabell']))
   {
   $show_tabell = $_POST['show_tabell'];
   $sql = "UPDATE $tableName SET `value` = '$show_tabell' WHERE tag LIKE 'SHOW_TABELL' LIMIT 1 ;";    
   }
   else
   {
   $sql = "UPDATE $tableName SET `value` = '0' WHERE tag LIKE 'SHOW_TABELL' LIMIT 1 ;";          
   }   
   mysql_query($sql);
   $sql ='';
   
   if(isset($_POST['page_row']))
   {                                                               
   $page_row = $_POST['page_row'];
   $sql = "UPDATE $tableName SET `value` = '$page_row' WHERE tag LIKE 'PAGE_ROW' LIMIT 1 ;";
   mysql_query($sql);
   }
   
   if(isset($_POST['google_map']))
   {                                                               
   $google_map = $_POST['google_map'];
   $sql = "UPDATE $tableName SET `value` = '$google_map' WHERE tag LIKE 'GOOGLE_MAP' LIMIT 1 ;";   
   }
   else
   {
   $sql = "UPDATE $tableName SET `value` = '0' WHERE tag LIKE 'GOOGLE_MAP' LIMIT 1 ;";          
   }
   mysql_query($sql);
   $sql ='';
   
   
   
?>