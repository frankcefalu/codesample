<?php

module_load_include('inc', 'cls_sso', 'cls_sso.passcode');
module_load_include('inc', 'cls_sso', 'cls_sso.ldap');

/**
* Implements hook_menu().
*/
function cls_sso_menu() {
  $items = array();

  $items['sso/cas/process-user'] = array(
    'title' => 'Process CAS User',
    'page callback' => 'cls_sso_process_ldap_user',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
    'file' => 'cls_sso.ldap.inc',
  );

  $items['sso/non-cas/process-user'] = array(
    'title' => 'Process and Redirect WIND User',
    'page callback' => 'cls_sso_process_non_cas_user',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
    'file' => 'cls_sso.ldap.inc',
  );

  $items['sso/login/select'] = array(
    'title' => 'Login',
    'page callback' => 'cls_sso_display_login_choice',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
  );

  $items['sso/passcode-login'] = array(
    'title' => 'Login',
    'page callback' => 'cls_sso_passcode_login_page',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
    'file' => 'cls_sso.passcode.inc',
  );

  $items['sso/login-via-passcode'] = array(
    'title' => 'Login via Passcode',
    'page callback' => 'cls_sso_login_via_passcode',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
    'file' => 'cls_sso.passcode.inc',
  );

  $items['sso/login-via-wind'] = array(
    'title' => 'Login via WIND',
    'page callback' => 'cls_sso_login_via_wind',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
  );

  $items['sso/404'] = array(
    'title' => 'Page not found.',
    'page callback' => 'cls_sso_display_page_not_found',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
  );

  $items['sso/403'] = array(
    'title' => 'Access denied.',
    'page callback' => 'cls_sso_display_access_denied',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
  );

  $items['sso/account-not-set-up'] = array(
    'title' => 'Account not set up.',
    'page callback' => 'cls_sso_display_account_not_set_up',
    'page arguments' => array(2),
    'type' => MENU_SUGGESTED_ITEM,
    'access callback' => TRUE,
  );

  $items['admin/config/people/sso/path/add'] = array(
    'title' => 'Create a new passcode login page',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('cls_sso_passcode_page_form'),
    'type' => MENU_LOCAL_ACTION,
    'tab_parent' => 'admin/config/people/sso',
    'tab_root' => 'admin/config/people/sso',
    'file' => 'cls_sso.passcode.admin.inc',
    'access callback' => 'user_access',
    'access arguments' => array('edit guest passcode'),
  );
  
  $items['admin/config/people/sso/path/%/edit'] = array(
    'title' => 'Edit a passcode login page',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('cls_sso_passcode_page_form', 5),
    'type' => MENU_NORMAL_ITEM,
    'file' => 'cls_sso.passcode.admin.inc',
    'access callback' => 'user_access',
    'access arguments' => array('edit guest passcode'),
  );

  $items['sso/login'] = array(
    'title' => 'Process destination data and send user to the right login page',
    'page callback' => 'cls_sso_login_redirect',
    'page arguments' => array(2),
    'type' => MENU_CALLBACK,
    'access callback' => TRUE,
  );
  
  $items['sso/ldap-test'] = array(
    'title' => 'LDAP Test',
    'page callback' => 'cls_sso_ldap_test',
    'page arguments' => array(2),
    'type' => MENU_CALLBACK,
    'access callback' => TRUE,
  );

  return $items;
}

