<?php
// add the action
//date_default_timezone_set('Australia/Brisbane');
function my_page_template_redirect() {

}
add_action( 'template_redirect', 'my_page_template_redirect' );
require_once ('include/setup_theme.php');
require_once ('include/enqueue_scripts.php');
require_once ('include/acf.php');
require_once ('include/custom_post_type.php');
require_once ('include/main.php');
require_once ('include/settelment_report.php');
function add_post_meta_boxes() {
    // see https://developer.wordpress.org/reference/functions/add_meta_box for a full explanation of each property
    add_meta_box(
        "post_metadata_school_dashboard", // div id containing rendered fields
        "School Dashboard", // section heading displayed as text
        "post_meta_box_school_dashboard", // callback function to render fields
        "schools", // name of post type on which to render fields
        "normal", // location on the screen
        "high" // placement priority
    );
}
add_action( "admin_init", "add_post_meta_boxes" );
function update_post_school_fundraised(){
    global $post;
    $totalRaised  = round(getTotalraiseForSchool($post->ID));
    $totalProfile = count(manageStudentfundrasingBySchool($post->ID));
    $sposn = 1; 
    $totalProfile = $totalProfile  - $sposn;
    if(($totalProfile > 0) || ($totalProfile >'0')){
        $totalProfile = $totalProfile;
    }else{
        $totalProfile = 0;
    }
    $totaldonation = totalDonationtransectionforCFAdmin($post->ID);
    $totalRegistration = totalRegistrationtransectionforCFAdmin($post->ID);
    $bothCheckbox = get_post_meta($post->ID,'bothcheckbox',true);
    if($bothCheckbox == 1){
        $bothCheck = 'Yes';
    }else if($bothCheckbox == 2){
        $bothCheck = 'No';
    }
    if ( get_post_type( $post->ID) == 'schools' ) {
        $cffield  = array(
            'total_funds_raised' => $totalRaised,
            'total_profiles' =>$totalProfile ,
            'total_revenue_registration_and_merch' => $totalRegistration ,
            'total_revenue_donations' => $totaldonation,
            'gst_invoivce_or_deduct_from_fundraising' => $bothCheck,
        );
        foreach ($cffield as $key => $value) {
            update_post_meta($post->ID,$key,$value);
        }
    }
}

/** Admin Logout */
//add_filter( 'logout_url', 'wpse_58453_logout_url' );
//function wpse_58453_logout_url( $default )
//{
//    $logoutUrl=str_replace("wp-login.php","D9o974Goqw5.php",$default);
//    // set your URL here
//    return is_admin() ? $logoutUrl  : $default;
//}
//add_filter( 'logout_url', 'my_logout_url' );
//function my_logout_url( $url ) {
//    $redirect = home_url();
//    return $url.'&redirect_to='.$redirect;
//}
//add_filter( 'logout_url', 'my_logout_page', 10, 2 );
//function my_logout_page( $logout_url, $redirect ) {
//    return home_url( '/D9o974Goqw5.php/?redirect_to=' . $redirect );
//}

