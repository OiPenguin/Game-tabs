<?php
/*
Plugin Name: Game tabs
Description: Fetch XML-feeds from Speaker (commercially available) and display fixture list, results and league table for the Norwegian football leauge.
Plugin URI: http://wordpress.org/extend/plugins/game-tabs/
Version: 0.4.0
Author: OiPenguin, Espen Gjester, Jon-Erik Andersgaard
Author URI: http://www.lars.kvisle.no/plugins/game-tabs/
License: GPLv2 or later
*/ 

/*
Copyright 2011  Strømmen IF  (email : lars@kvisle.no)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

////////////////////////////////////////////////////////   
function sampleMyStrommen($tournament=null,$tname=null)
{
?>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/game-tabs/js/tabber.js"></script>
<link rel="stylesheet" href="<?php echo plugins_url(); ?>/game-tabs/css/style_gt.css" TYPE="text/css" MEDIA="screen">

<?php /* here is javascript for tabs ?> */ ?>
<script type="text/javascript">
/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>                                                                                                                            
<script type="text/javascript">                                                                                                      
/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */                                                                                            
document.write('<style type="text/css">.tabber{display:none;}<\/style>');                                                            
var tabberOptions = {                                                                                                                

  'onClick': function(argsObj) {
    var t = argsObj.tabber; /* Tabber object */	
    var i = argsObj.index; /* Which tab was clicked (0..n) */
    var div = this.tabs[i].div; /* The tab content div */
    /* Display a loading message */
    div.innerHTML = "<p>Loading...<\/p>";
    var myAjax = new Ajax.Updater(div, url, {method:'get',parameters:pars});
  },

  'onLoad': function(argsObj) {    
    argsObj.index = 0;
    this.onClick(argsObj);
  },                                     
}
</script>
<?php  /*  javascrip end here */  ?>
<p>
<?php echo $tname; ?>
</p>
<div class="tabber">
     <?php
     if(SHOW_SISTE_GLOBAL == 1 && TEAM_GLOBAL !='' ){?>                   
     <div class="tabbertab" style="width: <?php echo WIDGET_WIDTH_GLOBAL; ?>px;">
      <h2><?php _e('Latest matches','MyLanguage'); ?></h2>
      <?php
       //WHERE CURDATE()>= wp_strommen_matches.match_date AND
       $sql_match = "SELECT wp_strommen_matches.*,t1.url as team_url1,t2.url as team_url2 FROM wp_strommen_matches 
            RIGHT JOIN wp_strommen_team_master t1 
            ON t1.team_id = wp_strommen_matches.home_team_id 
            RIGHT JOIN wp_strommen_team_master t2 
            ON t2.team_id = wp_strommen_matches.away_team_id 
            WHERE CURDATE() >= wp_strommen_matches.match_date AND
            wp_strommen_matches.match_tournament_id = $tournament AND 
            (wp_strommen_matches.home_team_id  IN (".TEAM_GLOBAL.") OR wp_strommen_matches.away_team_id  IN (".TEAM_GLOBAL.") ) ORDER BY wp_strommen_matches.match_date DESC LIMIT " .WIDGET_ROW_GLOBAL ; 
       
       $result_match = mysql_query($sql_match);
       $numberofrow = mysql_num_rows($result_match); 
      
       ?>
       <table border="1">
      <tr class="table_row"><th><?php _e('Date','MyLanguage'); ?></th><th><?php _e('Home Team','MyLanguage'); ?></th><th><?php _e('Away Team','MyLanguage'); ?></th><th><?php _e('Result','MyLanguage'); ?></th></tr>
       <?php if($numberofrow > 0) { ?>      
      <?php
      
      while( $data_match = mysql_fetch_array($result_match))  {   
      
       ?>
      <tr class="table_row">
       <td><?php echo date('d.m',strtotime($data_match['match_date'])) ;?></td>
       <td><?php echo $data_match['hometeam']; ?></td>       
      <?php              
       if( isset($data_match['team_url2']) && $data_match['team_url2']!='' ) // && ($data_match['away_team_id'] == $data_match['team_id'])
       {
       $link = '';
       $link = $data_match['team_url2'];
       if(isset($link) && $link!=''){
       if(substr($link,0,7)!="http://" )
       {$link="http://" . $link;}                                                         
       }
       ?>                         
      <td><a href="<?php echo $link; ?>" target="_blank" > <?php echo $data_match['away_team']; ?> </a> </td>                             
      <?php }else{?>
      <td><?php echo $data_match['away_team']; ?></td>
      <?php } ?> 
      
       <?php
            $txt1 ='' ;
            $link1 = $data_match['url'];
            if(isset($link1) && $link1!=''){
            if(substr($link1,0,7)!="http://" )
            {$link1="http://" . $link1;}                            
            $txt1 = "more info";
            
            ?>
            <td align="center"><a href="<?php echo $link1; ?>" target="_blank" ><?php echo $data_match['home_goals']."-".$data_match['away_goals'] ;?></a></td>
            <?php } else { ?>
            <td align="center"><?php echo $data_match['home_goals']."-".$data_match['away_goals'] ;?></td>
            <?php } ?> 
      
      </tr>
      <?php } ?>
      <tr><td colspan="6"><a href="<?php echo plugins_url(); ?>/game-tabs/mainpage-gt.php?t=<?php echo $tournament ?>&n=<?php echo $tname?>" ><?php _e('show all matches','MyLanguage'); ?></a></td></tr>
      
      <?php } else { ?>
      <tr align="center"><td colspan="6"><?php _e('No Records','MyLanguage'); ?></td></tr>     
      <?php } ?>
      </table>
     </div> 
     <?php } ?>    
      
     <?php 
     if(SHOW_NESTE_GLOBAL == 1 && TEAM_GLOBAL !='' ){?>                
     <div class="tabbertab">
      <h2><?php _e('Next five','MyLanguage'); ?></h2>
      <?php
       $sql_match2 = "SELECT wp_strommen_matches.*,t1.url as team_url1,t2.url as team_url2 FROM wp_strommen_matches 
            RIGHT JOIN wp_strommen_team_master t1 
            ON t1.team_id = wp_strommen_matches.home_team_id 
            RIGHT JOIN wp_strommen_team_master t2 
            ON t2.team_id = wp_strommen_matches.away_team_id 
            WHERE CURDATE()<= wp_strommen_matches.match_date AND
            wp_strommen_matches.match_tournament_id = $tournament AND 
            (wp_strommen_matches.home_team_id  IN (".TEAM_GLOBAL.") OR wp_strommen_matches.away_team_id  IN (".TEAM_GLOBAL.") ) ORDER BY wp_strommen_matches.match_date ASC LIMIT " .WIDGET_ROW_GLOBAL ; 
       $result_match2 = mysql_query($sql_match2);  
       $numberofrow2 = mysql_num_rows($result_match2); ?>
       
       <table border="1">
       <tr class="table_row"><th><?php _e('Date','MyLanguage'); ?></th><th><?php _e('Time','MyLanguage'); ?></th><th><?php _e('Home Team','MyLanguage'); ?></th><th><?php _e('Away Team','MyLanguage'); ?></th><th><?php _e('Venue','MyLanguage'); ?></th></tr>
       <?php if($numberofrow2 > 0) { ?>
      <?php
      while($data_match2 = mysql_fetch_array($result_match2)) {
      ?>
       <tr class="table_row">
       <td><?php echo date('d.m',strtotime($data_match2['match_date'])) ;?></td>
       <td><?php echo $data_match2['match_start_time'] ;?></td>
       <td><?php echo $data_match2['hometeam']; ?></td>             
       <?php                          
       if( isset($data_match2['team_url2']) && $data_match2['team_url2']!=''  ) // && ($data_match2['away_team_id'] == $data_match2['team_id'])
       {
       $link = '';
       $link = $data_match2['team_url'];
       if(isset($link) && $link!=''){
       if(substr($link,0,7)!="http://" )
       {$link="http://" . $link;}                                                         
       }
       ?>                         
      <td><a href="<?php echo $link; ?>" target="_blank" > <?php echo $data_match2['away_team']; ?> </a> </td>                             
      <?php }else{?>
      <td><?php echo $data_match2['away_team']; ?></td>
      <?php } 
      
      if(GOOGLE_MAP_GLOBAL==1) {
      ?>
       <td><a href="http://maps.google.com/maps?q=<?php echo $data_match2['venue_name']; ?>" target="_blank"><?php echo $data_match2['venue_name'] ;?></a></td>
      <?php }else{?>
      <td><?php echo $data_match2['venue_name'] ;?></a></td>
      <?php } ?>
              <?php
            $txt2 ='' ;
            $link2 = $data_match2['url'];
            if(isset($link2) && $link2!=''){
            if(substr($link2,0,7)!="http://" )
            {$link2="http://" . $link2;}                            
            $txt2 = "more info";
            }
      ?>      
      </tr>
      <?php } ?>
      <tr><td colspan="6"><a href="<?php echo plugins_url(); ?>/game-tabs/mainpage-gt.php?t=<?php echo $tournament ?>&n=<?php echo $tname?>" ><?php _e('show all matches','MyLanguage'); ?></a></td></tr>
      <?php } else { ?>      
      <tr align="center"><td colspan="6"><?php _e('No Records','MyLanguage'); ?></td></tr>            
      <?php } ?>
      </table>
     </div>     
     <?php } ?> 
     
     <?php 
     if(SHOW_TABELL_GLOBAL == 1 ) {?>                                  
     <div class="tabbertab">
      <h2><?php _e('Table','MyLanguage'); ?></h2>
            
      <?php
      
       // this query give you all data of tournament by points DESC and no_diff ASC
       $sql_Allmatch = "SELECT wp_strommen_tournament_matches.*, wp_strommen_team_master.team_id,wp_strommen_team_master.url as team_url FROM wp_strommen_tournament_matches 
       RIGHT JOIN wp_strommen_team_master ON wp_strommen_team_master.team_id = wp_strommen_tournament_matches.org_element_id 
       WHERE wp_strommen_tournament_matches.tournament_id = $tournament 
       ORDER BY wp_strommen_tournament_matches.points DESC, wp_strommen_tournament_matches.no_diff  DESC ";       
             
              
       $result_Allmatch = mysql_query($sql_Allmatch);  
       $numberofrow3 = mysql_num_rows($result_Allmatch);       
      
       $AllNewArray = array();
       if($numberofrow3 > 0 )
       {
            $AlltableArray = array();
            $AlltableDetails = array();
            $i = 1;
            while($data_Allmatch3 = mysql_fetch_array($result_Allmatch)) 
            {                       
                $AlltableDetails['number'] = $i; 
                $AlltableDetails['org_id'] = $data_Allmatch3['org_element_id']; 
                $AlltableDetails['url'] = $data_Allmatch3['team_url']; 
                $AlltableDetails['team_name'] = $data_Allmatch3['team_name']; 
                $AlltableDetails['team_id'] = $data_Allmatch3['team_id']; 
                $AlltableDetails['no_matches'] = $data_Allmatch3['no_matches']; 
                $AlltableDetails['no_diff'] = $data_Allmatch3['no_diff'];
                $AlltableDetails['points'] = $data_Allmatch3['points'];                                
                $AllNewArray[] = $AlltableDetails;
                $i++;           
            }      
      }
      
       //echo "<pre>"; print_r($AllNewArray);echo "<br/>"; echo "</pre>"; exit;
            
       $numberofrow3 = '';
       $MyNewArray = array();
       $MytableArray = array();
       $MytableDetails = array();
       
       $rowlimit = 0;
       
       // this query give you tournament details of my team by points DESC and no_diff ASC
       $sql_match3 = "(SELECT wp_strommen_tournament_matches.*, wp_strommen_team_master.team_id,wp_strommen_team_master.url as team_url FROM wp_strommen_tournament_matches RIGHT JOIN wp_strommen_team_master ON wp_strommen_team_master.team_id = wp_strommen_tournament_matches.org_element_id WHERE wp_strommen_tournament_matches.tournament_id = $tournament AND wp_strommen_tournament_matches.org_element_id IN (".TEAM_GLOBAL.") order by wp_strommen_tournament_matches.points DESC,  wp_strommen_tournament_matches.no_diff DESC LIMIT ".WIDGET_ROW_GLOBAL." )";
       
      
       
       $result_match3 = mysql_query($sql_match3);  
       $numberofrow3 = mysql_num_rows($result_match3);
       if($numberofrow3 > 0 ) 
       {            
            
            while($data_match3 = mysql_fetch_array($result_match3)) 
            {                                            
               
                $MytableDetails['org_id'] = $data_match3['org_element_id']; 
                $MytableDetails['url'] = $data_match3['team_url']; 
                $MytableDetails['team_name'] = $data_match3['team_name']; 
                $MytableDetails['team_id'] = $data_match3['team_id'];                 
                $MytableDetails['no_matches'] = $data_match3['no_matches']; 
                $MytableDetails['no_diff'] = $data_match3['no_diff']; 
                $MytableDetails['points'] = $data_match3['points'];
                $MytableArray[]= $MytableDetails;                                                
            }                      
     
       }           
       $rowlimit =  WIDGET_ROW_GLOBAL - $numberofrow3;
       // this query give you tournament details of except my team by points DESC and no_diff ASC
       $sql_match3 = "(SELECT wp_strommen_tournament_matches.*, wp_strommen_team_master.team_id, wp_strommen_team_master.url as team_url FROM wp_strommen_tournament_matches RIGHT JOIN wp_strommen_team_master ON wp_strommen_team_master.team_id = wp_strommen_tournament_matches.org_element_id WHERE wp_strommen_tournament_matches.tournament_id = $tournament AND wp_strommen_tournament_matches.org_element_id NOT IN (".TEAM_GLOBAL.") order by wp_strommen_tournament_matches.points DESC, wp_strommen_tournament_matches.no_diff DESC LIMIT $rowlimit)";
       
        
       $result_match3 = mysql_query($sql_match3);  
       $numberofrow3 = mysql_num_rows($result_match3);     
       
       if($numberofrow3 > 0 ) 
       {          
            
            while($data_match3 = mysql_fetch_array($result_match3)) 
            {                                            
                $MytableDetails['org_id'] = $data_match3['org_element_id']; 
                $MytableDetails['url'] = $data_match3['team_url']; 
                $MytableDetails['team_name'] = $data_match3['team_name']; 
                $MytableDetails['team_id'] = $data_match3['team_id'];
                $MytableDetails['no_matches'] = $data_match3['no_matches']; 
                $MytableDetails['no_diff'] = $data_match3['no_diff']; 
                $MytableDetails['points'] = $data_match3['points'];
                $MytableArray[]= $MytableDetails;                                
            }                      
      
    
       }       
       
       $result = array();
       $table = array();
       $value = null;
       
       // Here we compary both array and fatchout data      
       foreach($MytableArray as $key=>$value)
       {           
           $value2 = null;
           foreach($AllNewArray as $key2=>$value2)
           {
               
               if($value['org_id']==$value2['org_id'])
               {
                $table['number']=$value2['number'];
                $table['org_id']=$value2['org_id'];
                $table['url']=$value2['url'];
                $table['team_id'] = $table['team_id'];
                $table['team_name']=$value2['team_name'];
                $table['no_matches']=$value2['no_matches'];
                $table['no_diff']=$value2['no_diff'];
                $table['points']=$value2['points'];                
               }
           }
        $result[] = $table;       
       }
        
       $result1=array_sort($result, 'number', SORT_ASC); // here we short that data as per number
       
       
       foreach($result1 as $key3=>$value3 ) { // this is for a only index change to show on front side
           $datawidget[]=$value3;
       }
      
       $result_match3 = mysql_query($sql_match3);  
       $numberofrow3 = mysql_num_rows($result_match3); 
     
      ?>
     
      <table border="1">
      <tr class="table_row"><th><?php _e('Number','MyLanguage'); ?></th><th><?php _e('Team Name','MyLanguage'); ?></th><th><?php _e('Game Played','MyLanguage'); ?></th><th><?php _e('Goal Diff','MyLanguage'); ?></th><th><?php _e('Points','MyLanguage'); ?></th></tr>
      <?php if(count($result_match3) > 0) { ?>      
      <?php //DebugBreak();      
      for($a=0;$a<count($datawidget);$a++) {
      ?>
      <tr class="table_row">
      <td align="center"><?php echo $datawidget[$a]['number']?></td>
      <?php if( isset($datawidget[$a]['url']) && $datawidget[$a]['url']!='')       
      {
           $link = '';
           $link = $datawidget[$a]['url'];
           if(isset($link) && $link!=''){
           if(substr($link,0,7)!="http://" )
           {$link="http://" . $link;}                                                         
           }                             
           ?>
           <td align="left"><a href="<?php echo $link; ?>" target="_blank" > <?php echo $datawidget[$a]['team_name']; ?></a></td>
           <?php
           }else{
           ?>
           <td align="left"><?php echo $datawidget[$a]['team_name']; ?></td>
           <?php } ?>
       <td align="center"><?php echo $datawidget[$a]['no_matches'] ;?></td>
       <td align="center"><?php echo $datawidget[$a]['no_diff'] ;?></td>
       <td align="center"><?php echo $datawidget[$a]['points'] ;?></td>       
      </tr>
      <?php } ?>
      <tr><td colspan="6"><a href="<?php echo plugins_url(); ?>/game-tabs/mainpage-gt.php?t=<?php echo $tournament ?>&n=<?php echo $tname?>" ><?php _e('show entire table','MyLanguage'); ?></a></td></tr>      
      <?php } else { ?>      
      <tr align="center"><td colspan="6"><?php _e('No Records','MyLanguage'); ?></td></tr>
      <?php } ?>
      </table>
      </div>  
      <?php } ?>                             
     