function cls_sso_ldap_test() {
  
  $cls_ldap_server = PANTHEON_SOIP_CLS_LDAP; //variable_get('cls_sso.config.cls_ldap_server', '');
  echo "PANTHEON_SOIP_CLS_LDAP constant :" . $cls_ldap_server;
  $cls_ldap_dn = variable_get('cls_sso.config.cls_ldap_dn', '');
  echo "CLS LDAP DN: " . $cls_ldap_dn;
  $cls_ldap_user = variable_get('cls_sso.config.cls_ldap_user', '');
  echo "CLS LDAP user: " . $cls_ldap_user;
  $cls_ldap_password = variable_get('cls_sso.config.cls_ldap_password', '');
  echo "CLS LDAP password: " . $cls_ldap_password;
  $ldap_info = cls_sso_get_ldap_info($cls_ldap_server, $cls_ldap_dn, $cls_ldap_user, $cls_ldap_password, "atkach");
  print_r($ldap_info);
	
//  $settings = array(
//      'hostname' => PANTHEON_SOIP_CLS_LDAP,//'clsauth.law.columbia.edu',
//      'port' => '',
//      'bind_rdn' => 'ldaps',
//      'bind_password' => 'ldapsearch',
//      'base_dn' => 'ou=facstaff,dc=law,dc=columbia,dc=edu',
//      'attributes' => array(
//        'accountexpires',
//        'title',
//        'department',
//        'initials',
//        'sn',
//        'givenname',
//        'mail',
//        'employeeid',
//        'memberof',
//        'usnchanged'
//      ),
//  );
//
//  //echo 'LDAPTLS_CERT=' . getenv('LDAPTLS_CERT') . PHP_EOL;
//  //if (getenv('LDAPTLS_CERT')) {
//  //  echo '  hash: ' . exec('openssl x509 -noout -hash -in ' . getenv('LDAPTLS_CERT')) . PHP_EOL;
//  //}
//
//  echo 'LDAPTLS_CACERT=' . getenv('LDAPTLS_CACERT') . "<br />";
//
//  if (getenv('LDAPTLS_CACERT')) {
//    echo '  hash: ' . exec('openssl x509 -noout -hash -in ' . getenv('LDAPTLS_CACERT')) . "<br />";
//  }
//
//  //echo 'LDAPTLS_CACERTDIR=' . getenv('LDAPTLS_CACERTDIR') . PHP_EOL;
//  //echo 'LDAPTLS_REQCERT=' . getenv('LDAPTLS_REQCERT') . PHP_EOL;
//  echo "Attempting to connect to {$setting['hostname']} <br />";
//
//  $link_identifier = ldap_connect($settings['hostname'], '636');
//  if (!$link_identifier) {
//    echo 'Unable to connect - ' . ldap_error($link_identifier) . '<br />';
//  }
//  else {
//    echo 'Connected.' . '<br />';
//  }
//
//  ldap_set_option($link_identifier, LDAP_OPT_PROTOCOL_VERSION, 3);
//  ldap_set_option($link_identifier, LDAP_OPT_REFERRALS, 0);
//
//  echo "Attempting to bind with rdn {$settings['bind_rdn']} and password {$settings['bind_password']} <br />";
//  if (!ldap_bind($link_identifier, $settings['bind_rdn'], $settings['bind_password'])) {
//    echo 'Unable to bind - ' . ldap_error($link_identifier) . '<br />';
//    ldap_unbind($link_identifier);
//  }
//  else {
//    echo 'Bind succeeded. <br />';
//  }
//
//  echo "Attempting to search with base_dn {$settings['base_dn']}, filter {$setting['filter']} and attributes " . var_export($settings['attributes'], TRUE) . '<br />';
//  $search_result_identifier = ldap_search($link_identifier, $settings['base_dn'], 'cn=atkach', $settings['attributes'], 0, 1000);
//  if (!$search_result_identifier) {
//    echo 'Unable to search - ' . ldap_error($link_identifier) . '<br />';
//    ldap_unbind($link_identifier);
//    continue;
//  }
//  echo 'Search succeeded. <br />';
//
//  $entries = ldap_get_entries($link_identifier, $search_result_identifier);
//  var_dump($entries);
}

/**
* Implements hook_user_update().
*/
function cls_sso_user_update(&$edit, $account, $category) {
//  global $user;
//  if (isset($account->name) && $account->name && isset($user->name) && $user->name) {
//    watchdog(
//      'user',
//      t('User account @account updated successfully by @user.',
//        array(
//          '@account' => format_username($account),
//          '@user' => format_username($user)
//        )
//      )
//    );
//  }
}

/**
* Implements hook_user_insert().
*/
function cls_sso_user_insert(&$edit, $account, $category) {
  if (isset($account->name) && $account->name) {
    watchdog(
      'user',
      t('User account @account created successfully.',
        array('@account' => format_username($account))
      )
    );
  }
}

/**
* Implements hook_permission().
*/
function cls_sso_permission() {
  return array(
    'edit guest passcode' => array(
      'title' => t('Edit guest passcode')
    )
  );
}

/**
 * Implements hook_preprocess_page().
 */
function cls_sso_preprocess_page(&$vars) {
  $current_path = current_path();
  $sso_paths = array(
    "user",
    "sso/login",
    "sso/login/select",
    "sso/passcode-login",
    "sso/403",
    "sso/404",
    "sso/account-not-set-up"
  );
  if (in_array($current_path, $sso_paths)) {
    $vars['page']['sidebar_first'] = "";
    $vars['page']['sidebar_second'] = "";
    $vars['page']['banner'] = "";
  }
}

