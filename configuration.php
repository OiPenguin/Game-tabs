<?php
/**
 * General settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
/** WordPress Administration Bootstrap 

*/
/*
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

require_once('./admin.php');  
require_once('custom_function.php');
if ( ! current_user_can( 'manage_options' ) )
	wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) ); 

$title = __('Configuration');                                                                  
$parent_file = 'options-general.php';
/* translators: date and time format for exact current time, mainly about timezones, see http://php.net/date */
$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
/*
Insert for submit button added by hetal
*/       

/**
 * Display JavaScript on the page.
 *
 * @package WordPress
 * @subpackage General_Settings_Panel
 */
function add_js() {
?>
<?php
}
add_filter('admin_head', 'add_js');
?>

<?php
$tableName="wp_strommen_config"; 

if($_REQUEST['config_save'])
{         
    include('save-configuration.php');
    ?>
    <script language="javascript" type="text/javascript">
       alert('Update successfully ! ')
       </script>
    <?php   
}

if($_REQUEST['resetdb'])
{
    reset_db();    
}

if($_REQUEST['custom_config_but'])
{
   
    if(isset($_REQUEST['custom_config']))
    {   $sql="";
        $tid="";        
        $tid = $_REQUEST['custom_config'];
        
        $sql1 = "DELETE FROM wp_strommen_matches WHERE match_tournament_id = $tid"; 
        mysql_query($sql1);
        $sql2 = "DELETE FROM wp_strommen_tournaments WHERE tournament_id = $tid"; 
        mysql_query($sql2);
        $sql3 = "DELETE FROM wp_strommen_tournament_matches WHERE tournament_id = $tid"; 
        mysql_query($sql3);
        
       
       if(TEAM_GLOBAL!='')                       
       {
        $sql = "SELECT * FROM wp_strommen_team_master WHERE tournament_id = $tid AND team_id IN (".TEAM_GLOBAL.")";                                
        $sql_result = mysql_query($sql);
        $numberofrow = mysql_num_rows($sql_result);        
      
        if($numberofrow > 0)
        {
             $sql4 = "DELETE FROM wp_strommen_team_master WHERE tournament_id = $tid" ; 
             mysql_query($sql4);
             $sql5 = "SELECT * FROM wp_strommen_team_master WHERE team_id IN (".TEAM_GLOBAL.")";
             $sql_result = mysql_query($sql5);
             $numberofrow = mysql_num_rows($sql_result);
             $tids ='';
             if($numberofrow > 0)
             {
                 while( $sqldata = mysql_fetch_array($sql_result)){
                     if($tids=='')
                            $tids = $sqldata['team_id'];
                        else
                            $tids .= ", ".$sqldata['team_id'];                     
                 }
                                     
             }
             $sql6 = "UPDATE wp_strommen_config SET value = '$tids' WHERE tag like 'TEAM' ";
             mysql_query($sql6);
        }
       } 
        $sql_result = '';
        $sql7 = "SELECT * FROM wp_strommen_tournaments";
        $sql_result = mysql_query($sql7);
        $numberofrow = mysql_num_rows($sql_result);
        
        if($numberofrow > 0)
        {
            $tids ='';
            while( $sqldata = mysql_fetch_array($sql_result)){
            if($tids=='')
                $tids = $sqldata['tournament_id'];
            else
                $tids .= ", ".$sqldata['tournament_id'];                     
            }
                    
        }
        $sql8 = "UPDATE wp_strommen_config SET value = '$tids' WHERE tag like 'TOURNAMENT' ";
        mysql_query($sql8);
        
        $url = admin_url();
        $url = $url."/admin.php?page=game-tabs/configuration.php";
        ?>
        <script type="text/javascript"> 
        window.location.href='<?php echo $url; ?>';
        </script>
        <?php
        
    }   
}
?>
<?php 
$sql = "SELECT * FROM $tableName";
$config_data = mysql_query($sql);
while($config_result = mysql_fetch_array($config_data)) {
     $$config_result['tag']=$config_result['value'];     
}   
?>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/game-tabs/js/general.js"></script>       
<div class="wrap">
<?php screen_icon();
?>
<h2><?php echo esc_html( $title ); ?></h2>
<form id="" action="" method="post">
<table>
<tr>
<td><?php _e('Widget Header','MyLanguage');?></td><td><input type="text" name="widget_header" id="widget_header" value="<?php print $WIDGET_HEADER; ?>" /></td>
</tr>  
<tr>
<td><?php _e('Widget Width','MyLanguage');?></td><td><input type="text" name="widget_width" id="widget_width" value="<?php print $WIDGET_WIDTH; ?>" />px (fluid 100% if left empty)</td>
</tr> 
<tr>
<td><?php _e('Page Width','MyLanguage'); ?></td><td><input type="text" name="page_width" id="page_width" value="<?php print $PAGE_WIDTH; ?>" />px (fluid 100% if left empty)</td>
</tr>
<tr>
<td><?php _e('Number Of row in Widget','MyLanguage'); ?></td><td><input type="text" name="widget_row" id="widget_row" value="<?php print $WIDGET_ROW; ?>" /></td>
</tr>         
<tr>
<?php
    if($SHOW_SISTE==1)
       $chk = 'checked';     
    else
       $chk = '';     
