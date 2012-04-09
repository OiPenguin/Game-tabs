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
require_once('./admin.php');
  
if ( ! current_user_can( 'manage_options' ) )
	wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );   

$title = __('Team Detail');

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
include('paging-function.php');
//$dir = fn_to_get_plugin_directory();

?>

<link href="<?php echo plugins_url(); ?>/game-tabs/css/admin_paging.css" rel="stylesheet" type="text/css" />  
<?php
//fetch records from databse table;

$tableName="wp_strommen_team_master";

// update url code      
   if($_REQUEST['rowid'])
   {           
        $url = $_REQUEST['match_url'];
        $sql = "UPDATE $tableName SET url = '$url' WHERE id = ".$_REQUEST['rowid'];                
        mysql_query($sql);
?>
       <script language="javascript" type="text/javascript">
       alert('"SUCCESS! URL Save successfully" ')
       </script>
       <?php
   }     

   // pagignation 
   //$tableName="wp_strommen_team_master";    
   //$targetpage = get_admin_url()."match_detail.php";              
    $Path=$_SERVER['REQUEST_URI'];    
    if($Path != "")
    {
        if(strpos($Path,"p=") > 0)
        {
            $Path=substr($Path,0,strpos($Path,"p=")-1);
        }
    }
    //print $Path;
    //exit;
    
    $targetpage = $Path;//"match_detail.php";    
    $limit = 20;
    $custom_query = "SELECT * FROM $tableName";
	
    $arr_data = paging($tableName,$targetpage,$limit,$custom_query);	
    $result = $arr_data[0];    
    $paginate = $arr_data[1];
    $row_count = mysql_num_rows($arr_data[0]);	       
                            
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>
 
 <?php if ($row_count > 0) { ?>
<table border="1px">
<label><?php _e('URL field will link to the away team site in "Latest matches" and "Last five" ','MyLanguage');?></label>
<tr>
	<th><?php _e('Team ID','MyLanguage');?></th>
    <th><?php _e('Team Name','MyLanguage');?></th>    
    <th><?php _e('URL','MyLanguage');?></th>
    <th><?php _e('save','MyLanguage');?></th>
</tr>
   
<?php 
while($data = mysql_fetch_assoc($result)) {  
    ?>
    <tr align="center">
    <form action="" method="post">
   
    <td><?php echo $data['team_id']; ?></td>
    <td><?php echo $data['team_name']."(".$data['tournament_name'].")"; ?></td>
    <td>
    <input type="text" id="match_url" name="match_url" value="<?php echo $data['url'] ; ?>" size="50"  />
    <input type="hidden" id="rowid" name="rowid" value="<?php echo $data['id'] ; ?>" />
    </td>
    <?php
    if($data['url']!=NULL)
        $BtnValue = 'update';
    else
        $BtnValue = 'save';
    ?>
    <td><input type="submit" id="save" name="save"  value="<?php echo $BtnValue;?>" /></td>        
	</form>
</tr> 
<?php
    }
?>
</table>
<?php }else { _e('No Records','MyLanguage'); } ?>
<?php 
 echo $paginate;
settings_fields('general'); ?>
</div>