add_action( "admin_head", "update_post_school_fundraised" );
function disable_acf_load_field( $field ) {
    $field['readonly'] = 1;
    return $field;
}
add_filter('acf/load_field/name=gst_invoivce_or_deduct_from_fundraising', 'disable_acf_load_field');
add_filter('acf/load_field/name=total_funds_raised', 'disable_acf_load_field');
add_filter('acf/load_field/name=total_profiles', 'disable_acf_load_field');
add_filter('acf/load_field/name=total_revenue_registration_and_merch', 'disable_acf_load_field');
add_filter('acf/load_field/name=total_revenue_donations', 'disable_acf_load_field');
add_filter('acf/load_field/name=school_logo', 'disable_acf_load_field');
function save_post_meta_boxes(){
    global $post;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
        return;
    }
    update_post_meta( $post->ID, "_post_school_dashboard_html", sanitize_text_field( $_POST[ "_post_school_dashboard_html" ] ) );
}
add_action( 'save_post', 'save_post_meta_boxes' );
function post_meta_box_school_dashboard(){
    global $post, $wpdb;
    $custom = get_post_custom( $post->ID );
    $school_id = $post->ID;
    $string = get_school_webpage($school_id)['data'][0]['event_name'];
    $goalAmount = get_school_webpage($school_id)['data'][0]['fundrasing_goal'];
    $strings = strtolower(str_replace(' ', '-', $string));
    $adminAccess='?user_id='.base64_encode(get_current_user_ID()).'&isAdmin=true&school_id='.base64_encode($school_id);
    $url = home_url().'/campaign/?id='.base64_encode($school_id); 
    echo '<p style="margin-bottom:10px;"><strong>School Dashboard: </strong><a target="_blank" href="'.$url.'">'.$url.'</a></p>';
    $school_logo = $goalAmount = get_school_webpage($school_id)['data'][0]['school_logo'];
    echo '<strong style="margin-bottom:15px;" for="school_logo">School Logo: </strong></br></br>';
    echo '<img src="'.$school_logo.'" width="150" height="150">';
}
function redirect_direct_access( ) {
   //echo "chk". $noheadfoot = sanitize_text_field( get_query_var( 'fbclid' ) );
    //echo "aa".$post_id = get_the_ID();die;
}
add_action( 'template_redirect', 'redirect_direct_access' );
function getAllSchoolbyUploadBulkCsv(){
   global $wpdb;
   $result=$wpdb->get_results("SELECT * FROM wp_school_details");
   if(isset($result) && !empty($result)){
    return $result;
   }
}
add_action("init","getAllSchoolbyUploadBulkCsv");
/* add menu for graph csv upload */
    function graph_admin_menu(){
        add_menu_page('Graph', 'Bulk Upload', 'manage_options', 'graph-slug', 'graph_function','dashicons-upload');
    }
    add_action('admin_menu', 'graph_admin_menu');
    function graph_function(){
?>
        <html>
            <head>
                <h3>Upload File</h3>
            </head>
            <body>
                <form enctype="multipart/form-data" action="" method="POST">
                    <div class="form-row">
                        <div class="col-lg-6 m-group">
                            <div class="form-group" style="margin-top: 10px;">
                                <label class="control-label school-label" for="email"><h3>Linked Event (School Name)</h3></label>
                                <div class="">
                                    <select style="width: 30%;" id="text" Placeholder="Choose School" required name="school_id"
                                            class="form-control ">
                                        <option>Select the School</option>
                                        <?php
                                           $result= getAllSchoolbyUploadBulkCsv();
                                         foreach($result as $key=>$res){ ?>
                                        <option value="<?php echo $res->school_id; ?>"><?php echo $res->event_name ;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="eventName" value="<?php echo $res->event_name ;?>">
                         <label class="control-label school-label" for="email"><h3>Please choose a file:</h3></label>
                            <input name="uploaded" required = 'required' type="file"/>
                    <input style="cursor: pointer;background-color: #F32085;color: #fff;" type="submit" name="submit" value="Submit"/>
                </div>
                </form>
            </body>
        </html>
<?php
        if(isset($_POST['submit']) && !empty($_POST['submit'])) {
            $school_id=$_POST['school_id'];
            if ($_FILES["uploaded"]["error"] > 0) {
            } else {
                $all_rows = array();
                $handle = fopen($_FILES['uploaded']['tmp_name'], "r");
                $header = fgetcsv($handle);
                for ($i = 0; $row = fgetcsv($handle); ++$i) {
                    $all_rows[] = array_combine($header, $row);
                }
                if(isset($all_rows) && !empty($all_rows)){
                    foreach($all_rows as $all_rowss){
                      echo ManageImportParentStudent($all_rowss,$school_id);
                    }
                    sentCustomWelcomeEmail($all_rows,$school_id);
                }
                fclose($handle);
                move_uploaded_file($_FILES["uploaded"]["tmp_name"],
                        get_stylesheet_directory() . '/upload/' . $_FILES["uploaded"]["name"]);
                die;
            }
        }
    }
    function sentCustomWelcomeEmail($data,$school_id){
            foreach($data as $key=>$val){
                $user=get_user_by('email',$val['Parent Email']);
                $user_id=$user->ID;
                if($val['Tshirt']){
                    $cloth = 1;
                }else{
                    $cloth = '';
                }
                if($val['Hat']=='Yes'){
                    $choose = 1;
                }else{
                    $choose = '';
                }
                $childDetail[]= array(
                        'parent_id' => $user_id,
                        'school_id' => $school_id,
                        'firstname' => ($val['Student First Name'])?($val['Student First Name']):'',
                        'lastname' => ($val['Student last Name'])?($val['Student last Name']):'',
                        'class' => ($val['Student Class Group'])?($val['Student Class Group']):'',
                        'childClasss' => ($val['Student Class 2 Group'])?($val['Student Class 2 Group']):'',
                        'nickname' => ($val['Student Nickname'])?($val['Student Nickname']):'',
                        'cloth_number' =>$cloth,
                        'cloth_size' => ($val['Tshirt'])?($val['Tshirt']):'',
                        'hat_number' => $choose,
                        'hat_size' => 'Choose',
                        'event_name' => stripslashes($_POST['Event Name']),
                        'status' => '1',
                        'd_off_amount'=>($val['Offline Donation'])?($val['Offline Donation']):Null,
                        'product_type' => 'register',
                        'total_sub_total' => '$ -',
                        'created_date' => current_time('mysql'),
                        'modified_date' => current_time('mysql'),
                );
            }
            $result=array();
            global $wpdb;
            foreach($childDetail as $key=>$child){
                $result[$child['parent_id']][]=$child;
            }
            foreach($result as $key=>$childDetail){
                    $user=get_user_by('id',$key,true);
                    $userEmail=$user->data->user_email;
                   foreach($childDetail as $valll){
                        $idd = $valll['parent_id'];
                        $resr=$wpdb->get_results("SELECT id FROM wp_child_details where parent_id = $idd", ARRAY_A);
                        $password = get_user_meta($idd,'password',true);
                        foreach($resr as $key=> $chilID){
                            $childDetail[$key]['child_id'] = $chilID['id'];
                            $childDetail['transectionDetails']['password'] = $password ;
                            $childDetail['transectionDetails']['payment_id'] = 'N/A';
                            $childDetail['transectionDetails']['status'] = 'succeeded';
                        }
                    }
                    sendEmailToParent($userEmail,$childDetail);
            }
               return $result;
    }
    function sendEmailToParent($userEmail,$childDetail){
        ob_start();
        send_welcome_email_parent($childDetail, $userEmail);
        ob_end_clean();
    }
    function ManageImportParentStudent($data,$school_id){
         $msg='<div>';$totalImportedData=array();
         $parentEmail=@$data['Parent Email'];
         $hashedPassword = wp_generate_password(12, true);
         if(isset($parentEmail) && !empty($parentEmail)){
            global $wpdb;
            $logData = json_encode($_POST); 
            $wpdb->insert('wp_custom_log',array('all_request'=>@$logData,'which_rego_flow'=>'AddByCsv'));
             $exists=email_exists($parentEmail);
             if($exists){
                 $user=get_user_by('email',$parentEmail);
                 $user_id=$user->ID;
             }else {
                 $parentDetail = array(
                         'user_login' => @$parentEmail,
                         'user_email' => @$parentEmail,
                         'user_pass' =>$hashedPassword,
                 );
                 $user_id = wp_create_user(@$parentEmail, @$hashedPassword, @$parentEmail);
                 $user = new WP_User($user_id);
                 $user->set_role('student_role');
                 $wpdb->update('wp_users',array('payment_status'=>1), array('ID'=>$user_id));
                 wp_update_user(array('ID' => $user_id, 'display_name' => @$data['Parent First Name'].' '.@$data['Parent Last Name'] ));
                 $parentData = array(
                         'phone' => @$data['Parent Mobile Number'],
                         'event-name' => $_POST['Event Name'],
                         'first_name' => @$data['Parent First Name'],
                         'last_name' => @$data['Parent Last Name'],
                         'password' => $hashedPassword ,
                         'school_id' => $school_id
                 );
                 foreach ($parentData as $key => $val) {
                     update_user_meta($user_id, $key, $val);
                 }
             }
             if ($user_id) {
                 /** childDetail */
                 $_POST=$data;
                 if($_POST['Tshirt']){
                    $cloth = 1;
                }else{
                    $cloth = '';
                }
                if($_POST['Hat']=='Yes'){
                    $choose = 1;
                }else{
                    $choose = '';
                }
                if(trim($_POST['Student Class 2 Group'])){
                    $allClass = $_POST['Student Class Group'].' ,'.$_POST['Student Class 2 Group'];
                }else{
                    $allClass = $_POST['Student Class Group'];
                }
                if(trim($_POST['Student Nickname'])){
                   $event_name =  $_POST['Student Nickname'].' ,'.$allClass;
                }else{
                    $event_name =  $_POST['Student First Name'].' '.ucfirst($_POST['Student last Name'][0]).','.$allClass;
                }
                $title =  get_school_webpage($school_id)['data'][0]['school_name'];
                    if(empty(trim($_POST['Student Nickname']))){
                        $firstname = trim($_POST['Student First Name']).''.trim(substr($_POST['Student last Name'], 0, 1));
                    }else{
                        $firstname = trim($_POST['Student Nickname']);
                    }
                    $fname =  trim($_POST['Student First Name']);
                    $lname =  trim(substr($_POST['Student last Name'], 0, 1));
                    $school_child_name=$wpdb->get_results("SELECT count(firstandlastname) as total from wp_child_details WHERE school_id = $school_id AND firstname = '".$fname."' AND SUBSTRING(lastname, 1, 1) = '".$lname."'",ARRAY_A);
                    $count = $school_child_name[0]['total'];
                    if($count > 1){
                        $countAdd = $count;
                    }else if($count == 1){
                        $countAdd = 1;
                    }else{
                        $countAdd = '';
                    }
                    $child_or_school_name = str_replace(' ', '', $title).'/'.str_replace("'", '', $firstname).''.$countAdd;
                 $childDetail= array(
                        'parent_id' => $user_id,
                        'school_id' => $school_id,
                        'child_or_school_name' => ($child_or_school_name) ? (str_replace(' ', '', $child_or_school_name)) : 'No',
                        'firstandlastname' => ($firstname) ? (str_replace(' ', '', $firstname)) : 'No',
                        'firstname' => ($_POST['Student First Name'])?($_POST['Student First Name']):'',
                        'lastname' => ($_POST['Student last Name'])?($_POST['Student last Name']):'',
                        'class' => ($_POST['Student Class Group'])?($_POST['Student Class Group']):'',
                        'childClasss' => ($_POST['Student Class 2 Group'])?($_POST['Student Class 2 Group']):'',
                        'nickname' => ($_POST['Student Nickname'])?($_POST['Student Nickname']):'',
                        'cloth_number' =>$cloth,
                        'cloth_size' => ($_POST['Tshirt'])?($_POST['Tshirt']):'',
                        'hat_number' => $choose,
                        'hat_size' => 'Choose',
                        'event_name' => stripslashes($_POST['Event Name']),
                        'd_off_amount'=>($_POST['Offline Donation'])?($_POST['Offline Donation']):Null,
                        'status' => '1',
                        'product_type' => 'register',
                        'total_sub_total' => 0,
                        'created_date' => current_time('mysql'),
                        'modified_date' => current_time('mysql'),
                 );
                 $wpdb->insert('wp_child_details', $childDetail);
                 $child_id = $wpdb->insert_id;
                 $msg .=$_POST['Student First Name'].' child_id is '.$child_id;
                 if($child_id){
                     $fundriaisingDetails= array(
                             'user_id' => $user_id,
                             'child_id' => $child_id,
                             'title'=> stripslashes($event_name),
                             'description' => 'I want to help raise money for our school for new equipment and to help create a better future for our students.',
                             'start-date' => current_time('mysql'),
                             'end-date' => current_time('mysql'),
                             'min-amount' => '100',
                             'max-amount' => '100',
                             'goal-amount' => ($_POST['Fundraising Goal'])?($_POST['Fundraising Goal']):0,
                             'status' => '1',
                             'accepted_at' => current_time('mysql'),
                     );
                     $wpdb->insert('fundraise_posted_campiagn', $fundriaisingDetails);
                     $wpdb->insert('fundraise_posted_campiagn_deleted', $fundriaisingDetails);
                     $fund_page_id = $wpdb->insert_id;
                     $msg .=' '.$_POST['Student First Name'].'fundpage id '.$fund_page_id;
                     $totalImportedData=$fund_page_id;
                 }
                  if(!empty($_POST['Offline Donation'])){
                    $offlineData =
                         array('amount' => $_POST['Offline Donation'],
                                  'shipping_firstname'=>'Offline-donation',
                                  'user_id' => $user_id,
                                  'fundpage_id' => $fund_page_id,
                                  'child_id' => $child_id,
                                  'd_off_amount' => 'yes',
                                  'taxable_amount' => $_POST['Offline Donation'],
                        );
                     $wpdb->insert('fundraise_donation',$offlineData);
                }
                $wpdb->insert('wp_child_details_deleted', $childDetail);
                 $msg .='</div><br>';
                 return $msg;
             }
         }
    }

    define( 'SENDINBLUE_URL', 'https://api.sendinblue.com/v3/smtp/email' );
    define( 'SENDINBLUE_API_KEY', 'c2008e86cbc823f' );

    function sendSendInBlueRequest($url, $data){
        $postdata = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Content-Type: application/json",
            "api-key: ".SENDINBLUE_API_KEY,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    add_filter( 'send_retrieve_password_email', 'sbs_send_retrieve_password_email_filter', 10, 3 );
    function sbs_send_retrieve_password_email_filter( $send, $user_login, $user_data ){
        $errors    = new WP_Error();
        $user_data = false;
        if ( ! $user_login && ! empty( $_POST['user_login'] ) ) {
            $user_login = $_POST['user_login'];
        }
        if ( empty( $user_login ) ) {
            $errors->add( 'empty_username', __( '<strong>Error</strong>: Please enter a username or email address.' ) );
        } elseif ( strpos( $user_login, '@' ) ) {
            $user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
            if ( empty( $user_data ) ) {
                $errors->add( 'invalid_email', __( '<strong>Error</strong>: There is no account with that username or email address.' ) );
            }
        } else {
            $user_data = get_user_by( 'login', trim( wp_unslash( $user_login ) ) );
        }

        $user_data = apply_filters( 'lostpassword_user_data', $user_data, $errors );
        do_action( 'lostpassword_post', $errors, $user_data );
        $errors = apply_filters( 'lostpassword_errors', $errors, $user_data );

        if ( $errors->has_errors() ) {
            return $errors;
        }
        if ( ! $user_data ) {
            $errors->add( 'invalidcombo', __( '<strong>Error</strong>: There is no account with that username or email address.' ) );
            return $errors;
        }

        $user_email = $user_data->user_email;
        $allow = true;
        if ( is_multisite() && is_user_spammy( $user_data ) ) {
            $allow = false;
        }
        $allow = apply_filters( 'allow_password_reset', $allow, $user_data->ID );
        if ( ! $allow ) {
            return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user' ) );
        } elseif ( is_wp_error( $allow ) ) {
            return $allow;
        }
        $key = wp_generate_password( 20, false );
        do_action( 'retrieve_password_key', $user_login, $key );
        if ( empty( $wp_hasher ) ) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash( 8, true );
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );

        $key_saved = wp_update_user(
            array(
                'ID'                  => $user_data->ID,
                'user_activation_key' => $hashed,
            )
        );

        if ( is_wp_error( $key ) ) {
            return $key;
        }
        $locale = get_user_locale( $user_data );
        $switched_locale = switch_to_locale( $locale );
        if ( $switched_locale ) {
            restore_previous_locale();
        }
        $subject = wp_specialchars_decode( $subject );
        $emailParams = [];
        $emailParams['user_name'] = $user_login;
        $emailParams['activationLink'] = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '&wp_lang=' . $locale . "\r\n\r\n";
        $data['to'] = [['email'=>$user_email]];
        $data['cc'] = [['email'=>CC_EMAIL]];
        $data['params'] = $emailParams;
        $data['templateId'] = 340;
        $resData =  sendSendInBlueRequest(SENDINBLUE_URL,$data);
        return false;
    }

    add_action( 'validate_password_reset', 'rsm_redirect_after_rest', 10, 2 );
    function rsm_redirect_after_rest($errors, $user) {
        if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
            reset_password( $user, $_POST['pass1'] );

            list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );
            $rp_cookie = 'wp-resetpass-' . COOKIEHASH;
            setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

            /* send password change confirmation email */
                $user = get_user_by('ID', $user->ID);
                $user_name = $user->data->display_name;
                $user_email = $user->data->user_email;
                $ip = $_SERVER['REMOTE_ADDR'];
                $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
                $emailParams = [];
                $emailParams['user_name'] = $user_name;
                $emailParams['current_time'] = date('d/m/Y H:m');
                $emailParams['location'] = $details->city;
                $emailParams['device'] = $_SERVER['HTTP_USER_AGENT'];
                $emailParams['login_url'] = home_url('/school-login');
                $data['to'] = [['email'=>$user_email]];
                $data['cc'] = [['email'=>CC_EMAIL]];
                //$data['replyTo'] = ['email'=>'sudhanshu@delimp.com','name'=>'Sudhanshu Bisht'];
                $data['params'] = $emailParams;
                //$data['subject'] = "School Account opening email confirmation";
                $data['templateId'] = 338;
                $resData =  sendSendInBlueRequest(SENDINBLUE_URL,$data);
            /* end send password change confirmation email */
            wp_set_current_user( $user->ID );
            wp_set_auth_cookie( $user->ID );
            do_action( 'wp_login', $user->user_login, $user );
            wp_redirect( home_url() );
            exit;
        }
    }