/**
 * Implements hook_theme().
 */
function cls_sso_theme($existing, $type, $theme, $path) {
  global $base_url;
  $destination = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_STRING);
  $service = variable_get('wind_service', 'law-web');
  $wind_login_url = (variable_get('wind|sandbox_mode|enable')) ? variable_get('wind|wind_service|sandbox_login_url') : variable_get('wind|wind_service|login_url');
  return array(
    'login' => array(
      'variables' => array(
        'service' => $service,
        'wind_login_url' => $wind_login_url,
        'destination' => $destination,
        'base_url' => $base_url,
      ),
      'template' => 'templates/login',
    ),
    'login_passcode' => array(
      'variables' => array(
        'destination' => $destination,
        'base_url' => $base_url,
        'login_page_title' => 'Guest Login',
        'login_page_body' => NULL,
        'login_page_having_trouble_url' => 'mailto:webadmin@law.columbia.edu',
        'form' => NULL,
      ),
      'template' => 'templates/login_passcode',
    ),
    'duplicate_uni_email' => array(
      'variables' => array(
        'uid' => '',
        'uni' => '',
      ),
      'template' => 'templates/duplicate_uni_email',
    ),
  );
}

/**
 * Implements hook_form_alter().
 */
function cls_sso_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == "user_profile_form") {
    if (!user_access('edit guest passcode')) {
      $form["cls_sso_passcode"]['und'][0]['value']['#access'] = FALSE;
    }
  }
}

/**
 * Implements hook_theme_registry_alter().
 */
function cls_sso_theme_registry_alter(&$theme_registry) {
  $theme_registry['user_profile']['template'] = 'sites/all/modules/custom/cls_sso/templates/sso-user-profile';
}

/**
* Returns destination parameter from URL. If it is not present, returns current path.
*/
function cls_sso_get_destination() {
  $destination = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_STRING);
  $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
  $current_path = request_path();
  $current_path = ltrim($current_path, '/');
  $current_path_arr = explode("?", $current_path);
  $current_path = $current_path_arr[0];
  $current_path = $destination ? $destination : ($current_path ? $current_path : $q);
  if (in_array($current_path, array("sso/login", "sso/login/select", "sso/passcode-login", "sso/cas/process-user"))) {
    $current_path = "";
  }
  return $current_path;
}

/**
 * Overrides default 403 page.
 * Determines login page based on the user's destination and current login state, and redirects him/her there.
 */
