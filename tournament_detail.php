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

$title = __('Tournament Detail');
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
$tableName="wp_strommen_tournaments";


//fetch records from databse table;             
   if($_REQUEST['rowid'])
   {           
        $url = $_REQUEST['tournaments_url'];
        $sql = "UPDATE $tableName SET url = '$url' WHERE id = ".$_REQUEST['rowid'];        
        mysql_query($sql);
       ?>
       <script language="javascript" type="text/javascript">
       alert('"SUCCESS! URL Save successfully" ')
       </script>
       <?php
   }     
// pagignation 
//$tableName="wp_tournaments"; 
    $Path=$_SERVER['REQUEST_URI'];
    if($Path != "")
    {
        if(strpos($Path,"p=") > 0)
        {
            $Path=substr($Path,0,strpos($Path,"p=")-1);
        }
    }       
    $targetpage = $Path;//"tournament_detail.php";     
    $limit = 20;
    $custom_query = "SELECT * FROM $tableName";
       
    $arr_data = paging($tableName,$targetpage,$limit,$custom_query);
    $result = $arr_data[0];
    $paginate =  $arr_data[1];
    $row_count = mysql_num_rows($arr_data[0]);  

/*$sql_sel = "SELECT * FROM wp_tournaments";
$result = mysql_query($sql_sel);*/                          //$data_sel = mysql_fetch_assoc($res_sel); //print_r($data_sel);//exit;        
                            
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<?php if ($row_count > 0) { ?>
<!-- <label>URL field will link to "More info" in in Tabell</label> -->
<table border="1" >
<tr>
    <th><?php _e('ID','MyLanguage');?></th>  
    <th><?php _e('Job Name','MyLanguage');?></th>
    <?php /* ?> <th>Tournament Id</th> <?php */ ?>
    <th><?php _e('Tournament Name','MyLanguage');?></th>
    <th><?php _e('Tournament Type','MyLanguage');?></th>
    <th><?php _e('Nation','MyLanguage');?></th>
    <th><?php _e('Gender','MyLanguage');?></th>
    <?php /* ?><th>Admin Id</th><?php */ ?>    
    <th><?php _e('URL','MyLanguage');?></th>
    <th><?php _e('Action','MyLanguage');?></th>
</tr>
<?php while($data = mysql_fetch_array($result)) {?>

<tr align="center">
    <form method="post" action="">          
    <td><?php echo $data['id']; ?></td>
    <td><?php echo $data['job_name']; ?></td>
    <?php /* ?><td><?php echo $data['tournament_id']; ?></td><?php */ ?>
    <td><?php echo $data['tournament_name']; ?></td>
    <td><?php echo $data['tournament_type']; ?></td>
    <td><?php echo $data['nation']; ?></td>
    <td><?php echo $data['gender']; ?></td>
    <?php /* ?><td><?php echo $data['admin_id']; ?></td><?php */ ?>
    <td>
        <input type="text" id="tournaments_url" name="tournaments_url" value="<?php echo $data['url'] ; ?>" /> 
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