//add_action( 'create_school_site_reminder', 'create_school_site_reminder_func' );
function create_school_site_reminder_func() {
      global $wpdb;
      $sql_query = "SELECT * FROM `wp_school_details` WHERE `created_date` < (now() - INTERVAL 72 HOUR) AND event_name='' ORDER BY `id` DESC";
      $pending_sites =$wpdb->get_results($sql_query);
      if(!empty($pending_sites)){
        foreach($pending_sites as $key=>$site){
          $school_id = $site->school_id;
          $user_meta_sql = "SELECT user_id FROM `wp_usermeta` WHERE `meta_key`='school_id' AND meta_value={$school_id}";
          $user = $wpdb->get_row($user_meta_sql);
          $user_id = $user->user_id;
          $user_detail = get_user_by('ID',$user_id);
          $user_email = $user_detail->data->user_email;
          $display_name = $user_detail->data->display_name;
          $user_password = get_user_meta($user_id,'school_without_password',true);
          $campaign_name = get_post_meta($school_id,'campaign_name',true);
          $emailParams = [];
          $email = $user_email;
          $emailParams['user_email'] = $user_email;
          $emailParams['campaign_name'] = $campaign_name;
          $emailParams['user_pass'] = $user_password;
          $emailParams['url'] = home_url('school-login');
          $emailParams['FIRSTNAME'] = $display_name;
          $data['to'] = [['email'=>$email]];
          $data['cc'] = [['email'=>CC_EMAIL]];
          $data['params'] = $emailParams;
          $data['templateId'] = 347;
          $resData =  sendSendInBlueRequest(SENDINBLUE_URL,$data);
        }
      }
}

