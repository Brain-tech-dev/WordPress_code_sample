<?php
/*
 * School Dashboard
 * @ver:1.0
 */
function parent_dashboard_callback1($atts)
{
        session_start();
        global $wpdb;
        $atts = shortcode_atts(array(), $atts, 'parent_dashboard');
        $user_id = base64_decode($_GET['id']);
        $_SESSION['userId'] =$user_id;
        if ((is_user_logged_in()) && (!empty($user_id)) && (isset($user_id))): 
        $user = get_userdata($user_id);
        $userMeta = get_user_meta($user->ID);
        $parent = new Parent_model();
        $table = TABLE_PREFIX_MAIN . "child_details";
        $where = " WHERE parent_id=" . $user_id;
        $childData = $parent->get($table, $where); 
        $schoolid = $childData['data'][0]['school_id'];
        if((!empty($schoolid)) && (isset($schoolid))){
          $schoolDetails = get_school_webpage($schoolid);  
        }
        $class_one_hide = $schoolDetails['data'][0]['set_a_show_fundrasing'];
        $class_two_hide = $schoolDetails['data'][0]['set_b_show_fundrasing'];
        $class_three_hide = $schoolDetails['data'][0]['set_c_show_fundrasing'];
        $class_four_hide = $schoolDetails['data'][0]['set_d_show_fundrasing'];
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <style>
            .modal-donation .modal-content{margin: 15% auto;width: 94%;}
            .modal-donation .sorting_disabled{font-size: 12px !important;}
            .modal-donation table.dataTable tbody tr{background-color: #005473 !important ;}
            .modal-donation #serach_tables td {font-size: 12px}
            /*.dt-buttons, .dataTables_filter{display: none;}*/
            .modal-donation .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            color: #fff !important;
        }
        </style>
        <div class="main-dashboard parent-dsh">
            <section class="reports_message_detail parent_login">
                <div class="login_link_detail" style="max-width: 1150px;">
                    <h3 class="heading">Dashboard</h3>
                    <div class="row">
                        <div class="col-lg-6 parent-register">
                            <div class="row pb-1">
                                <div class="col-lg-6">
                                    <div class="copy_link">
                                        <label>Parent First Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" readonly class="form-control"
                                               value="<?php echo $userMeta['first_name'][0]; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row pb-1">
                                <div class="col-lg-6">
                                    <div class="copy_link">
                                        <label>Parent Last Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" readonly
                                               value="<?php echo $userMeta['last_name'][0]; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row pb-1">
                                <div class="col-lg-6">
                                    <div class="copy_link">
                                        <label>Parent Email</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control parentEmailDash" readonly
                                               value="<?php echo $user->data->user_email; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row pb-1">
                                <div class="col-lg-6">
                                    <div class="copy_link">
                                        <label>Parent Phone</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" readonly
                                               value="<?php echo $userMeta['phone'][0]; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row pb-1">
                                <div class="col-lg-6">
                                    <div class="copy_link">
                                        <label>Event Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control parentSchoolEvent" readonly
                                               value="<?php echo stripslashes($childData['data'][0]['event_name']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="parent_login_link">
                                <div class="row pb-1">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="form-group for-button">
                                            <label>View Receipt</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group for-button">
                                            <a href="<?php echo home_url() . '/invoice'; ?>" class="btn btn-warning">Receipt</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-1">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="form-group for-button">
                                            <label>FAQ’s</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group for-button">
                                            <a href="<?php echo home_url(); ?>/parent-faqs" class="btn btn-warning">FAQ’s</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-1">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="form-group for-button">
                                            <label>Event Flyer</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group for-button">
                                            <?php
                                            if((!empty($user_id)) && (isset($user_id))){
                                                $school_id = getChildSchoolId($user_id);
                                            }
                                            $giftCardDate =get_field('incentive_end_date', $school_id['school_id']);
                                            $_SESSION['school_id'] = $school_id['school_id'];
                                            $parentTakeHomeLetter = get_field('parent_take_home_letter', $school_id['school_id']);
                                            ?>
                                            <a target="_blank" href="<?php echo $parentTakeHomeLetter['url']; ?>"
                                               class="btn btn-warning">Letter</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-1">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="form-group for-button">
                                            <label>Update Details</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group for-button">
                                            <button id="edit-button" class="btn btn-warning">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-1">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="form-group for-button">
                                            <label>Change password</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group for-button">
                                            <button id="edit-password" class="btn btn-warning">Change</button>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    $schoolStartDate=get_field('campaign_start_date',$school_id['school_id']);
                                    $schoolEndDate=get_field('campaign_end_date',$school_id['school_id']);
                                    $currentdate = date('F j,Y');
                                    if ((strtotime($schoolEndDate) >= strtotime($currentdate)) && (strtotime($schoolStartDate) <=strtotime($currentdate))){
                                ?>
                                <div class="row pb-1">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="form-group for-button">
                                            <label>Add Another Child</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group for-button"> <button id="add_another_child" class="btn btn-warning add_childs">ADD</button> </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
             <section class="threedates">
                <div class="row">
                    <div class="col-lg-7 parent-register" style="margin: auto;">
                        <div class="row pb-1">
                            <div class="col-lg-6">
                                <div class="copy_link">
                                    <label>Event Date:</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" readonly class="form-control"
                                           value="<?php echo get_field('size_event_date',$school_id['school_id']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row pb-1">
                            <div class="col-lg-6">
                                <div class="copy_link">
                                    <label>Registration Close Date:</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" class="form-control" readonly
                                           value="<?php 
                                           $ttt =  get_field('campaign_end_date',$school_id['school_id']);
                                            echo  date('d/m/Y', strtotime($ttt)); ?>">
                                </div>
                            </div>
                        </div>
                    <?php 
                    $merch = get_field('merchandise', $school_id['school_id']);
                    if ($merch == 'yes'){ ?>
                        <div class="row pb-1">
                            <div class="col-lg-6">
                                <div class="copy_link">
                                    <label>Merchandise Order Close Date:</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="email" class="form-control parentEmailDash" readonly
                                           value="<?php echo get_field('merch_order_end_date',$school_id['school_id']); ?>">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div> 
            </section>
            <section class="funddash form-section for-mobile">
                <div class="child_register" style="max-width: 1150px;">
                    <?php if (!empty($childData['data'])) { ?>
                        <h3 class="heading" style="    margin-bottom: 0px;">Children registered</h3>
                        <?php
                        $totalChild = count($childData['data']);
                        $max_fund_raised = maxFundRaised($childData['data']);
                        $_SESSION['max_fund_raised'] = $max_fund_raised;
                        $i = 0;
                        $j = 1;
                        foreach (($childData['data']) as $key => $child): //pr($child);
                                $child_fundpage = get_child_fundraising_page($child['parent_id'],$child['id']);
                                $donation = get_all_fund_donation(@$child_fundpage['data']['id']);
                                $deviceType=isMobile($_SERVER["HTTP_USER_AGENT"]);
                            if($deviceType) {
                            ?>
                                <h3 style="margin-bottom: 0px;font-size: 30px;">Child <?php echo $j; ?></h3>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="user-detail">
                                            <div class="user-n-ab" style="margin-bottom: -34px;">
                                                <?php 
                                                if(get_user_meta($user_id,'avtar_'.$child['id'],true)){
                                                    $attecmentid = get_user_meta($user_id,'avtar_'.$child['id'],true);
                                                    $image =  wp_get_attachment_url($attecmentid);
                                                }else{
                                                    $image =  wp_get_attachment_url(9438);
                                                }
                                                ?>
                                                <div>
                                                <a class="imagechanges" href="<?php echo home_url().'/profile/?'.$child['child_or_school_name']; //echo home_url() . '/student-fundraising-page/?child-id=' . base64_encode($child['id']); ?>"><img style="max-width: 100%;" src="<?php echo $image; ?>"></a>
                                                <?php 
                                                     $imageChange = get_field('change_profile', $school_id['school_id']); 
                                                     if($imageChange == 'on'){
                                                     ?>
                                                <div style="margin: 0 10%;">
                                                    <button id="Changed_image" data-size="<?php echo 'image'; ?>" class="btn btn-warning  Allimage_<?php echo $child['id']; ?> " data-childid="<?php echo $child['id']; ?>">Change image</button>
                                                </div>
                                            <?php } ?>
                                                </div>
                                                <div class="name_abc">
                                                    <h3><?php if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                            echo stripslashes($child['nickname']);
                                                        } else {
                                                            echo stripslashes($child['firstname']) . ' ' . stripslashes($child['lastname']);
                                                        } ?></h3>
                                                    <div class="row m-0">
                                                        <div class="col-lg-6">
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-9">
                                                                    <div class="copy_link">
                                                                        <label>First Name</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 parentLayout">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="<?php echo stripslashes($child['firstname']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row px-2 align-items-center">
                                                                <div class="col-lg-9">
                                                                    <div class="copy_link">
                                                                        <label>Last Name</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 parentLayout">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="<?php echo stripslashes($child['lastname']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-9">
                                                                    <div class="copy_link">
                                                                        <label>Class/Group</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 parentLayout">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="<?php echo stripslashes($child['child_set_a']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty(trim($child['child_set_b']))) { ?>
                                                                <div class="row align-items-center" style="padding: 7px 0;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Class/Group</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['child_set_b']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (!empty(trim($child['child_set_c']))) { ?>
                                                                <div class="row align-items-center" style="padding: 7px 0;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Class/Group</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['child_set_c']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (!empty(trim($child['child_set_d']))) { ?>
                                                                <div class="row align-items-center" style="padding: 7px 0;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Class/Group</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['child_set_d']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <button id="Changed_child_details" class="las_act_but btn btn-warning edit_compaign_model <?php if(trim($child['firstname']) == 'Sponsors Page'){ echo 'd-none'; }?>" data-class="<?= $child['child_set_a'] ?>" data-fund="<?= $child['comp_id'] ?>" data-title="<?= $child['title'] ?>" data-target="#myModal" id="myBtn " data-ChildID="<?php echo $child['id'] ; ?>" >Edit</button>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <?php if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') { ?>
                                                                <div class="row align-items-center"
                                                                     style="padding-bottom: 10px;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Nickname</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['nickname']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (trim($child['cloth_number'])) { ?>
                                                                <div class="row align-items-center">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>T-Shirt</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo $child['cloth_size']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $sizeEventDate = get_field('merch_order_end_date', $school_id['school_id']);
                                                                $sizeEventDate = str_replace('/', '-', $sizeEventDate);
                                                                $sizeEventDate = date('d-m-Y', strtotime($sizeEventDate));
                                                                $sizeEventDate = $sizeEventDate .TIME_ZONE_SET;
                                                                //$daysbefore = date('d-m-Y', strtotime('-20 days', strtotime($sizeEventDate)));
                                                                date_default_timezone_set('Australia/Brisbane');
                                                                $currentDate = date('d-m-Y H:i:s');
                                                                if (strtotime($sizeEventDate) > strtotime($currentDate)):
                                                                ?>
                                                                <div class="row align-items-center">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link" style="margin-top: 5px;">
                                                                            <label>Change Size</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 ">
                                                                        <div class="form-group">
                                                                            <button id="Changed_size" data-size="<?php echo $child['cloth_size']; ?>" class="btn btn-warning  Allsize_<?php echo $child['id']; ?> tShirtSize" data-childid="<?php echo $child['id']; ?>">Change Size</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif;
                                                                }
                                                            if (trim($child['hat_number'])) { ?>
                                                                <div class="row px-2 align-items-center">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Bucket Hat</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo (!empty($child['hat_number']) && (isset($child['hat_number'])) && (($child['hat_number'])) !== ' ') ? 'YES' : 'NO'; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }
                                                            $sizeEventDate = get_field('merch_order_end_date', $school_id['school_id']);
                                                            $sizeEventDate = str_replace('/', '-', $sizeEventDate);
                                                            $sizeEventDate = date('d-m-Y', strtotime($sizeEventDate));
                                                            $sizeEventDate = $sizeEventDate .TIME_ZONE_SET;
                                                            //$daysbefore = date('d-m-Y', strtotime('-20 days', strtotime($sizeEventDate)));
                                                            $currentDate = date('d-m-Y H:i:s');
                                                            $merch = get_field('merchandise', $school_id['school_id']);
                                                            if ($merch == 'yes' && (strtotime($sizeEventDate) >= strtotime($currentDate))) {
                                                                if (empty(trim($child['cloth_size'])) && empty(trim($child['hat_number']))) {
                                                                    ?>
                                                                    <div class="row align-items-center"
                                                                         style="    margin-top: 10px;">
                                                                        <div class="col-lg-9">
                                                                            <div class="copy_link">
                                                                                <label>Merchandise</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-9">
                                                                            <div class="form-group for-button">
                                                                                <button id="order_button_both"
                                                                                        class="btn btn-warning order_button AllModalbutton_<?php echo $child['id']; ?>"
                                                                                        data-childid="<?php echo $child['id']; ?>">
                                                                                    Order
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['cloth_size']))) { ?>
                                                                    <div class="row align-items-center"
                                                                         style="    margin-top: 10px;">
                                                                        <div class="col-lg-9">
                                                                            <div class="copy_link">
                                                                                <label>Merchandise</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-9 parentLayout">
                                                                            <div class="form-group for-button">
                                                                                <button id="order_button_tshirt"
                                                                                        class="btn btn-warning order_button AllModalbutton_<?php echo $child['id']; ?>"
                                                                                        data-childid="<?php echo $child['id']; ?>">
                                                                                    Order
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['hat_number']))) { ?>
                                                                    <div class="row align-items-center"
                                                                         style="    margin-top: 10px;">
                                                                        <div class="col-lg-9">
                                                                            <div class="copy_link">
                                                                                <label>Merchandise</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-9 parentLayout">
                                                                            <div class="form-group for-button">
                                                                                <button id="order_button_hat"
                                                                                        class="btn btn-warning order_button AllModalbutton_<?php echo $child['id']; ?>"
                                                                                        data-childid="<?php echo $child['id']; ?>">
                                                                                    Order
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php }  } 
                                                                $events = get_field('fundraising_event', $school_id['school_id'], false);
                                                                if ($events == 'Yes') {
                                                                ?>
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-9 ">
                                                                    <div class="form-group">
                                                                        <button id="show_donations" class="btn btn-warning alldonations_<?php echo $child['id']; ?>" data-childid="<?php echo $child['id']; ?>">Donations</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                         <!-- This model for show donations -->
                                            <div id="donationsAll_<?php echo $child['id']; ?>" class="modal fade modal-donation" role="dialog">
                                                <div class="modal-content modal_btn forchilddonorparent">
                                                    <button class="donations" style="position: absolute;right: 27px;font-size: 18px;font-weight: bold;">X</button>
                                                    <div class="form-group group">
                                                        <h3 class="heading">Show Donations</h3>
                                                    </div>
                                                    <div class="table_responsive">
                                                    <div class="report" id="printTable">
                                                        <div class="report" id="printTable">
                                                                <table class="table dataTables" id="dataTables">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">Participant Profile Name</th>
                                                                    <th scope="col">Participant First Name</th>
                                                                    <th scope="col">Participant Last Name</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Event Name</th>
                                                                    <th scope="col">List of donors</th>
                                                                    <th scope="col">Donation</th>
                                                                    <th scope="col">Refunded Donation</th>
                                                                    <th scope="col">Donation Dates </th>
                                                                    <th scope="col">Bank Chargeback List</th>
                                                                    <th scope="col">Bank Chargeback Amount</th>
                                                                    <th scope="col">Organisation ABN Number</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="serach_tables">
                                                                <?php
                                                                $child_id = $child['id'];
                                                                $funddatas = manageDonationByEachDonationOnbothDashboard($child_id); 
                                                                if(!empty($funddatas)){
                                                                    foreach ($funddatas as $funddata) {
                                                                        if(trim($funddata['nickname'])){
                                                                                $titleName = $funddata['nickname'];
                                                                        }else{
                                                                            $titleName = stripslashes($funddata['firstname']).' '.stripslashes(ucfirst($funddata['lastname'][0]));
                                                                        }
                                                                        if(trim($funddata['child_set_b'])){
                                                                            $child_set_b = ', '.$funddata['child_set_b'];
                                                                        }
                                                                        if(trim($funddata['child_set_c'])){
                                                                            $child_set_c = ', '.$funddata['child_set_c'];
                                                                        }
                                                                        if(trim($funddata['child_set_d'])){
                                                                            $child_set_d = ', '.$funddata['child_set_d'];
                                                                        }
                                                                        $title_name = $titleName.', '.stripslashes($funddata['child_set_a']).''.stripslashes($child_set_b).''.stripslashes($child_set_c).''.stripslashes($child_set_d);
                                                                        ?>
                                                                    <tr id="<?php echo $funddata['id']; ?>">
                                                                        <td><?php echo $title_name; ?></td>
                                                                        <td><?php echo stripslashes($funddata['firstname']); ?></td>
                                                                        <td><?php echo stripslashes($funddata['lastname']); ?></td>
                                                                        <td><?php echo stripslashes($funddata['child_set_a']); ?></td>
                                                                        <td><?php echo stripslashes(trim($funddata['child_set_b']));?></td>
                                                                        <td><?php echo stripslashes(trim($funddata['child_set_c']));?></td>
                                                                        <td><?php echo stripslashes(trim($funddata['child_set_d']));?></td>
                                                                        <td><?php echo stripslashes($funddata['event_name']); ?></td>
                                                                        <td><?php if(empty($funddata['keep_safe'])){ echo removeBadWords($funddata['shipping_firstname']).' '.removeBadWords($funddata['shipping_lastname']);}else{ echo 'Anonymous';}  ?></td>
                                                                        <td><?php if($funddata['amount']){ echo DEFAULT_CURRENCY . round($funddata['amount'],2);}else{ echo DEFAULT_CURRENCY .'0.00';} ?></td>
                                                                        <td><?php if($funddata['refunded_donation']){ echo DEFAULT_CURRENCY . round($funddata['refunded_donation'],2);}else{ echo DEFAULT_CURRENCY .'0.00';} ?></td>
                                                                        <td><?php if($funddata['created_date']){echo $funddata['created_date'];} ?></td>
                                                                        <td><?php if($funddata['charge_by_done']){ echo $funddata['charge_by_done'];}else{ echo "--";} ?></td>
                                                                        <td><?php if($funddata['charge_amount']){ echo DEFAULT_CURRENCY . round($funddata['charge_amount'],2);}else{ echo DEFAULT_CURRENCY .'0.00';} ?></td>
                                                                        <td><?php if($funddata['abn']){ echo $funddata['abn'];} ?></td>
                                                                    </tr>
                                                                    <?php  } } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- This model for show donations -->
                                            <?php if (empty(trim($child['cloth_size'])) || empty(trim($child['hat_number']))) { ?>
                                                <div id="myModalboth_<?php echo $child['id']; ?>"
                                                     class="modal fade fundModal media_popup" role="dialog">
                                                    <!-- Modal content -->
                                                    <?php //pr($child) ;?>
                                                    <div class="modal-content">
                                                        <form method="POST" action=""
                                                              id="order_model_<?php echo $child['id']; ?>"
                                                              class="order_both">
                                                            <input type="hidden" name="eventName" value="<?php echo stripslashes($child['event_name']); ?>">
                                                            <input type="hidden" name="SchoolID" value="<?php echo $child['school_id']; ?>">
                                                            <input type="hidden" name="Child_id" id="model_childid"
                                                                   value="<?php echo $child['id']; ?>">
                                                            <input type="hidden" name="parent_id" id="model_parentid"
                                                                   value="<?php echo $child['parent_id']; ?>">
                                                            <input type="hidden" name="action"
                                                                   value="addStudentParenthatandshirt">
                                                            <button class="closed_model parent-model">X</button>
                                                            <div class="child_merchandise_1 child_merchandise_custm">
                                                                <?php if (empty(trim($child['cloth_size'])) && empty(trim($child['hat_number']))) {
                                                                    ?>
                                                                    <div class="form-group group">
                                                                        <?php
                                                                        $args = array(
                                                                                'post_type' => 'tshirt-details',
                                                                                'post_status' => 'publish',
                                                                                'posts_per_page' => 1,
                                                                                'orderby' => 'date',
                                                                                'order' => 'ASC',
                                                                        );
                                                                        $loop = new WP_Query($args);
                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                            ?>
                                                                            <input type="hidden" class="tshirtheading"
                                                                                   value="<?php the_field('tshirt_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirthatcontent"
                                                                                   value="<?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtPrice"
                                                                                   value="<?php the_field('tshirt_price', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtContent"
                                                                                   value="<?php the_field('tshirt_content', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtImage"
                                                                                   value="<?php the_field('tshirt_image', $loop->ID); ?>">
                                                                            <h3 class="heading"><?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?></h3>
                                                                            <p class="Ttitleall">A fantastic addition to your student’s school colour blast event experience. </p></br>
                                                                            <p class="Ttitleall">Your student can wear these items both to school on the event day, and after the event is accomplished.</p></br></br></br>
                                                                            <p class="Ttitleall">A truly wonderful keepsake for students.</p>
                                                                            <div class="row align-items-center">
                                                                                <div class="col-lg-4">
                                                                                    <div class="sizing">
                                                                                        <div class="count">
                                                                                            <p><?php the_field('tshirt_heading', $loop->ID); ?>
                                                                                                <span data-type="CLOTH"
                                                                                                      data-price="0"
                                                                                                      data-total_price="0"
                                                                                                      class="d-block price">$0</span>
                                                                                            </p>
                                                                                            <p style="padding: 7px 0px;font-size: 10px;text-align: start;"><?php the_field('tshirt_content'); ?></p>
                                                                                            <div class="size-chart size-chart_<?php echo $child['id']; ?> d-none">
                                                                                                <p>Size chart (Required)</p>
                                                                                                <div class="size-ch"><a
                                                                                                            id="sizechartBtn"
                                                                                                            href="javascript:void(0);"
                                                                                                            required>?</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-8 increase increase_custm">
                                                                                    <div class="shirt">
                                                                                        <img class="wooco-img"
                                                                                             src="<?php the_field('tshirt_image'); ?>">
                                                                                    </div>
                                                                                    <div class="size-ch popup"><a
                                                                                                id="TshirtBtn"
                                                                                                href="javascript:void(0);">?</a>
                                                                                    </div>
                                                                                    <div class="up-down up-down-cstom">
                                                                                        <p>
                                                                                            <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                 id="minus1" width="40"
                                                                                                 height="20" class="minus"/>
                                                                                            <input id="clothqty-1"
                                                                                                   type="text" value="0"
                                                                                                   min="0" max="1"
                                                                                                   class="clothqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                   readonly name="cloth-1"/>
                                                                                            <img id="add1"
                                                                                                 src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                 width="40" height="20"
                                                                                                 class="add Add_<?php echo $child['id']; ?>"/>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endwhile; ?>
                                                                        <div class="row">
                                                                            <div class="col-lg-6 d-none sizeChart_<?php echo $child['id']; ?>">
                                                                                <label for="exampleInputEmail1"
                                                                                       class="cstom-label">Child</label>
                                                                                <div class="">
                                                                                    <select id="childSize-1" required
                                                                                            Placeholder="Junior or Senior"
                                                                                            name="childSize-1"
                                                                                            class="childSize form-control form_control_cstom">
                                                                                        <option value="">Choose</option>
                                                                                        <?php
                                                                                        $args = array(
                                                                                                'post_type' => 'tshirt-size',
                                                                                                'post_status' => 'publish',
                                                                                                'orderby' => 'date',
                                                                                                'order' => 'ASC',
                                                                                        );
                                                                                        $loop = new WP_Query($args);
                                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                                            ?>
                                                                                            <option value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                                                                                        <?php endwhile; ?></select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group group">
                                                                        <div class="for-back-color">
                                                                            <div class="row align-items-center">
                                                                                <?php
                                                                                $args = array(
                                                                                        'post_type' => 'tshirt-details',
                                                                                        'post_status' => 'publish',
                                                                                        'posts_per_page' => 1,
                                                                                        'orderby' => 'date',
                                                                                        'order' => 'ASC',
                                                                                );
                                                                                $loop = new WP_Query($args);
                                                                                while ($loop->have_posts()) : $loop->the_post();
                                                                                    ?>
                                                                                    <input type="hidden" class="hatPrice"
                                                                                           value="<?php the_field('hat_price', $loop->ID); ?>">
                                                                                    <input type="hidden" class="hatheading"
                                                                                           value="<?php the_field('hat_heading', $loop->ID); ?>">
                                                                                    <input type="hidden" class="hatContent"
                                                                                           value="<?php the_field('hat_content', $loop->ID); ?>">
                                                                                    <input type="hidden" class="hatImage"
                                                                                           value="<?php the_field('hat_image', $loop->ID); ?>">
                                                                                    <div class="col-lg-4">
                                                                                        <div class="sizing">
                                                                                            <div class="count">
                                                                                                <p><?php the_field('hat_heading', $loop->ID); ?>
                                                                                                    <span data-type="HAT"
                                                                                                          data-price="0"
                                                                                                          data-total_price="0"
                                                                                                          class="d-block price">$0</span>
                                                                                                </p>
                                                                                                <p style="font-size: 10px;text-align: start;"><?php the_field('hat_content'); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-8 increase increase_custm">
                                                                                        <div class="hat">
                                                                                            <img class="wooco-img"
                                                                                                 src="<?php the_field('hat_image'); ?>">
                                                                                        </div>
                                                                                        <!--                            <div class="popup">-->
                                                                                        <div class="size-ch popup"><a
                                                                                                    id="hatBtn"
                                                                                                    href="javascript:void(0);">?</a>
                                                                                        </div>
                                                                                        <!--                            </div>-->
                                                                                        <div class="up-down up-down-cstom">
                                                                                            <p>
                                                                                                <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                     id="minus1" width="40"
                                                                                                     height="20"
                                                                                                     class="minus"/>
                                                                                                <input id="hatqty-1"
                                                                                                       type="text" value="0"
                                                                                                       min="0" max="1"
                                                                                                       class="hatqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                       readonly
                                                                                                       name="hat-1"/>
                                                                                                <img id="add1"
                                                                                                     src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                     width="40" height="20"
                                                                                                     class="add"/>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endwhile; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['cloth_size']))) { ?>
                                                                    <div class="form-group group">
                                                                        <?php
                                                                        $args = array(
                                                                                'post_type' => 'tshirt-details',
                                                                                'post_status' => 'publish',
                                                                                'posts_per_page' => 1,
                                                                                'orderby' => 'date',
                                                                                'order' => 'ASC',
                                                                        );
                                                                        $loop = new WP_Query($args);
                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                            ?>
                                                                            <input type="hidden" class="tshirtPrice"
                                                                                   value="<?php the_field('tshirt_price', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirthatcontent"
                                                                                   value="<?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtheading"
                                                                                   value="<?php the_field('tshirt_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtContent"
                                                                                   value="<?php the_field('tshirt_content', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtImage"
                                                                                   value="<?php the_field('tshirt_image', $loop->ID); ?>">
                                                                            <h3 class="heading"><?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?></h3>
                                                                            <p class="Ttitleall">A fantastic addition to your student’s school colour blast event experience. </p></br>
                                                                            <p class="Ttitleall">Your student can wear these items both to school on the event day, and after the event is accomplished.</p></br></br></br>
                                                                            <p class="Ttitleall">A truly wonderful keepsake for students.</p>
                                                                            <div class="row align-items-center">
                                                                                <div class="col-lg-4">
                                                                                    <div class="sizing">
                                                                                        <div class="count">
                                                                                            <p><?php the_field('tshirt_heading', $loop->ID); ?>
                                                                                                <span data-type="CLOTH"
                                                                                                      data-price="0"
                                                                                                      data-total_price="0"
                                                                                                      class="d-block price">$0</span>
                                                                                            </p>
                                                                                            <p style="padding: 7px 0px;font-size: 10px;text-align: start;"><?php the_field('tshirt_content'); ?></p>
                                                                                            <div class="size-chart size-chart_<?php echo $child['id']; ?> d-none">
                                                                                                <p>Size chart (Required)</p>
                                                                                                <div class="size-ch"><a
                                                                                                            id="sizechartBtn"
                                                                                                            href="javascript:void(0);"
                                                                                                            required>?</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-8 increase increase_custm">
                                                                                    <div class="shirt">
                                                                                        <img class="wooco-img"
                                                                                             src="<?php the_field('tshirt_image'); ?>">
                                                                                    </div>
                                                                                    <div class="size-ch popup"><a
                                                                                                id="TshirtBtn"
                                                                                                href="javascript:void(0);">?</a>
                                                                                    </div>
                                                                                    <div class="up-down up-down-cstom">
                                                                                        <p>
                                                                                            <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                 id="minus1" width="40"
                                                                                                 height="20" class="minus"/>
                                                                                            <input id="clothqty-1"
                                                                                                   type="text" value="0"
                                                                                                   min="0" max="1"
                                                                                                   class="clothqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                   readonly name="cloth-1"/>
                                                                                            <img id="add1"
                                                                                                 src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                 width="40" height="20"
                                                                                                 class="add Add_<?php echo $child['id']; ?>"/>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endwhile; ?>
                                                                        <div class="row">
                                                                            <div class="col-lg-6 d-none sizeChart_<?php echo $child['id']; ?>">
                                                                                <label for="exampleInputEmail1"
                                                                                       class="cstom-label">Child 1</label>
                                                                                <div class="">
                                                                                    <select id="childSize-1" required
                                                                                            Placeholder="Junior or Senior"
                                                                                            name="childSize-1"
                                                                                            class="childSize form-control form_control_cstom">
                                                                                        <option value="">Choose</option>
                                                                                        <?php
                                                                                        $args = array(
                                                                                                'post_type' => 'tshirt-size',
                                                                                                'post_status' => 'publish',
                                                                                                'orderby' => 'date',
                                                                                                'order' => 'ASC',
                                                                                        );
                                                                                        $loop = new WP_Query($args);
                                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                                            ?>
                                                                                            <option value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                                                                                        <?php endwhile; ?></select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['hat_number']))) { ?>
                                                                    <?php
                                                                    $args = array(
                                                                            'post_type' => 'tshirt-details',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => 1,
                                                                            'orderby' => 'date',
                                                                            'order' => 'ASC',
                                                                    );
                                                                    $loop = new WP_Query($args);
                                                                    while ($loop->have_posts()) : $loop->the_post();
                                                                        ?>
                                                                        <input type="hidden" class="hatPrice"
                                                                               value="<?php the_field('hat_price', $loop->ID); ?>">
                                                                        <input type="hidden" class="hatheading"
                                                                               value="<?php the_field('hat_heading', $loop->ID); ?>">
                                                                        <input type="hidden" class="hatContent"
                                                                               value="<?php the_field('hat_content', $loop->ID); ?>">
                                                                        <input type="hidden" class="hatImage"
                                                                               value="<?php the_field('hat_image', $loop->ID); ?>">
                                                                        <div class="form-group group">
                                                                            <div class="for-back-color">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-lg-4">
                                                                                        <div class="sizing">
                                                                                            <div class="count">
                                                                                                <p><?php the_field('hat_heading', $loop->ID); ?>
                                                                                                    <span data-type="HAT"
                                                                                                          data-price="0"
                                                                                                          data-total_price="0"
                                                                                                          class="d-block price"><?php the_field('hat_price', $loop->ID); ?></span>
                                                                                                </p>
                                                                                                <p style="font-size: 10px;text-align: start;">
                                                                                                   <?php the_field('hat_content', $loop->ID); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-8 increase increase_custm">
                                                                                        <div class="hat">
                                                                                            <img class="wooco-img"
                                                                                                 src="<?php the_field('hat_image', $loop->ID); ?>">
                                                                                        </div>
                                                                                        <!--                            <div class="popup">-->
                                                                                        <div class="size-ch popup"><a
                                                                                                    id="hatBtn"
                                                                                                    href="javascript:void(0);">?</a>
                                                                                        </div>
                                                                                        <!--                            </div>-->
                                                                                        <div class="up-down up-down-cstom">
                                                                                            <p>
                                                                                                <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                     id="minus1" width="40"
                                                                                                     height="20"
                                                                                                     class="minus"/>
                                                                                                <input id="hatqty-1"
                                                                                                       type="text" value="0"
                                                                                                       min="0" max="1"
                                                                                                       class="hatqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                       readonly
                                                                                                       name="hat-1"/>
                                                                                                <img id="add1"
                                                                                                     src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                     width="40" height="20"
                                                                                                     class="add"/>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endwhile; ?>
                                                                <?php } else { ?>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="hr-line"></div>
                                                            <div class="payment_detail payment_detail_cstom">
                                                                <div class="row d-none">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label check-label"
                                                                                   for="flexCheckChecked">Payment
                                                                                Method: Credit Card</label>
                                                                            <span class="crd"
                                                                                  style="display: inline-flex; margin-bottom: 10px;">
                                                                            <input class="form-check-input"
                                                                                   name="card-check" type="radio"
                                                                                   value="1" id="flexCheckChecked"
                                                                                   checked>
                                                                        </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="credit-card-detail d-none">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label cstom-label"
                                                                               for="flexCheckChecked"
                                                                               style="margin-bottom: 8px;">Credit
                                                                            Card</label>
                                                                        <div class="vertical vertical_cstom"
                                                                             style="border-radius: 10px;">
                                                                            <input type="text"
                                                                                   class="form-control form_control_pay"
                                                                                   maxlength="20" id="card_number"
                                                                                   name="card_number"
                                                                                   aria-describedby="emailHelp" required>
                                                                        </div>
                                                                        <div class="card_cvv">
                                                                            <div class="row" style="margin-top: 10px;">
                                                                            <div class="col-lg-4">
                                                                                <label  class="form-check-label cstom-label" >Expiry Month</label>
                                                                               <input required type="text" class="form-control" style="margin-top: 8px;background: #018bb3;border-radius: 10px;color: #fff;" placeholder="MM" maxlength="2" id="expiry_month" name="expiry_month">
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <label  class="form-check-label cstom-label" >Expiry Year</label>
                                                                                <input required type="text" class="form-control" style="margin-top: 8px;background: #018bb3;border-radius: 10px;color: #fff;" placeholder="YY" maxlength="2" id="expiry_year" name="expiry_year">
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <label  class="form-check-label cstom-label" >CVV</label>
                                                                                <input type="text" class="form-control" style="margin-top: 8px;background: #018bb3;border-radius: 10px;color: #fff;" placeholder="CVV" maxlength="3" id="cvv" name="cvv">
                                                                            </div>
                                                                        </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <div class="cardholder-detail d-none">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label cstom-label"
                                                                               style="margin-top: 10px;">Cardholder
                                                                            Name</label>
                                                                        <input type="text" id="name_on_card"
                                                                               class="form-control form_control_cstom"
                                                                               name="name_on_card">
                                                                    </div>
                                                                </div>
                                                                <div class="" style="float: right;margin-top: 16px;">
                                                                    <div class="form-check tott">
                                                                        <div class="total">
                                                                            <input type="hidden" name="total_price"
                                                                                   id="total_price_custom<?php echo $key; ?>"
                                                                                   value="0">
                                                                            <label for="exampleInputPassword1"
                                                                                   class="subtotal"
                                                                                   id="total_price_detail_custom<?php echo $key; ?>">Total:
                                                                                $0 </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row pb-1">
                                                                    <div class="col-lg-6">
                                                                        <div class="copy_link">
                                                                            <label style="font-size: 17px;">Merchandise Order Close Date: <br><p style="font-size: 17px;   text-align: center;margin-top: 10px;"><?php echo get_field('merch_order_end_date',$child['school_id']); ?></p></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="next" style="margin-top: 10px;">
                                                                    <input type="button"
                                                                           class="btn btn-primary float-right step_3 step_xxx"
                                                                           data-child_id="<?php echo $child['id']; ?>"
                                                                           value="submit"></input>
                                                                </div>
                                                            </div>
                                                            <script type="text/javascript"
                                                                    src="https://js.stripe.com/v3/"></script>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php $events = get_field('fundraising_event', $school_id['school_id'], false);
                                            if ($events == 'Yes') {
                                                if($child['hide_profile']=='No'){
                                                ?>
                                                <div class="goal_summary">
                                                    <div class="fundraise_graph" style="width: 75%;">
                                                        <div class="fund">
                                                            <!-- <span class="fund_left lft">Progress</span> -->
                                                            <span class="fund_right cent">Funds Raised</span>
                                                            <span class="fund_right rght">Goal</span>
                                                        </div>
                                                        <?php
                                                        $raisedAmount = round($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount']);
                                                        $raisedAmounts = $raisedAmount * 100;
                                                        $totalGoalAmount = round($child_fundpage['data']['goal-amount']);
                                                        if(!empty($raisedAmount)){
                                                            $maxDonation = $raisedAmounts/$totalGoalAmount;
                                                        }else{
                                                            $maxDonation = 0;
                                                        }
                                                        ?>
                                                        <div class="school-light-grey" style="border-radius: 10px;">
                                                            <div class="school-red"
                                                                 style="border-top-left-radius: 15px;    border-bottom-left-radius: 15px;max-width:<?php echo $maxDonation; ?>%"></div>
                                                        </div>
                                                        <div class="fund">
                                                            <span class="fund_left lft">$<?php echo number_format($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount'],2); ?></span>
                                                            <!--span class="fund_right cent">Funds Raised</span-->
                                                            <span class="fund_right rght">$<?php echo number_format($child_fundpage['data']['goal-amount'],2); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } }if($child['hide_profile']=='No'){?>
                                            <div class="fundrase_profile wew">
                                                <div class="row">
                                                    <?php $fundraisingEvent = get_field('fundraising_event', $school_id['school_id']);
                                                    if ($fundraisingEvent == 'Yes') {
                                                        ?>
                                                        <div class="col-lg-6">
                                                            <div class="classes">
                                                                <?php
                                                                if((!empty($user_id)) && (isset($user_id))){
                                                                    $school_id = getChildSchoolId($user_id);
                                                                }
                                                                $schoolFundEndDate = get_field('event_date', $school_id['school_id']);
                                                                $schoolFundEndDate = str_replace('/', '-', $schoolFundEndDate);
                                                                $schoolFundEndDate = date('d-m-Y', strtotime($schoolFundEndDate));
                                                                $schoolFundEndDateMessage = date('d-m-Y', strtotime($schoolFundEndDate));
                                                                $schoolFundEndDate = $schoolFundEndDate .TIME_ZONE_SET;
                                                                date_default_timezone_set('Australia/Brisbane');
                                                                $currentDate = date('d-m-Y H:i:s');
                                                                if (strtotime($schoolFundEndDate) > strtotime($currentDate)):
                                                                    ?>
                                                                    <h3>Fundrasing closes</h3>
                                                                    <div class="d_h_m_s">
                                                                        <div class="d"><span id="day-<?php echo $key; ?>"
                                                                                             class="day"></span>
                                                                            <p>Days</p></div>
                                                                        <div class="d"><span id="hour-<?php echo $key; ?>"
                                                                                             class="hour"></span>
                                                                            <p>Hours</p></div>
                                                                        <div class="d"><span id="minute-<?php echo $key; ?>"
                                                                                             class="minute"></span>
                                                                            <p>Minute</p></div>
                                                                        <div class="d"><span id="second-<?php echo $key; ?>"
                                                                                             class="second"></span>
                                                                            <p>Second</p></div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <h3>Registrations Closed</h3>
                                                                    <h3><?php echo $schoolFundEndDateMessage; ?></h3>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                        $events = get_field('fundraising_event', $school_id['school_id'], false);
                                                        if ($events == 'Yes') {
                                                        if(trim($child['nickname'])){
                                                            $donatName = $child['nickname'];
                                                        }else{
                                                            $donatName = stripslashes($child['firstname']).', '.stripslashes(ucfirst($child['lastname'][0]));
                                                        }
                                                        if ($class_one_hide == 1){
                                                        $class_one = ', '.stripslashes($child['child_set_a']);
                                                        }
                                                        if ($class_two_hide == 1){
                                                            $class_two = ', '.stripslashes($child['child_set_b']);
                                                        }
                                                        if ($class_three_hide == 1){
                                                            $class_three = ', '.stripslashes($child['child_set_c']);
                                                        }
                                                        if ($class_four_hide == 1){
                                                            $class_four = ', '.stripslashes($child['child_set_d']);
                                                        }
                                                        $dona_tName = $donatName.''.$class_one.''.$class_two.''.$class_three.''.$class_four;
                                                        if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                                    $eventName = strtolower(str_replace(' ', '', $child['nickname'] . '' . $child['event_name']));
                                                                } else {
                                                                    $eventName = strtolower(str_replace(' ', '', $child['firstname'] . '' . $child['event_name']));
                                                                }
                                                        ?>
                                                        <div class="col-lg-6">
                                                            <div class="profile shared_thre"
                                                                 style="margin-left:<?php if ($fundraisingEvent == 'No') { ?>15%;<?php } ?>">
                                                                <h3>Share Profile</h3>
                                                                <div class="f_en_cop">
                                                                    <?php
                                                                    $socialData = array(
                                                                            //'link' => home_url() . '/student-fundraising-page/?event='.stripslashes($eventName).urlencode('&child-id').'='.base64_encode($child['id']),
                                                                            'link' => home_url().'/profile/?'.$child['child_or_school_name'],
                                                                            'title' => $dona_tName,
                                                                            'email' => $user->data->user_email,
                                                                            'childID' => $child['id'],
                                                                            'user_id' => $user_id
                                                                    );
                                                                    echo social_share($socialData);
                                                                    ?>
                                                                </div>
                                                        <span class="d-none" id="copyText2_<?php echo $child['id']; ?>"><?php echo home_url().'/profile/?'.$child['child_or_school_name']; //echo home_url() . '/student-fundraising-page/?event='.stripslashes($eventName).'&child-id='.base64_encode($child['id']);?>
                                                        </span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div> <?php } ?>
                                        </div>
                                        <?php $events = get_field('fundraising_event', $school_id['school_id'], false);if ($events == 'Yes') {
                                            if($child['hide_profile']=='No'){
                                            ?>
                                            <div class="Badges_earnt" style="margin-top:10px">
                                                <div><h3>Badges Earnt</h3></div>
                                                <?php
                                                $maxBadgeAmount = getfundraiseLeaderBoard($school_id['school_id']);
                                                $maxBadgeAmount = round($maxBadgeAmount[0]['sum']);
                                                if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                    $eventName = strtolower(str_replace(' ', '', $child['nickname'] . '' . $child['event_name']));
                                                } else {
                                                    $eventName = strtolower(str_replace(' ', '', $child['firstname'] . '' . $child['event_name']));
                                                }
                                                $countTimer = count($donation['latest_donation']);
                                                get_template_part('include/studentParent/fundraiser/child-badges', null, array(
                                                                'data' => array(
                                                                        'child_id' => $child['id'],
                                                                        'total_fund_raised' => $donation['total_fund_raised'],
                                                                        'max_fund_raised' => $maxBadgeAmount,
                                                                        'number_of_badge' => 10,
                                                                        'child_school_name' => $eventName,
                                                                        'childID' => $child['id'],
                                                                        'countdonation' => $countTimer,
                                                                        'user_id' => $user_id,
                                                                        'schooID' => $school_id['school_id']
                                                                    //'goal_amount'   => $child_fundpage['data']['goal-amount'],
                                                                ))
                                                );
                                                ?>
                                            </div>
                                        <?php } }?>
                                    </div>
                                    <?php if($child['hide_profile']=='No'){ ?>
                                    <div class="col-lg-4">
                                        <?php $events = get_field('fundraising_event', $school_id['school_id'], false);if ($events == 'Yes') {?>
                                            <div class="donat" style="margin-top:-28px;">
                                                <h3>Latest Donation to
                                                    <span>
                                                        <?php 
                                                        if (($class_one_hide == 1) && !empty(trim($child['child_set_a']))){
                                                            $class_ones = ', '.stripslashes($child['child_set_a']);
                                                        }
                                                        if (($class_two_hide == 1) && !empty(trim($child['child_set_b']))){
                                                            $class_twos = ', '.stripslashes($child['child_set_b']);
                                                        }
                                                        if (($class_three_hide == 1) && !empty(trim($child['child_set_d']))){
                                                            $class_threes = ', '.stripslashes($child['child_set_c']);
                                                        }
                                                        if (($class_four_hide == 1) && !empty(trim($child['child_set_d']))){
                                                            $class_fours = ', '.stripslashes($child['child_set_d']);
                                                        }
                                                        $childClas = $class_ones.''.$class_twos.''.$class_threes.''.$class_fours;
                                                        if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                            echo stripslashes($child['nickname']) . '' . stripslashes($childClas);
                                                        } else {
                                                            echo stripslashes($child['firstname']) . ' ' . stripslashes(trim($child['lastname'][0])) . '' . stripslashes($childClas);
                                                        }
                                                    if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                    $event_Name = strtolower(str_replace(' ', '', $child['nickname'] . '' . $child['event_name']));
                                                } else {
                                                    $event_Name = strtolower(str_replace(' ', '', $child['firstname'] . '' . $child['event_name']));
                                                }?></span></h3>
                                                <?php //pr($donation);
                                                $donation1 = getRecentSchoolDonation($school_id['school_id']);
                                                $child_fundpage = get_child_fundraising_page($child['parent_id'], $child['firstname']);
                                                get_template_part('include/studentParent/fundraiser/latest-donation', null, array(
                                                                'data' => array(
                                                                        'latest_tdonation' => $donation,
                                                                        'latest_donation' => $donation1,
                                                                        'eventName' => $event_Name,
                                                                        'childID' => $child['id'],
                                                                    /*'child_id' => @$child_fundpage['data']['id'],
                                                                    'parent_id' =>$child['parent_id'],
                                                                    'fundraising_page_id' =>@$child_fundpage['data']['id'],
                                                                    'user_id' =>get_current_user_id(),*/
                                                                ))
                                                );
                                                ?>
                                            </div>
                                        <?php } ?>
                                        <?php $events = get_field('incentive_prizes', $school_id['school_id'], false);if ($events == 'yes') { ?>
                                            <div class="prize" style="margin-top: 10px;">
                                                <div>
                                                    <div class="st_prize">
                                                        <h3>Incentive Stars</h3>
                                                        <p>Fundraisers are highly appreciated and rewarded for their efforts. The value of the gift card credit increases as the funds raised reach higher milestones. Here's a breakdown of how it all works:</p>
                                                        <p>First Star: When you raise $20.00, you earn your first star and receive a $5.00 gift card credit</p>
                                                        <p>Second Star: Upon reaching $40.00 in total funds raised, you earn your second star and are rewarded with a $10.00 gift card.
                                                        Additional Stars: For every subsequent $40.00 you raise, you earn another star and receive an additional $10.00 in gift card credit.
                                                        </p>
                                                        <p>As you continue to raise more money, you earn more incentive stars, unlocking even greater rewards for your dedication and hard work. Within 14 days after the fundraising campaign concludes, your gift card credit will be delivered to the email address you registered with. The gift card credit can be used at over 500 online and in-store retailers, providing you with a wide range of choices
                                                        </p>
                                                    </div>
                                                    <?php
                                                    $star_prize = round($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount'], 2);
                                                    ?>
                                                    <div class="st_star">
                                                        <span class="timer">
                                                            <?php if ($star_prize >=20) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >=40) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 80) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 120) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 160) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 200) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 240) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 280) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 320) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 360) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize > 400) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 500) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } ?>
                                                         </span>
                                                        </div>
                                                    </div>
                                                    <div class="giftImageCard">
                                                    <div class="giftCard">
                                                        <h3>Gift Card Earnt <br>$<?php
                                                            $value = round($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount'],2);
                                                            if($value){
                                                            $items = array();
                                                            for ($x = 0; $x <= $value; $x+=20) {
                                                              $items[] = $x;
                                                            }
                                                            echo max($items)/4;
                                                        }else{
                                                            echo '0';
                                                        }?>
                                                        </h3>
                                                        <div style="max-width: 80%;text-align: center;">
                                                        <?php //$giftCardDate =get_field('incentive_end_date', $school_id['school_id']); ?>
                                                        <p>Gift card credit will be emailed to the email address you have registered with by <span><?php echo $giftCardDate; ?></span></p>
                                                        </div>
                                                    </div>
                                                    <div Class="giftImage">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Rebel-Gift-Card.png">
                                                    <img  src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Smiggle-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Kmart-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Priceline-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Event-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Coles-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/JBHiFi-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/EB-Games-Gift-Card.png">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } }?>
                                    </div>
                                </div>
                                <?php ++$j !== $totalChild; ?>
                                <?php if (++$i !== $totalChild): ?>
                                <div class="divider"></div>
                                <?php endif; ?>
                            <?php }else{ ?>
                                <h3 style="margin-bottom: 0px;font-size: 30px;">Child <?php echo $j; ?></h3>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="user-detail">
                                            <div class="user-n-ab" style="margin-bottom: -34px;"> 
                                                <?php 
                                                   if(get_user_meta($user_id,'avtar_'.$child['id'],true)){
                                                        $attecmentid = get_user_meta($user_id,'avtar_'.$child['id'],true);
                                                        $image =  wp_get_attachment_url($attecmentid);
                                                    }else{
                                                        $image =  wp_get_attachment_url(9438);
                                                    }
                                                ?>
                                                <div>
                                                    <a class="imagechanges" href="<?php echo home_url().'/profile/?'.$child['child_or_school_name']; //echo home_url() . '/student-fundraising-page/?child-id=' . base64_encode($child['id']); ?>"><img style="max-width: 100%;" src="<?php echo  $image; ?>"></a>
                                                     <?php 
                                                     $imageChange = get_field('change_profile', $school_id['school_id']); 
                                                     if($imageChange == 'on'){
                                                     ?>
                                                    <div>
                                                        <button id="Changed_image" data-size="<?php echo 'image'; ?>" class="btn btn-warning  Allimage_<?php echo $child['id']; ?> " data-childid="<?php echo $child['id']; ?>">Change image</button>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                                <div class="name_abc">
                                                    <h3><?php if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                            echo stripslashes($child['nickname']);
                                                        } else {
                                                            echo stripslashes($child['firstname']) . ' ' . stripslashes($child['lastname']);
                                                        } ?></h3>
                                                    <div class="row m-0">
                                                        <div class="col-lg-6">
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-9">
                                                                    <div class="copy_link">
                                                                        <label>First Name</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 parentLayout">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="<?php echo stripslashes($child['firstname']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row px-2 align-items-center">
                                                                <div class="col-lg-9">
                                                                    <div class="copy_link">
                                                                        <label>Last Name</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 parentLayout">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="<?php echo stripslashes($child['lastname']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-9">
                                                                    <div class="copy_link">
                                                                        <label>Class/Group</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 parentLayout">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="<?php echo stripslashes($child['child_set_a']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty(trim($child['child_set_b']))) { ?>
                                                                <div class="row align-items-center" style="padding: 7px 0;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Class/Group</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['child_set_b']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (!empty(trim($child['child_set_c']))) { ?>
                                                                <div class="row align-items-center" style="padding: 7px 0;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Class/Group</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['child_set_c']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (!empty(trim($child['child_set_d']))) { ?>
                                                                <div class="row align-items-center" style="padding: 7px 0;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Class/Group</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo stripslashes($child['child_set_d']); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <div>
                                                            <button id="Changed_child_details" class="las_act_but btn btn-warning edit_compaign_model <?php if(trim($child['firstname']) == 'Sponsors Page'){ echo 'd-none'; }?>" data-class="<?= $child['child_set_a'] ?>" data-fund="<?= $child['comp_id'] ?>" data-title="<?= $child['title'] ?>" data-target="#myModal" id="myBtn " data-ChildID="<?php echo $child['id'] ; ?>" >Edit</button>
                                                    </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <?php if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') { ?>
                                                                <div class="row align-items-center"
                                                                     style="padding-bottom: 10px;">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Nickname</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo $child['nickname']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if (trim($child['cloth_number'])) { ?>
                                                                <div class="row align-items-center">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>T-Shirt</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo $child['cloth_size']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $sizeEventDate = get_field('merch_order_end_date', $school_id['school_id']);
                                                                $sizeEventDate = str_replace('/', '-', $sizeEventDate);
                                                                $sizeEventDate = date('d-m-Y', strtotime($sizeEventDate));
                                                                $sizeEventDate = $sizeEventDate .TIME_ZONE_SET;
                                                                date_default_timezone_set('Australia/Brisbane');
                                                                $currentDate = date('d-m-Y H:i:s');
                                                                if (strtotime($sizeEventDate) >= strtotime($currentDate)):
                                                                ?>
                                                                <div class="row align-items-center">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link" style="margin-top: 5px;">
                                                                            <label>Change Size</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 ">
                                                                        <div class="form-group">
                                                                            <button id="Changed_size" data-size="<?php echo $child['cloth_size']; ?>" class="btn btn-warning  Allsize_<?php echo $child['id']; ?> tShirtSize" data-childid="<?php echo $child['id']; ?>">Change Size</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif;
                                                                }
                                                            if (trim($child['hat_number'])) { ?>
                                                                <div class="row px-2 align-items-center">
                                                                    <div class="col-lg-9">
                                                                        <div class="copy_link">
                                                                            <label>Bucket Hat</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-9 parentLayout">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" readonly
                                                                                   value="<?php echo (!empty($child['hat_number']) && (isset($child['hat_number'])) && (($child['hat_number'])) !== ' ') ? 'YES' : 'NO'; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }
                                                            $sizeEventDate = get_field('merch_order_end_date', $school_id['school_id']);
                                                            $sizeEventDate = str_replace('/', '-', $sizeEventDate);
                                                            $sizeEventDate = date('d-m-Y', strtotime($sizeEventDate));
                                                            $sizeEventDate = $sizeEventDate .TIME_ZONE_SET;
                                                            //$daysbefore = date('d-m-Y', strtotime('-20 days', strtotime($sizeEventDate)));
                                                            $currentDate = date('d-m-Y H:i:s');
                                                            $merch = get_field('merchandise', $school_id['school_id']);
                                                            if ($merch == 'yes' && (strtotime($sizeEventDate) >= strtotime($currentDate))) {
                                                                if (empty(trim($child['cloth_size'])) && empty(trim($child['hat_number']))) {
                                                                    ?>
                                                                    <div class="row align-items-center"
                                                                         style="    margin-top: 10px;">
                                                                        <div class="col-lg-9">
                                                                            <div class="copy_link">
                                                                                <label>Merchandise</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-9 parentLayout">
                                                                            <div class="form-group for-button">
                                                                                <button id="order_button_both"
                                                                                        class="btn btn-warning order_button AllModalbutton_<?php echo $child['id']; ?>"
                                                                                        data-childid="<?php echo $child['id']; ?>">
                                                                                    Order
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['cloth_size']))) { ?>
                                                                    <div class="row align-items-center"
                                                                         style="    margin-top: 10px;">
                                                                        <div class="col-lg-9">
                                                                            <div class="copy_link">
                                                                                <label>Merchandise</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-9 parentLayout">
                                                                            <div class="form-group for-button">
                                                                                <button id="order_button_tshirt"
                                                                                        class="btn btn-warning order_button AllModalbutton_<?php echo $child['id']; ?>"
                                                                                        data-childid="<?php echo $child['id']; ?>">
                                                                                    Order
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['hat_number']))) { ?>
                                                                    <div class="row align-items-center"
                                                                         style="    margin-top: 10px;">
                                                                        <div class="col-lg-9">
                                                                            <div class="copy_link">
                                                                                <label>Merchandise</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-9 parentLayout">
                                                                            <div class="form-group for-button">
                                                                                <button id="order_button_hat"
                                                                                        class="btn btn-warning order_button AllModalbutton_<?php echo $child['id']; ?>"
                                                                                        data-childid="<?php echo $child['id']; ?>">
                                                                                    Order
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } } 
                                                                $events = get_field('fundraising_event', $school_id['school_id'], false);
                                                                if ($events == 'Yes') {
                                                                ?>
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-9 ">
                                                                    <div class="form-group">
                                                                        <button id="show_donations" class="btn btn-warning alldonations_<?php echo $child['id']; ?>" data-childid="<?php echo $child['id']; ?>">Donations</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                             <!-- This model for show donations -->
                                                <div id="donationsAll_<?php echo $child['id']; ?>" class="modal fade modal-donation" role="dialog">
                                                    <div class="modal-content modal_btn forchilddonorparent">
                                                        <button class="donations" style="position: absolute;right: 27px;font-size: 18px;font-weight: bold;">X</button>
                                                        <div class="form-group group">
                                                            <h3 class="heading">Show Donations</h3>
                                                        </div>
                                                        <div class="table_responsive">
                                                    <div class="report" id="printTable">
                                                        <div class="report" id="printTable">
                                                                <table class="table dataTables" id="dataTables">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">Participant Profile Name</th>
                                                                    <th scope="col">Participant First Name</th>
                                                                    <th scope="col">Participant Last Name</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Participant Class/Group</th>
                                                                    <th scope="col">Event Name</th>
                                                                    <th scope="col">List of donors</th>
                                                                    <th scope="col">Donation</th>
                                                                    <th scope="col">Refunded Donation</th>
                                                                    <th scope="col">Donation Dates </th>
                                                                    <th scope="col">Bank Chargeback List</th>
                                                                    <th scope="col">Bank Chargeback Amount</th>
                                                                    <th scope="col">Organisation ABN Number</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="serach_tables">
                                                                <?php
                                                                $child_id = $child['id'];
                                                                $funddatas = manageDonationByEachDonationOnbothDashboard($child_id); 
                                                                if(!empty($funddatas)){
                                                                    foreach ($funddatas as $funddata) {
                                                                        if(trim($funddata['nickname'])){
                                                                                $titleName = $funddata['nickname'];
                                                                        }else{
                                                                            $titleName = stripslashes($funddata['firstname']).' '.stripslashes(ucfirst($funddata['lastname'][0]));
                                                                        }
                                                                        if(trim($funddata['child_set_b'])){
                                                                            $child_set_b = ', '.$funddata['child_set_b'];
                                                                        }
                                                                        if(trim($funddata['child_set_c'])){
                                                                            $child_set_c = ', '.$funddata['child_set_c'];
                                                                        }
                                                                        if(trim($funddata['child_set_d'])){
                                                                            $child_set_d = ', '.$funddata['child_set_d'];
                                                                        }
                                                                        $title_name = $titleName.', '.stripslashes($funddata['child_set_a']).''.stripslashes($child_set_b).''.stripslashes($child_set_c).''.stripslashes($child_set_d);
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo stripslashes($title_name); ?></td>
                                                                            <td><?php echo stripslashes($funddata['firstname']); ?></td>
                                                                            <td><?php echo stripslashes($funddata['lastname']); ?></td>
                                                                            <td><?php echo stripslashes($funddata['child_set_a']); ?></td>
                                                                            <td><?php if(trim($funddata['child_set_b'])) {echo stripslashes(trim($funddata['child_set_b']));} ?></td>
                                                                            <td><?php if(trim($funddata['child_set_c'])) {echo stripslashes(trim($funddata['child_set_c']));} ?></td>
                                                                            <td><?php if(trim($funddata['child_set_d'])) {echo stripslashes(trim($funddata['child_set_d']));} ?></td>
                                                                            <td><?php echo stripslashes($funddata['event_name']); ?></td>
                                                                            <td><?php if(empty($funddata['keep_safe'])){ echo removeBadWords($funddata['shipping_firstname']).' '.removeBadWords($funddata['shipping_lastname']);}else{ echo 'Anonymous';}  ?></td>
                                                                            <td><?php if($funddata['amount']){ echo DEFAULT_CURRENCY . round($funddata['amount'],2);}else{ echo DEFAULT_CURRENCY .'0.00';} ?></td>
                                                                            <td><?php if($funddata['refunded_donation']){ echo DEFAULT_CURRENCY . round($funddata['refunded_donation'],2);}else{ echo DEFAULT_CURRENCY .'0.00';} ?></td>
                                                                            <td><?php if($funddata['created_date']){echo $funddata['created_date'];} ?></td>
                                                                            <td><?php if($funddata['charge_by_done']){ echo $funddata['charge_by_done'];}else{ echo "--";} ?></td>
                                                                            <td><?php if($funddata['charge_amount']){ echo DEFAULT_CURRENCY . round($funddata['charge_amount'],2);}else{ echo DEFAULT_CURRENCY .'0.00';} ?></td>
                                                                            <td><?php if($funddata['abn']){ echo $funddata['abn'];} ?></td>
                                                                        </tr>
                                                                        <?php  } } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- This model for show donations -->
                                            <?php if (empty(trim($child['cloth_size'])) || empty(trim($child['hat_number']))) { ?>
                                                <div id="myModalboth_<?php echo $child['id']; ?>"
                                                     class="modal fade fundModal media_popup" role="dialog">
                                                    <!-- Modal content -->
                                                    <?php //pr($child) ;?>
                                                    <div class="modal-content">
                                                        <form method="POST" action=""
                                                              id="order_model_<?php echo $child['id']; ?>"
                                                              class="order_both">
                                                            <input type="hidden" name="eventName" value="<?php echo stripslashes($child['event_name']); ?>">
                                                            <input type="hidden" name="SchoolID" value="<?php echo $child['school_id']; ?>">
                                                            <input type="hidden" name="Child_id" id="model_childid"
                                                                   value="<?php echo $child['id']; ?>">
                                                            <input type="hidden" name="parent_id" id="model_parentid"
                                                                   value="<?php echo $child['parent_id']; ?>">
                                                            <input type="hidden" name="action"
                                                                   value="addStudentParenthatandshirt">
                                                            <button class="closed_model parent-model">X</button>
                                                            <div class="child_merchandise_1 child_merchandise_custm">
                                                                <?php if (empty(trim($child['cloth_size'])) && empty(trim($child['hat_number']))) {
                                                                    ?>
                                                                    <div class="form-group group">
                                                                        <?php
                                                                        $args = array(
                                                                                'post_type' => 'tshirt-details',
                                                                                'post_status' => 'publish',
                                                                                'posts_per_page' => 1,
                                                                                'orderby' => 'date',
                                                                                'order' => 'ASC',
                                                                        );
                                                                        $loop = new WP_Query($args);
                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                            ?>
                                                                            <input type="hidden" class="tshirtheading"
                                                                                   value="<?php the_field('tshirt_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirthatcontent"
                                                                                   value="<?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtPrice"
                                                                                   value="<?php the_field('tshirt_price', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtContent"
                                                                                   value="<?php the_field('tshirt_content', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtImage"
                                                                                   value="<?php the_field('tshirt_image', $loop->ID); ?>">
                                                                            <h3 class="heading"><?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?></h3>
                                                                            <p class="Ttitleall">A fantastic addition to your student’s school colour blast event experience. </p></br>
                                                                            <p class="Ttitleall">Your student can wear these items both to school on the event day, and after the event is accomplished.</p></br></br></br>
                                                                            <p class="Ttitleall">A truly wonderful keepsake for students.</p>
                                                                            <div class="row align-items-center">
                                                                                <div class="col-lg-4">
                                                                                    <div class="sizing">
                                                                                        <div class="count">
                                                                                            <p><?php the_field('tshirt_heading', $loop->ID); ?>
                                                                                                <span data-type="CLOTH"
                                                                                                      data-price="0"
                                                                                                      data-total_price="0"
                                                                                                      class="d-block price">$0</span>
                                                                                            </p>
                                                                                            <p style="padding: 7px 0px;font-size: 10px;text-align: start;"><?php the_field('tshirt_content'); ?></p>
                                                                                            <div class="size-chart size-chart_<?php echo $child['id']; ?> d-none">
                                                                                                <p>Size chart (Required)</p>
                                                                                                <div class="size-ch"><a
                                                                                                            id="sizechartBtn"
                                                                                                            href="javascript:void(0);"
                                                                                                            required>?</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-8 increase increase_custm">
                                                                                    <div class="shirt">
                                                                                        <img class="wooco-img"
                                                                                             src="<?php the_field('tshirt_image'); ?>">
                                                                                    </div>
                                                                                    <div class="size-ch popup"><a
                                                                                                id="TshirtBtn"
                                                                                                href="javascript:void(0);">?</a>
                                                                                    </div>
                                                                                    <div class="up-down up-down-cstom">
                                                                                        <p>
                                                                                            <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                 id="minus1" width="40"
                                                                                                 height="20" class="minus"/>
                                                                                            <input id="clothqty-1"
                                                                                                   type="text" value="0"
                                                                                                   min="0" max="1"
                                                                                                   class="clothqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                   readonly name="cloth-1"/>
                                                                                            <img id="add1"
                                                                                                 src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                 width="40" height="20"
                                                                                                 class="add Add_<?php echo $child['id']; ?>"/>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endwhile; ?>
                                                                        <div class="row">
                                                                            <div class="col-lg-6 d-none sizeChart_<?php echo $child['id']; ?>">
                                                                                <label for="exampleInputEmail1"
                                                                                       class="cstom-label">Child 1</label>
                                                                                <div class="">
                                                                                    <select id="childSize-1" required
                                                                                            Placeholder="Junior or Senior"
                                                                                            name="childSize-1"
                                                                                            class="childSize form-control form_control_cstom">
                                                                                        <option value="">Choose</option>
                                                                                        <?php
                                                                                        $args = array(
                                                                                                'post_type' => 'tshirt-size',
                                                                                                'post_status' => 'publish',
                                                                                                'orderby' => 'date',
                                                                                                'order' => 'ASC',
                                                                                        );
                                                                                        $loop = new WP_Query($args);
                                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                                            ?>
                                                                                            <option value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                                                                                        <?php endwhile; ?></select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group group">
                                                                        <div class="for-back-color">
                                                                            <div class="row align-items-center">
                                                                                <?php
                                                                                $args = array(
                                                                                        'post_type' => 'tshirt-details',
                                                                                        'post_status' => 'publish',
                                                                                        'posts_per_page' => 1,
                                                                                        'orderby' => 'date',
                                                                                        'order' => 'ASC',
                                                                                );
                                                                                $loop = new WP_Query($args);
                                                                                while ($loop->have_posts()) : $loop->the_post();
                                                                                    ?>
                                                                                    <input type="hidden" class="hatPrice"
                                                                                           value="<?php the_field('hat_price', $loop->ID); ?>">
                                                                                    <input type="hidden" class="hatheading"
                                                                                           value="<?php the_field('hat_heading', $loop->ID); ?>">
                                                                                    <input type="hidden" class="hatContent"
                                                                                           value="<?php the_field('hat_content', $loop->ID); ?>">
                                                                                    <input type="hidden" class="hatImage"
                                                                                           value="<?php the_field('hat_image', $loop->ID); ?>">
                                                                                    <div class="col-lg-4">
                                                                                        <div class="sizing">
                                                                                            <div class="count">
                                                                                                <p><?php the_field('hat_heading', $loop->ID); ?>
                                                                                                    <span data-type="HAT"
                                                                                                          data-price="0"
                                                                                                          data-total_price="0"
                                                                                                          class="d-block price">$0</span>
                                                                                                </p>
                                                                                                <p style="font-size: 10px;text-align: start;"><?php the_field('hat_content'); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-8 increase increase_custm">
                                                                                        <div class="hat">
                                                                                            <img class="wooco-img"
                                                                                                 src="<?php the_field('hat_image'); ?>">
                                                                                        </div>
                                                                                        <!--                            <div class="popup">-->
                                                                                        <div class="size-ch popup"><a
                                                                                                    id="hatBtn"
                                                                                                    href="javascript:void(0);">?</a>
                                                                                        </div>
                                                                                        <!--                            </div>-->
                                                                                        <div class="up-down up-down-cstom">
                                                                                            <p>
                                                                                                <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                     id="minus1" width="40"
                                                                                                     height="20"
                                                                                                     class="minus"/>
                                                                                                <input id="hatqty-1"
                                                                                                       type="text" value="0"
                                                                                                       min="0" max="1"
                                                                                                       class="hatqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                       readonly
                                                                                                       name="hat-1"/>
                                                                                                <img id="add1"
                                                                                                     src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                     width="40" height="20"
                                                                                                     class="add"/>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endwhile; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['cloth_size']))) { ?>
                                                                    <div class="form-group group">
                                                                        <?php
                                                                        $args = array(
                                                                                'post_type' => 'tshirt-details',
                                                                                'post_status' => 'publish',
                                                                                'posts_per_page' => 1,
                                                                                'orderby' => 'date',
                                                                                'order' => 'ASC',
                                                                        );
                                                                        $loop = new WP_Query($args);
                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                            ?>
                                                                            <input type="hidden" class="tshirtPrice"
                                                                                   value="<?php the_field('tshirt_price', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirthatcontent"
                                                                                   value="<?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtheading"
                                                                                   value="<?php the_field('tshirt_heading', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtContent"
                                                                                   value="<?php the_field('tshirt_content', $loop->ID); ?>">
                                                                            <input type="hidden" class="tshirtImage"
                                                                                   value="<?php the_field('tshirt_image', $loop->ID); ?>">
                                                                            <h3 class="heading"><?php the_field('tshirt_and_hat__top_heading', $loop->ID); ?></h3>
                                                                            <p class="Ttitleall">A fantastic addition to your student’s school colour blast event experience. </p></br>
                                                                            <p class="Ttitleall">Your student can wear these items both to school on the event day, and after the event is accomplished.</p></br></br></br>
                                                                            <p class="Ttitleall">A truly wonderful keepsake for students.</p>
                                                                            <div class="row align-items-center">
                                                                                <div class="col-lg-4">
                                                                                    <div class="sizing">
                                                                                        <div class="count">
                                                                                            <p><?php the_field('tshirt_heading', $loop->ID); ?>
                                                                                                <span data-type="CLOTH"
                                                                                                      data-price="0"
                                                                                                      data-total_price="0"
                                                                                                      class="d-block price">$0</span>
                                                                                            </p>
                                                                                            <p style="padding: 7px 0px;font-size: 10px;text-align: start;"><?php the_field('tshirt_content'); ?></p>
                                                                                            <div class="size-chart size-chart_<?php echo $child['id']; ?> d-none">
                                                                                                <p>Size chart (Required)</p>
                                                                                                <div class="size-ch"><a
                                                                                                            id="sizechartBtn"
                                                                                                            href="javascript:void(0);"
                                                                                                            required>?</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-8 increase increase_custm">
                                                                                    <div class="shirt">
                                                                                        <img class="wooco-img"
                                                                                             src="<?php the_field('tshirt_image'); ?>">
                                                                                    </div>
                                                                                    <div class="size-ch popup"><a
                                                                                                id="TshirtBtn"
                                                                                                href="javascript:void(0);">?</a>
                                                                                    </div>
                                                                                    <div class="up-down up-down-cstom">
                                                                                        <p>
                                                                                            <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                 id="minus1" width="40"
                                                                                                 height="20" class="minus"/>
                                                                                            <input id="clothqty-1"
                                                                                                   type="text" value="0"
                                                                                                   min="0" max="1"
                                                                                                   class="clothqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                   readonly name="cloth-1"/>
                                                                                            <img id="add1"
                                                                                                 src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                 width="40" height="20"
                                                                                                 class="add Add_<?php echo $child['id']; ?>"/>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endwhile; ?>
                                                                        <div class="row">
                                                                            <div class="col-lg-6 d-none  sizeChart_<?php echo $child['id']; ?>">
                                                                                <label for="exampleInputEmail1"
                                                                                       class="cstom-label">Child 1</label>
                                                                                <div class="">
                                                                                    <select id="childSize-1" required
                                                                                            Placeholder="Junior or Senior"
                                                                                            name="childSize-1"
                                                                                            class="childSize form-control form_control_cstom">
                                                                                        <option value="">Choose</option>
                                                                                        <?php
                                                                                        $args = array(
                                                                                                'post_type' => 'tshirt-size',
                                                                                                'post_status' => 'publish',
                                                                                                'orderby' => 'date',
                                                                                                'order' => 'ASC',
                                                                                        );
                                                                                        $loop = new WP_Query($args);
                                                                                        while ($loop->have_posts()) : $loop->the_post();
                                                                                            ?>
                                                                                            <option value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                                                                                        <?php endwhile; ?></select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (empty(trim($child['hat_number']))) { ?>
                                                                    <?php
                                                                    $args = array(
                                                                            'post_type' => 'tshirt-details',
                                                                            'post_status' => 'publish',
                                                                            'posts_per_page' => 1,
                                                                            'orderby' => 'date',
                                                                            'order' => 'ASC',
                                                                    );
                                                                    $loop = new WP_Query($args);
                                                                    while ($loop->have_posts()) : $loop->the_post();
                                                                        ?>
                                                                        <input type="hidden" class="hatPrice"
                                                                               value="<?php the_field('hat_price', $loop->ID); ?>">
                                                                        <input type="hidden" class="hatheading"
                                                                               value="<?php the_field('hat_heading', $loop->ID); ?>">
                                                                        <input type="hidden" class="hatContent"
                                                                               value="<?php the_field('hat_content', $loop->ID); ?>">
                                                                        <input type="hidden" class="hatImage"
                                                                               value="<?php the_field('hat_image', $loop->ID); ?>">
                                                                        <div class="form-group group">
                                                                            <div class="for-back-color">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-lg-4">
                                                                                        <div class="sizing">
                                                                                            <div class="count">
                                                                                                <p><?php the_field('hat_heading', $loop->ID); ?>
                                                                                                    <span data-type="HAT"
                                                                                                          data-price="0"
                                                                                                          data-total_price="0"
                                                                                                          class="d-block price">$<?php the_field('hat_price', $loop->ID); ?></span>
                                                                                                </p>
                                                                                                <p style="font-size: 10px;text-align: start;">
                                                                                                    <?php the_field('hat_content', $loop->ID); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-8 increase increase_custm">
                                                                                        <div class="hat">
                                                                                            <img class="wooco-img"
                                                                                                 src="<?php the_field('hat_image', $loop->ID); ?>">
                                                                                        </div>
                                                                                        <!--                            <div class="popup">-->
                                                                                        <div class="size-ch popup"><a
                                                                                                    id="hatBtn"
                                                                                                    href="javascript:void(0);">?</a>
                                                                                        </div>
                                                                                        <!--                            </div>-->
                                                                                        <div class="up-down up-down-cstom">
                                                                                            <p>
                                                                                                <img src="<?php echo home_url() . '/wp-content/uploads/2021/12/minus.png'; ?>"
                                                                                                     id="minus1" width="40"
                                                                                                     height="20"
                                                                                                     class="minus"/>
                                                                                                <input id="hatqty-1"
                                                                                                       type="text" value="0"
                                                                                                       min="0" max="1"
                                                                                                       class="hatqty_<?php echo $child['id']; ?> qty qty_cstom"
                                                                                                       readonly
                                                                                                       name="hat-1"/>
                                                                                                <img id="add1"
                                                                                                     src="<?php echo home_url() . '/wp-content/uploads/2021/12/plus.png'; ?>"
                                                                                                     width="40" height="20"
                                                                                                     class="add"/>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endwhile; ?>
                                                                <?php } else { ?>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="hr-line"></div>
                                                            <div class="payment_detail payment_detail_cstom">
                                                                <div class="row d-none">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label check-label"
                                                                                   for="flexCheckChecked">Payment
                                                                                Method: Credit Card</label>
                                                                            <span class="crd"
                                                                                  style="display: inline-flex; margin-bottom: 10px;">
                                                                                <input class="form-check-input"
                                                                                       name="card-check" type="radio"
                                                                                       value="1" id="flexCheckChecked"
                                                                                       checked>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="credit-card-detail d-none">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label cstom-label"
                                                                               for="flexCheckChecked"
                                                                               style="margin-bottom: 8px;">Credit
                                                                            Card</label>
                                                                        <div class="vertical vertical_cstom"
                                                                             style="border-radius: 10px;">
                                                                            <input type="text"
                                                                                   class="form-control form_control_pay"
                                                                                   maxlength="20" id="card_number"
                                                                                   name="card_number"
                                                                                   aria-describedby="emailHelp" required>
                                                                        </div>
                                                                        <div class="card_cvv">
                                                                                <div class="row" style="margin-top: 10px;">
                                                                            <div class="col-lg-4">
                                                                                <label  class="form-check-label cstom-label" >Expiry Month</label>
                                                                                <input required type="text" class="form-control" style="margin-top: 8px;background: #018bb3;border-radius: 10px;color: #fff;" placeholder="MM" maxlength="2" id="expiry_month" name="expiry_month">
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <label  class="form-check-label cstom-label" >Expiry Year</label>
                                                                                <input required type="text" class="form-control" style="margin-top: 8px;background: #018bb3;border-radius: 10px;color: #fff;" placeholder="YY" maxlength="2" id="expiry_year" name="expiry_year">
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <label  class="form-check-label cstom-label" >CVV</label>
                                                                                <input type="text" class="form-control" style="margin-top: 8px;background: #018bb3;border-radius: 10px;color: #fff;" placeholder="CVV" maxlength="3" id="cvv" name="cvv">
                                                                            </div>
                                                                        </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <div class="cardholder-detail d-none">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label cstom-label"
                                                                               style="margin-top: 10px;">Cardholder
                                                                            Name</label>
                                                                        <input type="text" id="name_on_card"
                                                                               class="form-control form_control_cstom"
                                                                               name="name_on_card">
                                                                    </div>
                                                                </div>
                                                                <div class="" style="float: right;margin-top: 16px;">
                                                                    <div class="form-check tott">
                                                                        <div class="total">
                                                                            <input type="hidden" name="total_price"
                                                                                   id="total_price_custom<?php echo $key; ?>"
                                                                                   value="0">
                                                                            <label for="exampleInputPassword1"
                                                                                   class="subtotal"
                                                                                   id="total_price_detail_custom<?php echo $key; ?>">Total:
                                                                                $0 </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row pb-1">
                                                                    <div class="col-lg-6">
                                                                        <div class="copy_link">
                                                                            <label style="font-size: 17px;">Merchandise Order Close Date: <br><p style="font-size: 17px;   text-align: center;margin-top: 10px;"><?php echo get_field('merch_order_end_date',$child['school_id']); ?></p></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="next" style="margin-top: 10px;">
                                                                    <input type="button"
                                                                           class="btn btn-primary float-right step_3 step_xxx"
                                                                           data-child_id="<?php echo $child['id']; ?>"
                                                                           value="submit"></input>
                                                                </div>
                                                            </div>
                                                            <script type="text/javascript"
                                                                    src="https://js.stripe.com/v3/"></script>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php $events = get_field('fundraising_event', $school_id['school_id'], false);
                                            if ($events == 'Yes') {
                                                if($child['hide_profile']=='No'){
                                                ?>
                                                <div class="goal_summary">
                                                    <div class="fundraise_graph" style="width: 75%;">
                                                        <div class="fund">
                                                            <!-- <span class="fund_left lft">Progress</span> -->
                                                            <span class="fund_right cent">Funds Raised</span>
                                                            <span class="fund_right rght">Goal</span>
                                                        </div>
                                                        <?php
                                                        $raisedAmount = round($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount']);
                                                        $raisedAmounts = $raisedAmount * 100;
                                                        $totalGoalAmount = round($child_fundpage['data']['goal-amount']);
                                                        if(!empty($raisedAmount)){
                                                            $maxDonation = $raisedAmounts/$totalGoalAmount;
                                                        }else{
                                                            $maxDonation = 0;
                                                        }
                                                        ?>
                                                        <div class="school-light-grey" style="border-radius: 10px;">
                                                            <div class="school-red"
                                                                 style="border-top-left-radius: 15px;    border-bottom-left-radius: 15px;
                                                                 max-width:<?php echo $maxDonation;?>%"></div>
                                                        </div>
                                                        <div class="fund">
                                                            <span class="fund_left lft">$<?php echo number_format($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount'],2); ?></span>
                                                            <!--span class="fund_right cent">Funds Raised</span-->
                                                            <span class="fund_right rght">$<?php echo number_format($child_fundpage['data']['goal-amount'],2); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } } if($child['hide_profile']=='No'){?>
                                            <div class="fundrase_profile wew">
                                                <div class="row">
                                                    <?php $fundraisingEvent = get_field('fundraising_event', $school_id['school_id']);
                                                    if ($fundraisingEvent == 'Yes') {
                                                        ?>
                                                        <div class="col-lg-6">
                                                            <div class="classes">
                                                                <?php
                                                                if((!empty($user_id)) && (isset($user_id))){
                                                                    $school_id = getChildSchoolId($user_id);
                                                                }
                                                                $schoolFundEndDate = get_field('event_date', $school_id['school_id']);
                                                                $schoolFundEndDate = str_replace('/', '-', $schoolFundEndDate);
                                                                $schoolFundEndDate = date('d-m-Y', strtotime($schoolFundEndDate));
                                                                $schoolFundEndDateMessage = date('d-m-Y', strtotime($schoolFundEndDate));
                                                                $schoolFundEndDate = $schoolFundEndDate .TIME_ZONE_SET;
                                                                date_default_timezone_set('Australia/Brisbane');
                                                                $currentDate = date('d-m-Y H:i:s');
                                                                if (strtotime($schoolFundEndDate) > strtotime($currentDate)):
                                                                    ?>
                                                                    <h3>Fundrasing closes</h3>
                                                                    <div class="d_h_m_s">
                                                                        <div class="d"><span id="day-<?php echo $key; ?>"
                                                                                             class="day"></span>
                                                                            <p>Days</p></div>
                                                                        <div class="d"><span id="hour-<?php echo $key; ?>"
                                                                                             class="hour"></span>
                                                                            <p>Hours</p></div>
                                                                        <div class="d"><span id="minute-<?php echo $key; ?>"
                                                                                             class="minute"></span>
                                                                            <p>Minute</p></div>
                                                                        <div class="d"><span id="second-<?php echo $key; ?>"
                                                                                             class="second"></span>
                                                                            <p>Second</p></div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <h3>Fundraising Closed</h3>
                                                                    <h3><?php echo $schoolFundEndDateMessage; ?></h3>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                    $events = get_field('fundraising_event', $school_id['school_id'], false);
                                                    if ($events == 'Yes') {
                                                        if(trim($child['nickname'])){
                                                            $donatName = $child['nickname'];
                                                        }else{
                                                            $donatName = stripslashes($child['firstname']).', '.stripslashes(ucfirst($child['lastname'][0]));
                                                        }
                                                        if ($class_one_hide == 1){
                                                        $class_one = ', '.stripslashes($child['child_set_a']);
                                                        }
                                                        if ($class_two_hide == 1){
                                                            $class_two = ', '.stripslashes($child['child_set_b']);
                                                        }
                                                        if ($class_three_hide == 1){
                                                            $class_three = ', '.stripslashes($child['child_set_c']);
                                                        }
                                                        if ($class_four_hide == 1){
                                                            $class_four = ', '.stripslashes($child['child_set_d']);
                                                        }
                                                        $dona_tName = $donatName.''.$class_one.''.$class_two.''.$class_three.''.$class_four;
                                                        if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                                    $eventName = strtolower(str_replace(' ', '', $child['nickname'] . '' . $child['event_name']));
                                                                } else {
                                                                    $eventName = strtolower(str_replace(' ', '', $child['firstname'] . '' . $child['event_name']));
                                                                }
                                                        ?>
                                                        <div class="col-lg-6">
                                                            <div class="profile shared_thre"
                                                                 style="margin-left:<?php if ($fundraisingEvent == 'No') { ?>15%;<?php } ?>">
                                                                <h3>Share Page</h3>
                                                                <div class="f_en_cop">
                                                                    <?php
                                                                    $socialData = array(
                                                                            //'link' => home_url() . '/student-fundraising-page/?event='.stripslashes($eventName).urlencode('&child-id').'='.base64_encode($child['id']),
                                                                            'link' => home_url().'/profile/?'.$child['child_or_school_name'],
                                                                            'title' => $dona_tName,
                                                                            'email' => $user->data->user_email,
                                                                            'childID' => $child['id'],
                                                                            'user_id' => $user_id
                                                                    );
                                                                    echo social_share($socialData);
                                                                    ?>
                                                                </div>
                                                        <span class="d-none" id="copyText2_<?php echo $child['id']; ?>"><?php echo home_url().'/profile/?'.$child['child_or_school_name']; //echo home_url() . '/student-fundraising-page/?event='.stripslashes($eventName).'&child-id='.base64_encode($child['id']);?></span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        </div>
                                        <?php $events = get_field('incentive_prizes', $school_id['school_id'], false);
                                        //echo $events;
                                        if ($events == 'yes') {
                                            if($child['hide_profile']=='No'){
                                            ?>
                                            <div class="prize" style="margin-top: 10px;">
                                                <div>
                                                    <div class="st_prize">
                                                        <h3>Incentive Stars</h3>
                                                        <p>Fundraisers are highly appreciated and rewarded for their efforts. The value of the gift card credit increases as the funds raised reach higher milestones. Here's a breakdown of how it all works:</p>
                                                        <p>First Star: When you raise $20.00, you earn your first star and receive a $5.00 gift card credit</p>
                                                        <p>Second Star: Upon reaching $40.00 in total funds raised, you earn your second star and are rewarded with a $10.00 gift card.
                                                        Additional Stars: For every subsequent $40.00 you raise, you earn another star and receive an additional $10.00 in gift card credit.
                                                        </p>
                                                        <p>As you continue to raise more money, you earn more incentive stars, unlocking even greater rewards for your dedication and hard work. Within 14 days after the fundraising campaign concludes, your gift card credit will be delivered to the email address you registered with. The gift card credit can be used at over 500 online and in-store retailers, providing you with a wide range of choices
                                                        </p>
                                                    </div>
                                                    <?php
                                                    $star_prize = round($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount'], 2);
                                                    ?>
                                                    <div class="st_star">
                                                                        <span class="timer">
                                                                            <?php if ($star_prize >=20) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >=40) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 80) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 120) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 160) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 200) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 240) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 280) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 320) {
                                                                                ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 360) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize > 400) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php }
                                                                            if ($star_prize >= 500) { ?>
                                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } else { ?>
                                                                                <img style="opacity: .1;"
                                                                                     src="<?php echo site_url(); ?>/wp-content/uploads/2022/03/CF_Star.png">
                                                                            <?php } ?>
                                                                        </span>
                                                    </div>
                                       <!--  <div class="Stars" style="--rating:0" aria-label="100"></div> -->
                                                </div>
                                                <div class="giftImageCard">
                                                    <div class="giftCard">
                                                        <h3>Gift Card Earnt <br>$<?php
                                                            $value = round($donation['total_fund_raised']['total_donation']-$donation['total_fund_raised']['charge_amount'],2);
                                                            if($value){
                                                            $items = array();
                                                            for ($x = 0; $x <= $value; $x+=20) {
                                                              $items[] = $x;
                                                            }
                                                            echo max($items)/4;
                                                        }else{
                                                            echo '0';
                                                        }?>
                                                        </h3>
                                                        <div style="max-width: 80%;text-align: center;">
                                                        <?php //$giftCardDate =get_field('incentive_end_date', $school_id['school_id']); ?>
                                                        <p>Gift card credit will be emailed to the email address you have registered with by <span><?php echo $giftCardDate; ?></span></p>
                                                        </div>
                                                    </div>
                                                    <div Class="giftImage">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Rebel-Gift-Card.png">
                                                    <img  src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Smiggle-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Kmart-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Priceline-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Event-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/Coles-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/JBHiFi-Gift-Card.png">
                                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/07/EB-Games-Gift-Card.png">
                                                    </div>
                                                </div>
                                                </div>
                                        <?php } }?>
                                    </div>
                                    <?php if($child['hide_profile']=='No'){ ?>
                                    <div class="col-lg-4">
                                        <?php $events = get_field('fundraising_event', $school_id['school_id'], false);
                                        if ($events == 'Yes') {
                                            ?>
                                            <div class="donat" style="margin-top:-28px;">
                                                <h3>Latest Donation to
                                                    <span class="latest-title">
                                                        <?php 
                                                        if (($class_one_hide == 1) && !empty(trim($child['child_set_a']))){
                                                            $class_ones = ', '.stripslashes($child['child_set_a']);
                                                        }
                                                        if (($class_two_hide == 1) && !empty(trim($child['child_set_b']))){
                                                            $class_twos = ', '.stripslashes($child['child_set_b']);
                                                        }
                                                        if (($class_three_hide == 1) && !empty(trim($child['child_set_d']))){
                                                            $class_threes = ', '.stripslashes($child['child_set_c']);
                                                        }
                                                        if (($class_four_hide == 1) && !empty(trim($child['child_set_d']))){
                                                            $class_fours = ', '.stripslashes($child['child_set_d']);
                                                        }
                                                        $childClas = $class_ones.''.$class_twos.''.$class_threes.''.$class_fours;
                                                        if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                            echo stripslashes($child['nickname']) . '' . stripslashes($childClas);
                                                        } else {
                                                            echo stripslashes($child['firstname']) . ' ' . stripslashes(trim($child['lastname'][0])) . '' . stripslashes($childClas);
                                                        }
                                                        if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                                $event_Name = strtolower(str_replace(' ', '', $child['nickname'] . '' . $child['event_name']));
                                                        } else {
                                                                $event_Name = strtolower(str_replace(' ', '', $child['firstname'] . '' . $child['event_name']));
                                                        }
                                                    ?></span></h3>
                                                <?php //pr($donation);
                                                $donation1 = getRecentSchoolDonation($school_id['school_id']);
                                                $child_fundpage = get_child_fundraising_page($child['parent_id'], $child['firstname']);
                                                get_template_part('include/studentParent/fundraiser/latest-donation', null, array(
                                                                'data' => array(
                                                                        'latest_tdonation' => $donation,
                                                                        'latest_donation' => $donation1,
                                                                        'eventName' => $event_Name,
                                                                        'childID' => $child['id'],
                                                                    /*'child_id' => @$child_fundpage['data']['id'],
                                                                    'parent_id' =>$child['parent_id'],
                                                                    'fundraising_page_id' =>@$child_fundpage['data']['id'],
                                                                    'user_id' =>get_current_user_id(),*/
                                                                ))
                                                );
                                                ?>
                                            </div>
                                        <?php } ?>
                                        <?php $events = get_field('fundraising_event', $school_id['school_id'], false);
                                        if ($events == 'Yes') {
                                            ?>
                                            <div class="Badges_earnt">
                                                <div><h3>Badges Earnt</h3></div>
                                                <?php
                                                $maxBadgeAmount = getfundraiseLeaderBoard($school_id['school_id']);
                                                $maxBadgeAmount = round($maxBadgeAmount[0]['sum']);
                                                if (!empty($child['nickname']) && $child['nickname'] != '' && $child['nickname'] != ' ') {
                                                    $eventName = strtolower(str_replace(' ', '', $child['nickname'] . '' . $child['event_name']));
                                                } else {
                                                    $eventName = strtolower(str_replace(' ', '', $child['firstname'] . '' . $child['event_name']));
                                                }
                                                $countTimer = count($donation['latest_donation']);
                                                get_template_part('include/studentParent/fundraiser/child-badges', null, array(
                                                                'data' => array(
                                                                        'child_id' => $child['id'],
                                                                        'total_fund_raised' => $donation['total_fund_raised'],
                                                                        'max_fund_raised' => $maxBadgeAmount,
                                                                        'number_of_badge' => 10,
                                                                        'child_school_name' => $eventName,
                                                                        'childID' => $child['id'],
                                                                        'countdonation' => $countTimer,
                                                                        'user_id' => $user_id,
                                                                        'schooID' => $school_id['school_id']
                                                                    //'goal_amount'   => $child_fundpage['data']['goal-amount'],
                                                                ))
                                                );
                                                ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                </div>
                                <?php ++$j !== $totalChild; ?>
                                <?php if (++$i !== $totalChild): ?>
                                <div class="divider"></div>
                                <?php endif; ?>
                            <?php } ?>
                        <?php endforeach; ?>
                    <?php } else { ?>
                        <span class="no_child">No child registered.</span>
                    <?php } ?>
                </div>
            </section>
        </div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Use if you require a Nickname for media concerns or easier reference.</p>
            </div>
        </div>
        <?php
        $args = array(
                'post_type' => 'tshirt-details',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'ASC',
        );
        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post();
            ?>
            <!-- SiZE CHART -->
            <div id="sizechart" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="modal-body">
                        <h3 style="text-align: center;">Size chart</h3>
                        <img style="margin: 0 auto;" src="<?php the_field('size_chart'); ?>">
                    </div>
                </div>
            </div>
            <!-- TSHirt -->
            <div id="tshirt" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="modal-body">
                        <img style="margin: 0 auto;" src="<?php the_field('tshirt_chart'); ?>">
                    </div>
                </div>
            </div>
            <!-- TSHirt -->
            <div id="hatModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="modal-body">
                        <img style="margin: 0 auto;" src="<?php the_field('hat_chart'); ?>">
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        <!-- Model for Update Details of Parent -->
        <div id="parentedit" class="modal fade parent-editmodel" role="dialog">
            <div class="modal-content">
                <button class="closed_model parent-model edit_detailsAll">X</button>
                <div class="form-group group">
                    <h3 class="heading">Edit Parent Details</h3>
                </div>
                <form id="updateparentForm" class="form_custom" action="" method="POST">
                    <input type="hidden" class="user_id" name="user_id"
                           value="<?php echo $user_id; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Parent First Name</label>
                                <input id="event_start_date datepicker" Placeholder="Parent first Name"
                                       value="<?php echo $userMeta['first_name'][0]; ?>" name="firstname"
                                       type="text" class="firstname form-control datepicker firstname" required=""
                                       autocomplete="off" autofocus="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Parent Last Name</label>
                                <input id="lastname" Placeholder="Parent lastname Name" name="lastname"
                                       value="<?php echo $userMeta['last_name'][0]; ?>" type="text"
                                       class="lastname form-control " required="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Parent Phone</label>
                                <input Placeholder="Enter Parent Phone number" name="Pphone" type="text"
                                       class="form-control Pphone" value="<?php echo $userMeta['phone'][0]; ?>"
                                       required="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Parent Email</label>
                                <input Placeholder="Enter Parent Email" name="pEmail" type="text"
                                       class="form-control pEmail" value="<?php echo $user->data->user_email; ?>"
                                       required="" autocomplete="off" autofocus="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-lg btn-block w-100" name="update"
                               value="Update"></input>
                    </div>
                    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
                </form>
            </div>
        </div>
        <!-- Model for update Password of parent -->
        <!-- Password --modal-->
        <div id="parentpasswordedit" class="modal fade parent-password-editmodel" role="dialog">
            <div class="modal-content">
                <button class="closed_model parent-model">X</button>
                <div class="form-group group">
                    <h3 class="heading">Edit Parent Details</h3>
                </div>
                <form id="updatepaswwordForm" class="form_custom" action="" method="POST">
                    <input type="hidden" class="user_id" name="user_id"
                           value="<?php echo $user_id; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Old Password</label>
                                <input id="event_start_date datepicker" Placeholder="Old Password"
                                       name="oldpassword" type="text"
                                       class="firstname form-control datepicker oldpassword" required=""
                                       autocomplete="off" autofocus="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">New Password</label>
                                <input id="lastname" Placeholder="New Password" name="newpassword" type="text"
                                       class="newpassword form-control " required="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Confirm Password</label>
                                <input Placeholder="Confirm Password" name="confirmpass" type="text"
                                       class="form-control confirmpass" required="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-lg btn-block w-100" name="update"
                               value="Update"></input>
                    </div>
                    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
                </form>
            </div>
        </div>
        <!-- Model for update Tshirt size -->
        <div id="TshirtUpdate" class="modal fade" role="dialog">
            <div class="modal-content">
                <button class="closed_model parent-model Edit_Tshirt">X</button>
                <div class="form-group group">
                    <h3 class="heading">Edit Child Tshirt Details</h3>
                </div>
                <form id="updateTshirtForm" class="form_custom" action="" method="POST">
                    <input type="hidden" class="user_id childId" name="childId" value="<?php echo $child['id']; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label" for="email">Please Select size</label>
                                <div class="">
                                    <select id="childSize" Placeholder="Junior or Senior" name="childSize"
                                            class="childSizes form-control form_control_cstom" required>
                                        <option value="">Choose</option>
                                        <?php
                                        $args = array(
                                                'post_type' => 'tshirt-size',
                                                'post_status' => 'publish',
                                                'orderby' => 'date',
                                                'order' => 'ASC',
                                        );
                                        $loop = new WP_Query($args);
                                        while ($loop->have_posts()) : $loop->the_post();
                                            ?>
                                            <option value="<?php echo get_the_title(); ?>"<?php echo ($child['cloth_size']==get_the_title())?'selected':''; ?>><?php echo get_the_title(); ?></option>
                                        <?php endwhile; ?></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-lg btn-block w-100" name="update"
                               value="Update"></input>
                    </div>
                    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
                </form>
            </div>
        </div>
       
        <!-- This model for add new child -->
        <div id="myModal" class="modal fade manage-funraising-onparentandschool" role="dialog">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="school_login school-login-form childfundpreview_onparentandschool" style="Padding: 0px 0;background: #1b7eaa;">
    </div>
  </div>
