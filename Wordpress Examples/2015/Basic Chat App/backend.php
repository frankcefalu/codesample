<?php
// Create Connection
global $wpdb, $user_ID;

$state = sanitize_text_field( $_POST['state'] ).sanitize_text_field( $_GET['state'] );
$salt = "kingdomofknights2013";
$hash = md5($salt.$user_ID.$salt);

switch($state):

case "post":

$hash_get = sanitize_text_field ( $_POST['hash'] ). sanitize_text_field ( $_GET['hash'] );
$message = sanitize_text_field ( $_POST['message'] ).sanitize_text_field ( $_GET['message'] );

if ( !is_user_logged_in() ) { return false; }
if($hash != $hash_get){ return false; }

$wpdb->query(
    $wpdb->prepare("INSERT INTO avalonwp_messages (uid, message) VALUES (%d,%s)",$user_ID,$message)
);

return true;

break;


case "delete":

$hash_get = sanitize_text_field ( $_POST['hash'] );
$mid = sanitize_text_field ( $_POST['mid'] );

if ( !is_user_logged_in() ) { return false; }
if ( !current_user_can('edit_posts') ) { return false; }
if($hash != $hash_get){ return false; }

$wpdb->query(
    $wpdb->prepare("DELETE FROM avalonwp_messages WHERE mid=%d",$mid)
);


break;


case "fetch":

$hash_get = sanitize_text_field ( $_POST['hash'] );

//if($hash != $hash_get){ return false; }

$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM avalonwp_messages ORDER BY mid DESC LIMIT 1"));

for($i=0;$i<count($results);$i++):
  $output[$i] = (array) $results[$i];
  $user = get_user_by('id',$output[$i]['uid']);
  $output[$i]['username'] = $user->data->display_name;
  $output[$i]['avatar'] = get_avatar($output[$i]['uid'],50,null,null,array("class"=>"img-circle"));
  $date = date_create($output[$i]['date']);

  if (current_user_can('edit_posts') ) {  $output[$i]['admin'] = "true"; }
  if (!current_user_can('edit_posts') ) {  $output[$i]['admin'] = "false"; }

  $output[$i]['date'] = human_time_diff( date_format($date, 'U'), time() );
  unset($user);
  unset($date);
endfor;
print json_encode($output);
break;



endswitch;



?>
