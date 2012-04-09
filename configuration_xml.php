<style type="text/css" >
.wrap table td select {
height: 125px !important;
}
</style>
<?php
  /*echo "test";
  exit;*/                         
/**
 * General settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
/** WordPress Administration Bootstrap */
//include_once('./admin.php');
//require_once('../../../wp-admin/admin.php'); 
//include_once('../../../wp-config.php');
//include_once('../../../wp-load.php');
//include_once('../../../wp-includes/wp-db.php');
include_once('custom_function.php');
include_once('xml_reader.php');   
/*require_once('custom_function.php');
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
//include('./admin-header.php');
// include file for pagin function
//$dir = fn_to_get_plugin_directory(); 
?>
<?php 
    if($_REQUEST['resetdb'])
    {
    reset_db();    
    }
            
    if($_REQUEST['save_main_feed'])
    {
        if(isset($_REQUEST['main_feed']) && $_REQUEST['main_feed']!='' )
        {
            checkxmlrequest_tournament($_REQUEST['main_feed']);
            $insert_query = "UPDATE wp_strommen_config SET value = '".$_REQUEST['main_feed']."' WHERE tag like 'MAIN_FEED_XML' "; 
            mysql_query($insert_query);
        }
        
    }
    
    if($_REQUEST['get_team'])
    {
          if($_REQUEST['right_tour'])
          {
             $tour_arr = array();
             $tour_arr = $_REQUEST['right_tour'];
             $ids ='';
             for($i=0;$i<count($tour_arr);$i++)
             {
                 if($i==count($tour_arr)-1)
                 $ids = $ids.$tour_arr[$i];
                 else
                 $ids = $ids.$tour_arr[$i] .",";  
             }
             
             // insert  in to config 
             $insert_query = "UPDATE wp_strommen_config SET value = '$ids' WHERE tag like 'TOURNAMENT' ";
             mysql_query($insert_query);
             ///
             
            $delete_query = "DELETE FROM wp_strommen_tournaments WHERE tournament_id NOT IN ($ids)";
            mysql_query($delete_query);
            
            $delete_query = "DELETE FROM wp_strommen_tournament_matches WHERE tournament_id NOT IN ($ids)";
            mysql_query($delete_query);
            
             
             $select_query = "SELECT * FROM wp_strommen_tournaments ";
             $result = mysql_query($select_query);
             $numberofrow = mysql_num_rows($result); 
             if($numberofrow > 0)
             {
                 while( $data = mysql_fetch_array($result))  {    
                 $tournamnet_id =  $data['tournament_id'];
                 checkxmlrequest_match($tournamnet_id);
                 checkxmlrequest_tournament_tables($tournamnet_id);
                 }
                 team_master();
             }                                        
          }                      
    }
    
    if($_REQUEST['get_match'])
    {
            
          if($_REQUEST['right_team'])
          {
             $team_arr = array();
             $team_arr = $_REQUEST['right_team'];
             $ids ='';
             for($i=0;$i<count($team_arr);$i++)
             {
                 if($i==count($team_arr)-1)
                 $ids = $ids.$team_arr[$i];
                 else
                 $ids = $ids.$team_arr[$i] .",";  
             }                                                    
             
             /*$delete_query = "DELETE FROM wp_strommen_team_master WHERE team_id NOT IN ($ids)"; mysql_query($delete_query); */        
                           
             // insert  in to config 
             $insert_query = "UPDATE wp_strommen_config SET value = '$ids' WHERE tag like 'TEAM' ";
             mysql_query($insert_query);
             /// 
          }
		if($_REQUEST['match_feed'])
        {
           $insert_query = "UPDATE wp_strommen_config SET value = '".$_REQUEST['match_feed']."' WHERE tag like 'MATCH_XML' "; 
           mysql_query($insert_query);
        }
        if($_REQUEST['table_feed'])
        {
           $insert_query = "UPDATE wp_strommen_config SET value = '".$_REQUEST['table_feed']."' WHERE tag like 'TABLE_XML' ";  
           mysql_query($insert_query);
        }
        
        $url = admin_url();
        $url = $url."/admin.php?page=game-tabs/configuration.php";
        ?>
        <script type="text/javascript"> 
        window.location.href='<?php echo $url; ?>';
        </script>
        <?php       
        
    } 
    
    if($_REQUEST['custom_config_but'])
{
    //DebugBreak();
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
        
       // $sql = "SELECT * FROM wp_strommen_team_master WHERE team_id IN (".TEAM_GLOBAL.")";                        
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
<script type="text/javascript" src="<?php echo plugins_url(); ?>/game-tabs/js/general.js"></script>
<?php
if(MAIN_FEED_XML_GLOBAL!='' || isset($_REQUEST['i']) ) { ?>
<?php ////////////////////////////////////////////////////////// step 1  ?>
<?php if( TOURNAMENT_GLOBAL == '') {?>

<?php if($_REQUEST['i']==0) {?>   
<div class="wrap">
<h2>Step 1</h2>
    <form action="" id="main_feed_form" name="main_feed_form" method="post">
    <label>Enter a URL For Fatching  Tournament Details : </label><br/>
    <input type="text" name="main_feed" id="main_feed" size="50" />
    <input type="submit" name="save_main_feed" id="save_main_feed" value="Get Data" onclick="return check_data('main_feed')" /><br/>
    <?php if(MAIN_FEED_XML_GLOBAL!='') {?>
    <label><i>last Entred url : <?php echo MAIN_FEED_XML_GLOBAL; ?></i></label>
    <?php } ?>
    <input type="hidden" name="i" id="i" value="1" />    
    </form>                                                   
</div>
<?php } ?>
<?php /////////////////////////////////////////////////////// step 2   ?>
<?php if($_REQUEST['i']==1) {?>
<div class="wrap">
<?php $tournaments_data = get_tournaments();  ?>
<h2>Step 2</h2>
<form action="" id="tour_form" name="tour_form" onsubmit="selectAllOptions(document.tour_form.right_tour)" method="post">
<label>Select Tournament which you want to display on front side : </label><br/>
    <table border="0" cellpadding="0" cellspacing="0" width="26%">
          <tr>
              <td >
            <select name="left_tour" multiple="multiple" ondblclick="one2twoSingle('left_tour','right_tour');" size="5" id="left_tour" style="width: 250px;" >
            <?php for($i=0;$i<count($tournaments_data[0]);$i++)  { ?>
            <option value="<?php echo $tournaments_data[1][$i] ;?>" name="<?php echo $tournaments_data[0][$i] ; ?>" id="<?php echo $tournaments_data[1][$i] ;?>" ><?php echo $tournaments_data[0][$i] ; ?></option>
            <?php  } ?>
            </select>
            </td> 
<td>
<input name="add" onclick="one2two('left_tour', 'right_tour')" id="firstList" class="btn" value=" Add &gt;&gt;" type="button"><br>
<input name="remove" onclick="two2one('left_tour', 'right_tour')" id="firstList" class="btn" value=" &lt;&lt; Remove" type="button"></td>
<td>              
<td >
<select name="right_tour[]" multiple="multiple" ondblclick="two2oneSingle('left_tour','right_tour');" size="5" id="right_tour" style="width: 250px;" ></select></td> 
</table>
<br/>   
<input type="submit" name="get_team" id="get_team" value="Get Data" onclick="return check_data_list('right_tour')" />
<input type="hidden" name="i" id="i" value="2" />
</form>                                                   
</div>
<?php } ?>
<?php /////////////////////////////////////////////////////// step 3     ?>
<?php if($_REQUEST['i']==2) {?>
<div class="wrap">
<?php 
//$team_arr = get_team();  
$team_arr = get_team_with_tournament();
?>
<h2>Step 3</h2>
<label>Select your own team : </label><br/>
<form action="" id="team_form" name="team_form" onsubmit="selectAllOptions(document.team_form.right_team)" method="post">
    <table border="0" cellpadding="0" cellspacing="0" width="26%">
          <tr>
             <td >
            <select name="left_team" multiple="multiple" ondblclick="one2twoSingle('left_team','right_team');" size="5" id="left_team" style="width: 250px;" >
            <?php for($i=0;$i<count($team_arr[0]);$i++)  { ?>
            <option value="<?php echo $team_arr[1][$i] ;?>" name="<?php echo $team_arr[0][$i] ; ?>" id="<?php echo $team_arr[1][$i] ;?>" >
            <?php echo $team_arr[0][$i] ; ?>(<?php echo $team_arr[2][$i] ; ?>)
            </option>
            <?php  } ?>
            </select>
            </td> 
            <td>
<input name="add" onclick="one2two('left_team', 'right_team')" id="firstList" class="btn" value=" Add &gt;&gt;" type="button"><br>
<input name="remove" onclick="two2one('left_team', 'right_team')" id="firstList" class="btn" value=" &lt;&lt; Remove" type="button"></td>
<td>              
<td >
<select name="right_team[]" multiple="multiple" ondblclick="two2oneSingle('left_team','right_team');" size="5" id="right_team" style="width: 250px;" ></select></td> 
</table>
<br/>    

<?php 
$sql = "SELECT * FROM wp_strommen_config";
    $config_data = mysql_query($sql);
    while($config_result = mysql_fetch_array($config_data)) {
         $$config_result['tag']=$config_result['value'];
    }
?>
    <table>
    <tr>
    <td>MATCH</td><td><input type="text" name="match_feed" id="match_feed" size="100" value="<?php echo $MATCH_XML; ?>" /></td>
    </tr>
    <tr>
    <td>TABLE</td><td><input type="text" name="table_feed" id="table_feed" size="100" value="<?php echo $TABLE_XML; ?>" /></td>
    </tr>    
    <tr><td colspan="2"><br/></td></tr>
    </table>

<input type="submit" name="get_match" id="get_match" value="Save" onclick="return check_data_list('right_team')" />
<input type="hidden" name="last" id="last" value="1"> 
</form>                                                   
</div>
<?php }?>


<?php 
}else{ ?>
<div>
<br/><br/><br/><br/>
<?php    
    ///////// select tournament
    if(TEAM_GLOBAL!='') {
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
                $sql_myteam = "SELECT * FROM wp_strommen_team_master WHERE team_id IN (".TEAM_GLOBAL.") AND tournament_id = $tournamentID ";                
                $result_myteam = mysql_query($sql_myteam);
                $myteam_numberofrow = mysql_num_rows($result_myteam);
                if($myteam_numberofrow > 0)
                {  
                    $teamName = "";
                    
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
                                }
                                //echo  "<b>".$team_list."</b>" ;                                                                
                            }                            
                            //echo _e('and','MyLanguage')." <b>".$teamName."</b>"; 
                    }
                    
                }
                echo "<b>"._e('Setup','MyLanguage')." ".CHR($ch++)."</b> ";                
                echo _e('is displaying','MyLanguage')." <b>".$tournamentName."</b>";
                //echo _e('Your plugin is displaying','MyLanguage')." <b>".$tournamentName."</b>";
                //echo "<br />"
                echo _e('with','MyLanguage')." ";
                echo  "<b>".$team_list."</b> " ;
                echo _e('and','MyLanguage')." <b>".$teamName."</b>";
                echo "<br /><br />";                
                 ?>
                <form action="<?php echo $url ?>" name="custom_config_form" id="<?php echo $tournamentID ?>" method="post">
                <input type="submit" name="custom_config_but" id="custom_config_but" value="Remove Configuration" onclick="return doYouWantTo()">
                <input type="hidden" name="custom_config" id="custom_config" value="<?php echo $tournamentID ?>">
                </form>                
                <?php
        echo "<hr />";
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
<?php if(TEAM_GLOBAL!='' && $_REQUEST['new_config']!='1' ) { ?>
<div>
<br/>
<?php
$url = admin_url();
$url = $url."/admin.php?page=game-tabs/configuration_xml.php";
?>
<form action="<?php echo $url ?>" name="reset_db" id="reset_db" method="post">
<input type="submit" name="resetdb" id="resetdb" value="Remove All Configuration" onclick="return doYouWantToAll()">
<input type="hidden" name="new_config" id="new_config" value="1">
</form>
</div>
<?php }else{ 
$url = admin_url();
$url = $url."/admin.php?page=game-tabs/configuration.php";
?>
 <script type="text/javascript"> 
 window.location.href='<?php echo $url; ?>';
 </script> 
<?php } ?>

<?php } ?> 

<?php  } else {  
$url = admin_url();
$url = $url."/admin.php?page=game-tabs/configuration.php";
?>
<script type="text/javascript"> 
window.location.href='<?php echo $url; ?>';
</script>   
<?php } ?>