function flipbook_admin_menu(){
    add_menu_page('Flipbook', 'Flipbook', 'manage_options', 'flipbook-slug', 'flipbook_function','dashicons-admin-page');
}
add_action('admin_menu', 'flipbook_admin_menu');

    function flipbook_function(){
?>
        <html>
            <head>
                <h3>Enter the flipbook URL</h3>
                <p>You need to upload pdf from media section and copy that pdf url then paste in below box and click on "Add Flipbook" button</p>
            </head>
            <body>
                <form enctype="multipart/form-data" action="" method="POST">
                    <div class="form-row">
                        <input autocomplete="off" required type="url" name="flip_bookurl" placeholder="Enter the pdf url" class="flip_bookurl" style="width: 50%;">
                    <input style="cursor: pointer;background-color: #F32085;color: #fff;" type="submit" name="update" value="Add Flipbook"/>
                </div>
                </form>
            </body>
        </html>
<?php }
if(isset($_POST['update']) && !empty($_POST['flip_bookurl'])) {
    global $wpdb;
    $sql = $wpdb->update('wp_flipbook',array('url' => $_POST['flip_bookurl']),array('id' => 1));
    if($sql== 0){
        echo '<div style="margin-left: 14%;font-size: 17px;color: green;">Added Successfully</div>';
    }
}
function reorder_admin_menu( $__return_true ) {
    return array(
        'separator1',
        'index.php',
        'edit.php?post_type=schools',
        'manage-profile',
        'manage-archived-profile',
        'manage-transection',
        'manage-donations-transection',
        'refund-donations',
        'dashboard',
        'dashboard-archived',
        'graph-slug',
        'users.php',
        'separator2',
        'edit.php?post_type=quoteproduct',
        'edit.php?post_type=tshirt-size',
        'edit.php?post_type=tshirt-details',
        'edit.php?post_type=fundraing',
        'edit.php?post_type=help-guide',
        'edit.php?post_type=parent-faq',
        'edit.php?post_type=productitem',
        'edit.php?post_type=sales',
        'build-quotes',
        'flipbook-slug',
        'separator3',
        'edit.php?post_type=page',
        'tools.php',
        'options-general.php',
        'gf_edit_forms',
        'separator4',
        'themes.php',
        'sib_page_home',
        'edit.php?post_type=badges',
        'change-profile',
        'upload.php',
        'wpcf7',
        'bookit',
        'edit.php?post_type=child-class',
        'edit-comments.php',
        'plugins.php',
        'edit.php?post_type=acf-field-group',
        'theme-general-settings',
        'edit.php'
   );
}
add_filter( 'custom_menu_order', 'reorder_admin_menu' );
add_filter( 'menu_order', 'reorder_admin_menu' );