?>
<td><?php _e(' Show Latest matches','MyLanguage'); ?></td><td><input type="checkbox" id="show_siste" name="show_siste" <?php echo $chk ?> value="1"/></td>
</tr> 
<tr>
<?php     
    if($SHOW_NESTE==1)
       $chk = 'checked';     
    else
       $chk = '';     
?>
<td><?php _e('Last five','MyLanguage'); ?></td><td><input type="checkbox" id="show_neste" name="show_neste" <?php echo $chk ?> value="1"/></td>
</tr>  
<tr>
<?php
    if($SHOW_TABELL==1)
       $chk = 'checked';     
    else
       $chk = '';     
?>
<td><?php _e('Show Table','MyLanguage'); ?></td><td><input type="checkbox" id="show_tabell" name="show_tabell" <?php echo $chk ?> value="1" /></td>
</tr>

<tr>
<?php
    
    if($GOOGLE_MAP==1)
       $chk = 'checked';     
    else
       $chk = '';     
?>
<td><?php _e('Tick to activate automatic link of Sted(bane) to Google maps','MyLanguage'); ?>&nbsp</td><td><input type="checkbox" id="google_map" name="google_map" <?php echo $chk ?> value="1" /></td>
</tr>
</table>
<br/>
<input type="submit" id="config_save" name="config_save" value="Update"> 
</form> 
 <?php 