function cls_sso_login_redirect() {
  global $user;
  global $base_url;
  $destination = cls_sso_get_destination();
  $login_page = cls_sso_get_login_page($destination);
  if (module_exists('wind')) {
    $login_state = cls_sso_get_login_state();
  } else {
    $logged_in = cls_sso_check_login();
  }
  $login_page = (isset($_GET['force_sso']) && $_GET['force_sso'] == '1')?'sso/cas/process-user':$login_page;
  
  //Used to redirect user after successful authentication
  $_SESSION['destination_url'] = $destination;
  if (module_exists('wind')) {
    if ($login_state['logged_in']) {//User is logged in
      //Default behavior.
    } else if (!$login_state['ticket_id']){//User does not have a WIND ticket
      if (in_array($login_page, array("sso/cas/process-user"))) {
        //Login through CAS SSO
        header('Location: ' . url($login_page, array('query' => array('destination' => "sso/cas/process-user"), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("sso/passcode-login"))){
        //Login with passcode
        header('Location: ' . url($login_page, array('query' => array('destination' => drupal_get_path_alias($destination)), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("admissions/jd/admitted/login"))){
        //Login with passcode
        header('Location: ' . url($login_page, array('query' => array('destination' => drupal_get_path_alias($destination)), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("admissions/graduate-legal-studies/admitted/login"))){
        //Login with passcode
        header('Location: ' . url($login_page, array('query' => array('destination' => drupal_get_path_alias($destination)), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("sso/login-via-wind"))){
        //Login with passcode
        header('Location: ' . url($login_page, array('absolute' => FALSE, 'alias' => TRUE)));
      }
    } else if ($login_state['authorized']){//User is authorized
      //Send WIND user to destination
      header('Location: ' . url($destination, array('query' => array(), 'absolute' => FALSE, 'alias' => TRUE)));
    } else {//User has a ticket, but is not authorized
      //Default behavior. User is sent to "Access Denied" page.
    }
  } else {
    if (!$logged_in) {
      if (in_array($login_page, array("sso/cas/process-user"))) {
        //Login through CAS SSO
        header('Location: ' . url($login_page, array('query' => array('destination' => "sso/cas/process-user"), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("sso/passcode-login"))) {
        //Login with passcode
        header('Location: ' . url($login_page, array('query' => array('destination' => drupal_get_path_alias($destination)), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("admissions/jd/admitted/login"))) {
        //Login with passcode
        header('Location: ' . url($login_page, array('query' => array('destination' => drupal_get_path_alias($destination)), 'absolute' => FALSE, 'alias' => TRUE)));
      }
      else if (in_array($login_page, array("admissions/graduate-legal-studies/admitted/login"))) {
        //Login with passcode
        header('Location: ' . url($login_page, array('query' => array('destination' => drupal_get_path_alias($destination)), 'absolute' => FALSE, 'alias' => TRUE)));
      }
    }
  }
  
  if ($user->uid) {
    return cls_sso_display_access_denied();
  }
  
  return;
}

/**
 * Implements hook_user_logout().
 *
 * Logs out authenticated user from CAS, WIND or both.
 */
function cls_sso_user_logout() {
  global $user;
  global $base_url;
  $wind_redirect = "";
  $destination_url = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $base_url;
  //To avoid "Access Denied" message, user will be redirected to login page after logging out from user/* pages
  if (in_array($destination_url,
    array(
      "sso/cas/process-user",
      "$base_url/sso/cas/process-user",
      "user",
      "$base_url/user",
      "user/" . $user->uid,
      "$base_url/user/" . $user->uid
    )
  )) {
    $destination_url = $base_url;
  }
  if (isset($_SESSION['wind']['ticketid'])) {
    //WIND logout
    $wind_logout_url = (variable_get('wind|sandbox_mode|enable')) ? variable_get('wind|wind_service|sandbox_logout_url') : variable_get('wind|wind_service|logout_url');
    $service = variable_get('wind_service', 'law-web');
    $wind_redirect = "$wind_logout_url?service=$service&destination=$destination_url&destinationtext=Back%20to%20Columbia%20Law%School&passthrough=1";
  }
  session_destroy();
  module_invoke_all('user', 'logout', NULL, $user);
  $user = drupal_anonymous_user();
  //CAS logout
  cas_phpcas_init();
  $logout_url = phpCAS::getServerLogoutURL();
  //If user was logged in using both WIND and CAS, he/she will be redirected to WIND logout after CAS logout.
  //Otherwise, he/she will be redirected to destination.
  $destination_url = $wind_redirect ? $wind_redirect : $destination_url;
  $_SESSION['destination_url'] = $destination_url;
  $options = array('query' => array('service' => $destination_url));
  drupal_goto($logout_url, $options);
}

/**
* Determines which login page the user needs to be redirected to. 
* Provides output to cls_sso_login_redirect() function.
* 
* @param $destination - where the user is heading
* 
* @return $login_page - Log in URL
*/
function cls_sso_get_login_page($destination) {
  $passcode_login = cls_sso_check_path_for_passcode_page($destination);
  if ($passcode_login) {
    $login_page = "sso/passcode-login";
  } else {
    $login_page = "sso/cas/process-user"; 
  }

  if (preg_match('/^admissions\/jd\/admitted/', $destination)) {
    $login_page = "admissions/jd/admitted/login";
  }

  if (preg_match('/^admissions\/graduate-legal-studies\/admitted/', $destination)) {
    $login_page = "admissions/graduate-legal-studies/admitted/login";
  }

  if (preg_match('/^alumni\/reunion\/class\-photos/', $destination)) {
    $login_page = "sso/login-via-wind";
  }

  if (preg_match('/^career\-services\/alumni/', $destination)) {
    $login_page = "sso/login-via-wind";
  }

  return $login_page;
}

/**
* Determines if user if authenticated or if he/she needs to log in.
*
* @return array $access_vars - array of variables indicating if user is logged in and authorized to access the current page. 
* @return bool $access_vars['logged_in'] - TRUE or FALSE. Shows if user is logged in
* @return bool $access_vars['ticket_id'] - TRUE or FALSE. Shows if a ticket ID currently exists 
* @return bool $access_vars['authorized'] - TRUE or FALSE. Shows if a user with a ticket is authorized 
*/
function cls_sso_get_login_state() {

  $ticket_id = FALSE;
  $authorized = FALSE;
  $logged_in = FALSE;

  if (!cls_sso_check_login()) {
    if (isset($_GET['ticketid']) && $_GET['ticketid']) {
      $ticket_id = TRUE;
      $path = drupal_get_path('module', 'wind');
      $service = variable_get('wind_service', 'law-web');
      $windSecuritySettings = array(
        'windService' => $service,
        'windExpirationPeriod' => 14400,
        'windTimeoutPeriod' => 14400,
      );
      require_once $path . '/WindSecurityManager.class.inc';
      $wind = new WindSecurityManager($windSecuritySettings);
      if (!$wind->isAuthorized()) {
        $authorized = FALSE;
      } else {
        //If user is successfully authenticated, set cookie to "alumni" and send user to destination
        $authorized = TRUE;
      }
    } else {
      //If there is no ticket ID, display login choice window
      $ticket_id = FALSE;
    }
  } else {
	$logged_in = TRUE;
  }
      
  return array('logged_in' => $logged_in, 'ticket_id' => $ticket_id, 'authorized' => $authorized);
}

/**
* Displays login selection.
*
* @return $html - string containing login window layout
*/
function cls_sso_display_login_choice() {
  global $base_url;
  $destination = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_STRING);
  $destination = !in_array($destination, array('user/logout', 'sso/login', 'sso/login/select', 'sso/passcode-login', 'sso/cas/process-user', 'sso/403', 'sso/404', 'sso/account-not-set-up')) ? $destination : "";
  $destination = !in_array($destination, array("$base_url/user/logout", "$base_url/sso/login", "$base_url/sso/login/select", "$base_url/sso/passcode-login", "$base_url/sso/cas/process-user", "$base_url/sso/403", "$base_url/sso/404")) ? $destination : "";
  $_SESSION['destination_url'] = $destination; //Used by CAS
  $_SESSION['wind_destination_path'] = $destination; //Used by WIND
  drupal_add_css(drupal_get_path('module', 'cls_sso') . '/css/login.css');
  $passcode_login = cls_sso_check_path_for_passcode_page($path);
  if ($passcode_login && !strstr($_SERVER['HTTP_REFERER'], '/sso/passcode-login')) {
    drupal_goto('sso/passcode-login', array('query' => array('destination' => $destination), 'absolute' => FALSE, 'alias' => TRUE));
  } else {
    return theme('login', array('base_url' => $base_url, 'destination' => $destination));
  }
}

/**
* Redirects user to WIND login page.
* If destination is in $_SESSION['destination_url'], it will be written to the URL.
*
*/
function cls_sso_login_via_wind() {
  global $base_url;
  $secure_base_url = str_replace('http:', 'https:', $base_url);
  $destination = isset($_SESSION['destination_url']) ? $_SESSION['destination_url'] : "";
  $destination = !in_array($destination, array('user/logout', 'sso/login', 'sso/login/select', 'sso/passcode-login', 'sso/cas/process-user', 'sso/403', 'sso/404', 'sso/account-not-set-up')) ? $destination : "";
  $destination = !in_array($destination, array("$base_url/user/logout", "$base_url/sso/login", "$base_url/sso/login/select", "$base_url/sso/passcode-login", "$base_url/sso/cas/process-user", "$base_url/sso/403", "$base_url/sso/404")) ? $destination : "";
  $service = variable_get('wind_service', 'law-web');
  $wind_login_url = (variable_get('wind|sandbox_mode|enable')) ? variable_get('wind|wind_service|sandbox_login_url') : variable_get('wind|wind_service|login_url');
  drupal_goto($wind_login_url, array('query' => array('service' => $service, 'destination' => "$secure_base_url/user/wind?wind_destination_path=$secure_base_url/$destination"), 'absolute' => FALSE, 'alias' => TRUE));
}

/**
* Displays custom page not found message.
*
* @return $html - string containing html template
*/
function cls_sso_display_page_not_found() {
  return "<h4>Requested page is not found.</h4>";
}

/**
* Displays custom access denied message.
*
* @return $html - string containing html template
*/
function cls_sso_display_access_denied() {
  global $user;
  if (strstr(request_path(), "admissions/jd/admitted") !== FALSE) {
    header('Location: ' . url('admissions/jd/my-columbia-law', array('query' => array('asw403msg' => 1))));
    return TRUE;
  }
  if (strstr(request_path(), "admissions/graduate-legal-studies/admitted") !== FALSE) {
    header('Location: ' . url('admissions/graduate-legal-studies/my-columbia-llm', array('query' => array('asw403msg' => 1))));
    return TRUE;
  }
  if (strstr(request_path(), "students/student-services/orientation/jd-orientation") !== FALSE) {
    return '<h4>The J.D. orientation site requires you to be logged in with your LawNet username and password. If you have enrolled at the Law School and need to <a href="http://lawnetportal.law.columbia.edu/web/admitted-students" target="_blank">activate your LawNet account</a>, please do so now. Then, log in with your LawNet username and password before trying to access this site.</h4>';
  }
  return "<h4>You are not authorized to access this page.</h4>";
}

/**
* Displays custom "account not set up" message.
*
* @return $html - string containing html template
*/
function cls_sso_display_account_not_set_up() {
  return "<h4>Your account has not been set up fully.</h4>";
}

/**
* Checks if current Drupal user is logged in.
*
* @return $logged_in - TRUE or FALSE
*/
function cls_sso_check_login() {
  global $user;
  if ($user->uid) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Implements hook_views_api().
 */
function cls_sso_views_api() {
  return array(
    'api' => 3,
    'path' => drupal_get_path('module', 'cls_sso'),
  );
}

function cls_sso_views_data() {
    $data = array();

    $data['cls_sso_passcode_login_page']['table']['group'] = t('Passcode Pages');
    $data['cls_sso_passcode_login_page']['table']['base'] = array(
      'field' => 'pid', // This is the identifier field for the view. 
      'title' => t('Passcode Pages'), 
      'help' => t('Contains passcode pages.'), 
    );
    $data['cls_sso_passcode_login_page']['pid'] = array(
        'title' => t('Passcode Page ID'),
        'help' => t('Passcode Page ID.'),
        'filter' => array(
            'handler' => 'views_handler_filter_numeric',
        ),
        'field' => array(
            'handler' => 'views_handler_field_numeric',
            'click sortable' => TRUE,
        ),
        'sort' => array(
            'handler' => 'views_handler_sort',
        ),
        'argument' => array(
            'handler' => 'views_handler_argument_numeric',
        ),
    );
    $data['cls_sso_passcode_login_page']['login_page_title'] = array(
        'title' => t('Passcode login page title'),
        'help' => t('Passcode login page title.'),
        'filter' => array(
            'handler' => 'views_handler_filter_string',
        ),
        'field' => array(
            'handler' => 'views_handler_field',
            'click sortable' => TRUE,
        ),
        'sort' => array(
            'handler' => 'views_handler_sort',
        ),
        'argument' => array(
            'handler' => 'views_handler_argument_string',
        ),
    );
    $data['cls_sso_passcode_login_page']['login_page_body'] = array(
        'title' => t('Passcode login page body'),
        'help' => t('Passcode login page body'),
        'filter' => array(
            'handler' => 'views_handler_filter_string',
        ),
        'field' => array(
            'handler' => 'views_handler_field',
            'click sortable' => TRUE,
        ),
        'sort' => array(
            'handler' => 'views_handler_sort',
        ),
        'argument' => array(
            'handler' => 'views_handler_argument_string',
        ),
    );
    $data['cls_sso_passcode_login_page']['login_page_having_trouble_url'] = array(
        'title' => t('Passcode login page "Having Trouble" URL'),
        'help' => t('Passcode login page "Having Trouble" URL.'),
        'filter' => array(
            'handler' => 'views_handler_filter_string',
        ),
        'field' => array(
            'handler' => 'views_handler_field',
            'click sortable' => TRUE,
        ),
        'sort' => array(
            'handler' => 'views_handler_sort',
        ),
        'argument' => array(
            'handler' => 'views_handler_argument_string',
        ),
    );
    $data['cls_sso_passcode_login_page']['paths'] = array(
        'title' => t('Paths'),
        'help' => t('Paths associated with this definition.'),
        'filter' => array(
            'handler' => 'views_handler_filter_string',
        ),
        'field' => array(
            'handler' => 'views_handler_field',
            'click sortable' => TRUE,
        ),
        'sort' => array(
            'handler' => 'views_handler_sort',
        ),
        'argument' => array(
            'handler' => 'views_handler_argument_string',
        ),
    );
    return $data;
}