</div>
                              
<?php
}   
////////////////////////////////////////////////////////
function widget_myStrommen($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?>
  <?php if(TOURNAMENT_GLOBAL!='') {?>
  <?php echo WIDGET_HEADER_GLOBAL; ?>  
  <?php echo $after_title;  
  $no_of_widget = explode(',',TOURNAMENT_GLOBAL);  
  for($i=0;$i<count($no_of_widget);$i++)
  {
      $tname = "SELECT * FROM wp_strommen_tournaments WHERE tournament_id = ".$no_of_widget[$i];
      $tdata = mysql_query($tname);
      $numberofrow = mysql_num_rows($tdata);
      if($numberofrow > 0)
      {
          $data = mysql_fetch_array($tdata);
          $name =  $data['tournament_name'];  
      }
      sampleMyStrommen($no_of_widget[$i],$name);   
  }  
  }
  echo $after_widget;
}
//////////////// this function call when plug-in activated ////////////////////////////////////////
function table_create()
    {       
             
      global $wpdb;      
      //////////////////////  wp_matches //////////////////////////////////
        $table =    "wp_strommen_matches";   
        $structure = "CREATE TABLE $table(
                      id int(15) NOT NULL auto_increment,
                      match_id int(15) NOT NULL,
                      match_number int(15) NOT NULL,
                      match_date date NOT NULL,
                      round_id int(15) NOT NULL,
                      home_team_id int(15) NOT NULL,
                      hometeam varchar(300) NOT NULL,
                      away_team_id int(11) NOT NULL,
                      away_team varchar(300) NOT NULL,
                      match_start_time varchar(300) NOT NULL,
                      home_goals int(15) NOT NULL,
                      away_goals int(15) NOT NULL,
                      incidents_in varchar(15) NOT NULL,
                      venue_id int(15) NOT NULL,
                      venue_name varchar(300) NOT NULL,
                      url varchar(1000) NOT NULL,
                      last_changed date NOT NULL,
                      match_tournament_id int(15) NOT NULL,
                      PRIMARY KEY  (id))" ;      
      $wpdb->query($structure);
      //////////////////////  wp_tournaments //////////////////////////////////
      $table = "wp_strommen_tournaments";
      $structure = "CREATE TABLE $table(
                    id int(15) NOT NULL auto_increment,
                    job_name varchar(300) NOT NULL,
                    tournament_id int(15) NOT NULL,
                    tournament_name varchar(300) NOT NULL,
                    tournament_type varchar(30) NOT NULL,
                    nation varchar(300) NOT NULL,
                    gender varchar(30) NOT NULL,
                    admin_id int(15) NOT NULL,
                    url varchar(1000) NOT NULL,
                    PRIMARY KEY  (id)) " ;  
     $wpdb->query($structure);
     ///////////////////////////  wp_tournament_matches //////////////////////         
     $table = "wp_strommen_tournament_matches";
     $structure = "CREATE TABLE $table(
                    id int(15) NOT NULL auto_increment,
                    tournament_id int(15) NOT NULL,
                    tournament_name varchar(300) NOT NULL,
                    last_change date NOT NULL,
                    no_home_matches int(11) NOT NULL,
                    no_home_wins int(11) NOT NULL,
                    no_home_draws int(11) NOT NULL,
                    no_homes_losses int(11) NOT NULL,
                    no_home_goals int(11) NOT NULL,
                    no_home_against int(11) NOT NULL,
                    no_away_matches int(11) NOT NULL,
                    no_away_wins int(11) NOT NULL,
                    no_away_draws int(11) NOT NULL,
                    no_away_losses int(11) NOT NULL,
                    no_away_goals int(11) NOT NULL,
                    no_away_against int(11) NOT NULL,
                    no_matches int(11) NOT NULL,
                    no_wins int(11) NOT NULL,
                    no_draws int(11) NOT NULL,
                    no_losses int(11) NOT NULL,
                    no_goals int(11) NOT NULL,
                    no_against int(11) NOT NULL,
                    no_diff int(11) NOT NULL,
                    points int(11) NOT NULL,
                    team_name varchar(300) NOT NULL,
                    org_element_id varchar(15) NOT NULL,
                    local_council varchar(300) NOT NULL,
                    parent_id int(15) NOT NULL,
                    url varchar(1000) NOT NULL,
                    PRIMARY KEY  (`id`))";
     $wpdb->query($structure);
    
     /////////////////////////////// // config table  //////////////////////////////////////////// 
     $table =  "wp_strommen_config";
     $structure = "CREATE TABLE $table(
                    id INT( 11 ) NOT NULL AUTO_INCREMENT ,
                    name VARCHAR( 500 ) NOT NULL ,
                    tag VARCHAR( 500 ) NOT NULL ,
                    value VARCHAR( 500 ) NOT NULL ,
                    description VARCHAR( 1000 ) NOT NULL ,
                    date DATE NOT NULL ,
                    PRIMARY KEY ( `id` ))";
     $wpdb->query($structure);     
     $line1 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`, `description`, `date`)VALUES('Team Name', 'MY_TEAM', '', 'Name of team', CURDATE());";
     $wpdb->query($line1);
     $line2 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Widget Header', 'WIDGET_HEADER', 'SERIEN', 'Widget header name', CURDATE());";
     $wpdb->query($line2);
     $line3 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Widget Width', 'WIDGET_WIDTH', '250', 'Widget Width', CURDATE());";
     $wpdb->query($line3);
     $line4 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Number Of row in Widget', 'WIDGET_ROW', '7', 'Number Of row in Widget', CURDATE());";
     $wpdb->query($line4);
     $line5 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Show Siste kampe', 'SHOW_SISTE', '1', 'Show Siste kampe', CURDATE());";
     $wpdb->query($line5);
     $line6 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Show Neste 5', 'SHOW_NESTE', '1', 'Show Neste 5', CURDATE());";
     $wpdb->query($line6);
     $line7 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Shoe Tabell', 'SHOW_TABELL', '1', 'Shoe Tabell', CURDATE());";
     $wpdb->query($line7);
     $line8 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Number Of row in page', 'PAGE_ROW', '10', 'Number Of row in page', CURDATE());";
     $wpdb->query($line8);
     $line9 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Tournaments', 'TOURNAMENT', '', 'tournament', CURDATE());";
     $wpdb->query($line9);
     $line10 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Teams', 'TEAM', '', 'teams', CURDATE());";
     $wpdb->query($line10);
     $line11 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Matches', 'MATCH_XML', 'http://fotball.speaker.no/ResultDistribution/GenerateXML.aspx?t=matches&tid=', 'match xml link', CURDATE());";
     $wpdb->query($line11);
     $line12 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Tables', 'TABLE_XML', 'http://fotball.speaker.no/ResultDistribution/GenerateXML.aspx?t=table&tid=', 'table xml link', CURDATE());";
     $wpdb->query($line12);
     $line13 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Main Feed', 'MAIN_FEED_XML', '', 'main xml link', CURDATE());";
     $wpdb->query($line13);
     $line14 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Page  Width', 'PAGE_WIDTH', '580', 'page widget width', CURDATE());";
     $wpdb->query($line14);
     $line15 = "INSERT INTO wp_strommen_config(`name`, `tag`, `value`,`description`, `date`)VALUES('Google maps', 'GOOGLE_MAP', '1', 'Google map active or inactive', CURDATE());";
     $wpdb->query($line15);
     
     
     //////////////////////////////////////////  Google maps    /////////////////////////////////////////        
     $table = "wp_strommen_team_master";
     $structure = "CREATE TABLE $table (
                    id INT( 11 ) NOT NULL AUTO_INCREMENT ,
                    team_name VARCHAR( 256 ) NOT NULL ,
                    team_id INT( 11 ) NOT NULL ,
                    tournament_id INT (11) NOT NULL,
                    tournament_name VARCHAR( 256 ) NOT NULL ,
					url varchar(1000) NOT NULL,
                    PRIMARY KEY ( id ))";
     $wpdb->query($structure);
     /////////////////////////////////////////////////////////////////////////////////
    }
///////////////// this function call when plug-in deactivated ///////////////////////////////////////
function drop_table()    
    {
        global $wpdb;
        
        $table = "wp_strommen_matches";
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
        
        $table = "wp_strommen_tournaments";
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
        
        $table = "wp_strommen_tournament_matches";
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
        
        /*$table = $wpdb->prefix."strommen_xml_feed";
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);*/
        
        $table =  "wp_strommen_config";
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
        
        $table = "wp_strommen_team_master";
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
        
    } 
////////////////////////////////////////////////////////
function myStrommen_init()
{   
    register_sidebar_widget(__('Game tabs'), 'widget_myStrommen');
    //register_sidebar_widget(__('Strommen'), 'widget_myStrommen');
    define_variable();
    
    $plugin_dir = basename(dirname(__FILE__));    
    load_plugin_textdomain('MyLanguage','/wp-content/plugins/'.$plugin_dir.'/languages');

}
////////////////////////////////////////////////////////

add_action("plugins_loaded", "myStrommen_init");  

?>
<?php 

register_activation_hook(__FILE__, "table_create");
register_deactivation_hook( __FILE__, 'drop_table' );
add_action('admin_menu', 'game_menu');  

function game_menu() {
    //    DebugBreak();        
	if (function_exists('add_menu_page')) {		        
        add_menu_page(__('Game Menu', 'game-tabs'), __('Game Tabs', 'game-tabs'), 'game-tabs', 'game-tabs/configuration.php', '', plugins_url('game-tabs/images/ball.png'));
        //add_menu_page(__('Game Menu', 'game-tabs'), __('Front Configuration', 'game-tabs'), 'game-tabs', 'game-tabs/configuration_xml.php', '', plugins_url('game-tabs/images/ball.png'));
    }
	if (function_exists('add_submenu_page')) {
		add_submenu_page('game-tabs/configuration.php', __('Front Configuration', 'game-tabs'), __('Front Configuration', 'game-tabs'), 'game-tabs', 'game-tabs/configuration.php');
        add_submenu_page('game-tabs/configuration.php', __('XML Configuration', 'game-tabs'), __('XML Configuration', 'game-tabs'), 'game-tabs', 'game-tabs/configuration_xml.php');        
        add_submenu_page('game-tabs/configuration.php', __('Match', 'game-tabs'), __('Match', 'game-tabs'), 'game-tabs', 'game-tabs/match_detail.php');
        add_submenu_page('game-tabs/configuration.php', __('Tournament', 'game-tabs'), __('Tournament', 'game-tabs'), 'game-tabs', 'game-tabs/tournament_detail.php');
        add_submenu_page('game-tabs/configuration.php', __('Tournament Details', 'game-tabs'), __('Tournament Details', 'game-tabs'), 'game-tabs', 'game-tabs/match_tour_detail.php'); 
		add_submenu_page('game-tabs/configuration.php', __('Team', 'game-tabs'), __('Team', 'game-tabs'), 'game-tabs', 'game-tabs/team_detail.php');
	}
} 
$role = get_role('administrator');
    if(!$role->has_cap('game-tabs')) {
        $role->add_cap('game-tabs');
    }
//define_varibale
function define_variable()
{
$tableName="wp_strommen_config"; 
$sql = "SELECT * FROM $tableName";
$config_data = mysql_query($sql);
$numberofconfig_data = mysql_num_rows($config_data);

   if($numberofconfig_data > 0){
    while($config_result = mysql_fetch_array($config_data)) {
         $$config_result['tag']=$config_result['value'];
         }
   }     
       if(!defined('MY_TEAM_GLOBAL'))
       {   
           define('MY_TEAM_GLOBAL',$MY_TEAM); 
       }
       if(!defined('WIDGET_HEADER_GLOBAL'))
       {
          define('WIDGET_HEADER_GLOBAL',$WIDGET_HEADER); 
       }
       if(!defined('WIDGET_WIDTH_GLOBAL'))
       {
          define('WIDGET_WIDTH_GLOBAL',$WIDGET_WIDTH); 
       }
       if(!defined('PAGE_WIDTH_GLOBAL'))
       {
          define('PAGE_WIDTH_GLOBAL',$PAGE_WIDTH); 
       }
       if(!defined('WIDGET_ROW_GLOBAL'))
       {
          define('WIDGET_ROW_GLOBAL',$WIDGET_ROW);  
       }
       if(!defined('SHOW_SISTE_GLOBAL'))
       {
          define('SHOW_SISTE_GLOBAL',$SHOW_SISTE); 
       }
       if(!defined('SHOW_NESTE_GLOBAL'))
       {
           define('SHOW_NESTE_GLOBAL',$SHOW_NESTE); 
       }
       if(!defined('SHOW_TABELL_GLOBAL'))
       {
           define('SHOW_TABELL_GLOBAL',$SHOW_TABELL); 
       }
       /*if(!defined('PAGE_ROW_GLOBAL'))
       {   
           define('PAGE_ROW_GLOBAL',$PAGE_ROW); 
       }*/
       if(!defined('TOURNAMENT_GLOBAL'))
       {   
           define('TOURNAMENT_GLOBAL',$TOURNAMENT);           
       }
       if(!defined('TEAM_GLOBAL'))
       {   
           define('TEAM_GLOBAL',$TEAM); 
       }
       if(!defined('MAIN_FEED_XML_GLOBAL'))
       {   
           define('MAIN_FEED_XML_GLOBAL',$MAIN_FEED_XML); 
       }
       if(!defined('GOOGLE_MAP_GLOBAL'))
       {
           define('GOOGLE_MAP_GLOBAL',$GOOGLE_MAP); 
       }
}

// sorting array 
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }
    
    return $new_array;
}

?>