settings_fields('general'); ?>
</div>
<?php ///////////////////////////////////////////////////////////////////////     ?>
<br/>
<div>
<?php    
    ///////// select tournament    
    if(TOURNAMENT_GLOBAL!='') {
    $sql_tournament = "SELECT * FROM wp_strommen_tournaments";
    $result_tournament = mysql_query($sql_tournament);
    $tournament_numberofrow = mysql_num_rows($result_tournament);
    if($tournament_numberofrow > 0)
    {
        $tournamentName ='';
        $ch = 65 ;
        echo "<hr />";
         while( $data_tournament = mysql_fetch_array($result_tournament))  { 
                
                $tournamentName = $data_tournament['tournament_name'];
                $tournamentID = $data_tournament['tournament_id'];
                /*******************/
                if(TEAM_GLOBAL!='')
                {
                $sql_myteam = "SELECT * FROM wp_strommen_team_master WHERE team_id IN (".TEAM_GLOBAL.") AND tournament_id = $tournamentID ";                
                $result_myteam = mysql_query($sql_myteam);
                $myteam_numberofrow = mysql_num_rows($result_myteam);
                $teamName = "";
                if($myteam_numberofrow > 0)
                {
                 while($data_team = mysql_fetch_array($result_myteam))
                    {       
                        if($teamName=='')
                            $teamName = $data_team['team_name'];
                        else
                            $teamName .= ", ".$data_team['team_name'];
                            
                        if($teamId=='')    
                           $teamId = $data_team['team_id'];
                        else
                            $teamId .= ", ".$data_team['team_id'];                           
                           
                           $team_list = "";
                            /*******************/
                           // echo _e('Your plugin is displaying','MyLanguage')." <b>".$tournamentName."</b>";
                           // echo _e('with','MyLanguage')." ";                            
                            $sql_team = "SELECT * FROM wp_strommen_team_master WHERE team_id NOT IN (".$teamId.") AND tournament_id = $tournamentID ";                            
                            $result_team = mysql_query($sql_team);
                            $team_numberofrow = mysql_num_rows($result_team);
                            if($team_numberofrow > 0)
                            {
                             $k=0 ;
                                while( $data_team = mysql_fetch_array($result_team))  
                                {
                                       $k++;                                               
                                       //if($k==$team_numberofrow)
                                       if($team_list=='')
                                        $team_list = $data_team['team_name'];
                                       else
                                        $team_list .= ", ".$data_team['team_name'];
                                }//echo  "<b>".$team_list."</b>" ; 
                            } //echo _e('and','MyLanguage')." <b>".$teamName."</b>";                            
                            
                    }
                    
                }
                }
                if($teamName!=''){                
                echo "<b>"._e('Setup','MyLanguage')." ".CHR($ch++)."</b> ";                
                echo _e('is displaying','MyLanguage')." <b>".$tournamentName."</b>";
                
                echo _e('with','MyLanguage')." ";
                echo  "<b>".$team_list."</b> " ;
                echo _e('and','MyLanguage')." <b>".$teamName."</b>";
                echo "<br /><br />";                
                }else
                {
                 echo "<b>"._e('Setup','MyLanguage')." ".CHR($ch++)."</b> ";
                 echo _e('have not select any team to display','MyLanguage');
                 echo "  in <b> ".$tournamentName."</b>";
                 echo "<br /><br />";
                }
                 ?>
                <form action="<?php echo $url ?>" name="custom_config_form" id="<?php echo $tournamentID ?>" method="post">
                <input type="submit" name="custom_config_but" id="custom_config_but" value="Remove Configuration" onclick="return doYouWantTo()">
                <input type="hidden" name="custom_config" id="custom_config" value="<?php echo $tournamentID ?>">
                </form>                
                <?php                
                echo "<hr />";
               // }
        }
    }
    
    
    /*$sql_tournament = "SELECT * FROM wp_strommen_tournaments";
    $result_tournament = mysql_query($sql_tournament);
    $numberofrow = mysql_num_rows($result_tournament);
    if($numberofrow > 0)
    {   $l=0 ; 
        ?>
        <?php _e('Your plugin is displaying','MyLanguage'); ?>
        <?php
        while( $data_tournament = mysql_fetch_array($result_tournament))  {   
            $l++;
            if($l==$numberofrow)  
            $tournament_list = $tournament_list.$data_tournament['tournament_name'];
            else
            $tournament_list = $tournament_list.$data_tournament['tournament_name'].", ";
        }
        echo  "<b>".$tournament_list."</b>" ;
    }
    ///////// select team from master
    $sql_team = "SELECT * FROM wp_strommen_team_master WHERE team_id NOT IN (".TEAM_GLOBAL.")";
    $result_team = mysql_query($sql_team);
    $numberofrow = mysql_num_rows($result_team);
    if($numberofrow > 0)
    {   $k=0 ;
        ?>
        <?php _e('with','MyLanguage'); ?>        
        <?php
        while( $data_team = mysql_fetch_array($result_team))  {
               $k++;                                               
              if($k==$numberofrow)
              $team_list = $team_list.$data_team['team_name'];
              else
              $team_list = $team_list.$data_team['team_name'].", ";
        }
        echo  "<b>".$team_list."</b>" ;
    }
    //////// select my how team
       
    $sql_myteam = "SELECT * FROM wp_strommen_team_master WHERE team_id IN (".TEAM_GLOBAL.")";        
    $result_myteam = mysql_query($sql_myteam);
    $numberofrow = mysql_num_rows($result_myteam);
    if($numberofrow > 0)
    { $j=0;?>
    <?php _e('and','MyLanguage'); ?>
     <?php
        while( $data_myteam = mysql_fetch_array($result_myteam))  {   
            $j++;
            if($j==$numberofrow)  
            $team_mylist = $team_mylist.$data_myteam['team_name'];
            else
            $team_mylist = $team_mylist.$data_myteam['team_name'].", ";
            
        }
        echo  "<b>".$team_mylist."</b>" ;
    }*/
     } 
?>
</div>
<?php if(TOURNAMENT_GLOBAL!='' && $_REQUEST['new_config']!='1' ) { ?>
<div>
<br/>
<?php
$url = admin_url();
$url = $url."/admin.php?page=game-tabs/configuration.php"; 

?>
<form action="<?php echo $url ?>" name="reset_db" id="reset_db" method="post">
<input type="submit" name="resetdb" id="resetdb" value="Remove All Configuration" onclick="return doYouWantToAll()">
<input type="hidden" name="new_config" id="new_config" value="1">
</form>
</div>
<?php }else{ ?>
 <div class="wrap">
<?php
$url = admin_url();
$url = $url."/admin.php?page=game-tabs/configuration_xml.php&i=0";     
?>
<a href="<?php echo $url ?>" >Please set up XML configuration </a>
<br/>
</div>

<?php } ?>
