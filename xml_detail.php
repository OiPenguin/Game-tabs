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

$title = __('XML Detail');

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

$tableName="wp_strommen_xml_feed";

// update url code      
   if($_REQUEST['rowid'])
   {           
        $url = $_REQUEST['xml_url'];
        $sql = "UPDATE $tableName SET xml_url = '$url' WHERE id = ".$_REQUEST['rowid'];                
        mysql_query($sql);
?>
       <script language="javascript" type="text/javascript">
       alert('"SUCCESS! URL Save successfully" ')
       </script>
       <?php
   }     

   // pagignation 
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
    
    $limit = 10;
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
<tr align="left">      
    <th>XML Name</th>
    <!--<th>XML Description </th>-->
    <th>XML url</th>
    <th>save</th>
</tr>
   
<?php 
while($data = mysql_fetch_array($result)) {      
    ?>
    <form method="post" action=" ">     
    <tr align="left">    
    
    <td><?php echo $data['xml_name']; ?><br/>
    <?php echo "(".$data['xml_description'].")"; ?>
    </td>
    <!--<td><?php echo $data['xml_description']; ?></td>-->     
    <td>
    <input type="text" id="xml_url" name="xml_url" value="<?php echo $data['xml_url'] ; ?>" size="50" />
    <input type="hidden" id="rowid" name="rowid" value="<?php echo $data['id'] ; ?>" />
    </td>
    <?php
    if($data['xml_url']!=NULL)
        $BtnValue = 'update';
    else
        $BtnValue = 'save';
    ?>
    <td><input type="submit" id="save" name="save"  value="<?php echo $BtnValue;?>" /></td>        
    
</tr>
    
    </form> 
    
<?php
    }
?>
</table>
<?php }else { echo "No Records" ; } ?>
<?php 
 echo $paginate;
settings_fields('general'); ?>
</div>