</div>
        <!-- this model for change image -->
        <div id="childImageChanged" class="modal fade imageChanged" role="dialog">
            <div class="modal-content">
                <button class="closed_model imageclosed">X</button>
                <div class="form-group group">
                    <h3 class="heading">Change Child image</h3>
                </div>
                <form id="updateimage" enctype="multipart/form-data" class="form_custom" action="" method="POST">
                    <input type="hidden" class="user_id childId" name="childId"
                           value="<?php echo $child['id']; ?>">
                <section class="form-section">
                <div class="container padding">
                    <div class="row">
                         <div class="col-lg-6">
                            <div class="group">
                                <div class="upload_imaged">
                                    <label class="control-label school-label" for="email">Change Image</label>
                                </div>
                                <?php 
                                   if(get_user_meta($user_id,'avtar_'.$child['id'],true)){
                                        $attecmentid = get_user_meta($user_id,'avtar_'.$child['id'],false);
                                        $image =  wp_get_attachment_url($attecmentid);
                                    }else{
                                        $image =  wp_get_attachment_url(9438);
                                    }
                                ?>
                                <div class="school_logo_uploaded">
                                    <div class="school_logos">
                                        <img id="image_showing_logo_child" style="max-width: 575px;" class= "logo-and-images" src="<?php echo $image; ?>"/>
                                    </div>
                                    <div class="upload_imaged">
                                       <input type="file" name="school_logo_child" class="custom-file-input" id="school_logo_show_child">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="form-group for-button">
                    <button type="submit" style=" width: unset;" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
        </div>
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
        school_logo_show_child.onchange = evt => {
            const [file] = school_logo_show_child.files
                if (file) {
                 image_showing_logo_child.src = URL.createObjectURL(file)
                 $("#image_showing_logo_child").css("display", "block");
                }
        }

        $(document).ready(function() {
            $('#school_logo_show_child').bind('change', function() {
                var a=(this.files[0].size);
                let maxSize='<?php echo MAX_UPLOAD_FILESIZE; ?>';
                if(a > maxSize) {
                    toastr.error('Please upload the image upto 1 MB');
                };
            });
        });
        </script>
        <!-- this model for change image -->
        <?php
         get_template_part('include/studentParent/register/dashboard/dashboard-registration', null, array(
            'data' => array(
                    'schooID' => $school_id['school_id'],
            ))
        );
        ?>
        <?php
        if (!empty($childData['data'])) {
            if((!empty($user_id)) && (isset($user_id))){
                $school_id = getChildSchoolId($user_id);
            }
            $schoolFundEndDates = get_field('event_date', $school_id['school_id']);
            $schoolFundEndDates = str_replace('/', '-', $schoolFundEndDates);
            $schoolFundEndDates = date('M d, Y', strtotime($schoolFundEndDates));
            $schoolFundEndDates = $schoolFundEndDates .TIME_ZONE_SET;
            $res = $schoolFundEndDates;
            foreach (($childData['data']) as $key => $child) {
                if($child['hide_profile']=='No'){
                ?>
                <script>
                    function childTimer<?php echo $key; ?>() {
                        var x = setInterval(function () {
                            var countDownDate = new Date("<?php echo $res;  ?>").getTime();
                            //  var countDownDate = new Date("Jan 15, 2022 15:37:25").getTime();
                            var now = new Date().getTime();
                            var distance = countDownDate - now;
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            if (distance > 0) {
                                document.querySelector("#day-<?php echo $key; ?>").innerHTML = days;
                                document.querySelector("#hour-<?php echo $key; ?>").innerHTML = hours;
                                document.querySelector("#minute-<?php echo $key; ?>").innerHTML = minutes;
                                document.querySelector("#second-<?php echo $key; ?>").innerHTML = seconds;
                            }
                        }, 1000);
                    }
                    childTimer<?php echo $key; ?>();
                    function withoutJquery(id) {
                        var temp = document.createElement('input');
                        var texttoCopy = document.getElementById('copyText2_' + id + '').innerHTML;
                        if(texttoCopy.match("amp;")){
                            texttoCopy = texttoCopy.replaceAll('amp;','');
                        }
                        temp.type = 'input';
                        temp.setAttribute('value',texttoCopy);
                        document.body.appendChild(temp);
                        temp.select();
                        document.execCommand("copy");
                        toastr.success('Copied Successfully.');
                        setTimeout(()=>{
                             location.reload(true);
                        },1000);
                        temp.remove();
                    }
                </script>
            <?php } ?>
            <script>
                $(document).ready(function () {
                        $(".order_button").click(function () {
                            var model_childID = $(this).data('childid');
                            //console.log(model_childID);
                            $('#model_childid').val(model_childID);
                            $(".funddash").addClass("active");
                            jQuery('.total input').val(0);
                            $("#myModalboth_" + model_childID).show();
                        });
                    });
                    $(document).ready(function () {
                        $(".size-chart_<?php echo $child['id']; ?>").hide();
                        $(".sizeChart_<?php echo $child['id']; ?>").hide();
                        $(".Add_<?php echo $child['id']; ?>").click(function(event){
                            $(".sizeChart_<?php echo $child['id']; ?>").show();
                            $(".size-chart_<?php echo $child['id']; ?>").show();
                        });
                        $(".minus").click(function(event){
                            $(".sizeChart_<?php echo $child['id']; ?>").hide();
                            $(".size-chart_<?php echo $child['id']; ?>").hide();
                        });
                        $(".Allsize_<?php echo $child['id']; ?>").click(function(event){
                            let tshirtsize = $(this).data('size');
                            $("#childSize option[value='"+tshirtsize+"']").prop('selected',true);
                            $('#updateTshirtForm .childId').val('<?php echo $child['id']; ?>');
                            $("#TshirtUpdate").show();
                        });
                        $(".Edit_Tshirt").click(function(event){
                            $("#TshirtUpdate").hide();
                        });
                        $('.closedchild').on('click', function () {
                            $('#AddChildnew').hide();
                        });
                        $('.imageclosed').on('click', function () {
                            $('#childImageChanged').hide();
                            $("#image_showing_logo_child").css("display", "none");
                        });
                        $('.Allimage_<?php echo $child['id']; ?>').on('click', function () {
                            $("#image_showing_logo_child").css("display", "none");
                            $('#updateimage .childId').val('<?php echo $child['id']; ?>');
                            $('#childImageChanged').show();
                        });
                        $('.alldonations_<?php echo $child['id']; ?>').on('click', function () {
                            //var id = childid
                            $('#donationsAll_<?php echo $child['id']; ?>').show();
                        });
                        $('.donations').on('click', function () {
                            $('#donationsAll_<?php echo $child['id']; ?>').hide();
                        });
                    });
                </script>
            <?php } ?>
            <script type="text/javascript">
                
                $(document).ready(function() {
                    var table = $('.dataTables').DataTable({
                        "ordering": false,
                        "oLanguage": {
                                        "sEmptyTable": "Awaiting Donation - Donate Now to Help"
                                    },
                        dom: 'Bfrtip',
                        "pageLength": 5,
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: '',
                                filename: 'Donation Report <?php echo date('d/m/Y'); ?>',
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Donation Report',
                                orientation: 'landscape',
                                pageSize: 'A3',
                                filename: 'Donation Report <?php echo date('d/m/Y'); ?>',
                            },{
                                extend: 'print',
                                text: 'Print',
                                title: 'Donation Report',
                                filename: 'Donation Report <?php echo date('d/m/Y'); ?>'
                            }
                        ]
                    });
                } );
            </script>
    <?php } ?>
    <?php else: ?>
        <?php echo get_template_part('template-parts/unauthorized'); ?>
    <?php endif; ?>
    <?php
}
add_shortcode('parent_dashboards', 'parent_dashboard_callback1');