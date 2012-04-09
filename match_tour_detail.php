<?php
/**
 * General settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once('./admin.php');

if ( ! current_user_can( 'manage_options' ) )
    wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );

$title = __('Tournament Team Detail');
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
include('paging-function.php');  
?>
<link href="<?php echo plugins_url(); ?>/game-tabs/css/admin_paging.css" rel="stylesheet" type="text/css" />  
<?php
//fetch records from databse table;
$tableName="wp_strommen_tournament_matches";             
   if($_REQUEST['rowid'])
   {           
        $url = $_REQUEST['match_tour__url'];
        $sql = "UPDATE $tableName SET url = '$url' WHERE id = ".$_REQUEST['rowid'];        
        mysql_query($sql);
       ?>
       <script language="javascript" type="text/javascript">
       alert('"SUCCESS! URL Save successfully" ')
       </script>
       <?php
   }
// pagignation 
    $Path=$_SERVER['REQUEST_URI'];
    if($Path != "")
    {
        if(strpos($Path,"p=") > 0)
        {
            $Path=substr($Path,0,strpos($Path,"p=")-1);
        }
    } 
    $targetpage = $Path;     
    $limit = 20;
    $custom_query = "SELECT * FROM $tableName";       
    $arr_data = paging($tableName,$targetpage,$limit,$custom_query);
    $result = $arr_data[0];
    $paginate =  $arr_data[1];
    $row_count = mysql_num_rows($arr_data[0]);
/*$sql_sel = "SELECT * FROM wp_tournament_matches";
$result = mysql_query($sql_sel);*/
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>
<?php if ($row_count > 0) { ?>
<label><?php _e('URL field will link to "More info" in Tabell','MyLanguage');?></label>
<table border="1" >
<tr>
    <th><?php _e('Id','MyLanguage');?></th>  
    <th><?php _e('Tournament Name','MyLanguage');?></th>
    <?php /* ?> <th>Tournament Id</th> <?php */ ?>
    <th><?php _e('Team Name','MyLanguage');?></th>
    <th><?php _e('Org Element Id','MyLanguage');?></th>
    <th><?php _e('Local Council','MyLanguage');?></th>    
    <th><?php _e('No. Matches','MyLanguage');?></th>
    <th><?php _e('No. Wins','MyLanguage');?></th>
    <th><?php _e('No. Draws','MyLanguage');?></th>
    <th><?php _e('No. Losses','MyLanguage');?></th>
    <?php /* ?><th>Admin Id</th><?php */ ?>    
    <th><?php _e('No. Goals','MyLanguage');?></th>
    <th><?php _e('No. Against','MyLanguage');?></th>
    <th><?php _e('No. Diff','MyLanguage');?></th>
    <th><?php _e('Points','MyLanguage');?></th>
    <th><?php _e('URL','MyLanguage');?></th>
    <th><?php _e('Action','MyLanguage');?></th>
</tr>
<?php while($data = mysql_fetch_array($result)) {?>
<tr align="center">
     <form method="post" action="">          
    <td><?php echo $data['id']; ?></td>
    <td><?php echo $data['tournament_name']; ?></td>
    <?php /* ?><td><?php echo $data['tournament_id']; ?></td><?php */ ?>
    <td><?php echo $data['team_name']; ?></td>
    <td><?php echo $data['org_element_id']; ?></td>
    <td><?php echo $data['local_council']; ?></td>    
    <td><?php echo $data['no_matches']; ?></td>
    <td><?php echo $data['no_wins']; ?></td>
    <td><?php echo $data['no_draws']; ?></td>
    <td><?php echo $data['no_losses']; ?></td>
    <td><?php echo $data['no_goals']; ?></td> 
    <td><?php echo $data['no_against']; ?></td> 
    <td><?php echo $data['no_diff']; ?></td> 
    <td><?php echo $data['points']; ?></td> 
    <?php /* ?><td><?php echo $data['admin_id']; ?></td><?php */ ?>
    <td>
    <input type="text" id="match_tour_url" name="match_tour__url" value="<?php echo $data['url'] ; ?>" /> 
    <input type="hidden" id="rowid" name="rowid" value="<?php echo $data['id'] ; ?>" />    
    </td>
    <?php
    if($data['url']!='')
        $BtnValue = 'update';
    else
        $BtnValue = ' save ';
    ?>
    <td><input type="submit" id="save" name="save"  value="<?php echo $BtnValue;?>" /></td>      
     </form>     
</tr>
<?php
    }
?>
</table>
<?php } else {  _e('No Records','MyLanguage');  }?>  
<?php 
 echo $paginate;  
settings_fields('general'); ?>
</div>
<?php // include('./admin-footer.php') ?>