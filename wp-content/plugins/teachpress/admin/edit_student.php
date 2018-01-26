<?php
/* Edit a student
 * @param int $student_ID (GET)
 * @param string $search (GET)
 * @param string $students_group (GET)
 * @since 4.0.0
*/ 
function teachpress_show_student_page() {
   $student = htmlspecialchars($_GET['student_ID']);
   $students_group = htmlspecialchars($_GET['students_group']);
   $search = htmlspecialchars($_GET['search']);
   $entry_limit = intval($_GET['limit']);
   
   ?> 
   <div class="wrap">
   <?php
   // Event handler
   if ( isset( $_GET['delete'] )) {
        tp_delete_registration($_GET['checkbox']);
        $message = __('Enrollment deleted','teachpress');
        get_tp_message($message);
   }
   echo '<p><a href="admin.php?page=teachpress/students.php&amp;search=' . $search . '&amp;students_group=' . $students_group . '&amp;limit=' . $entry_limit . '" class="button-secondary" title="' . __('Back','teachpress') . '">&larr; ' . __('Back','teachpress') . ' </a></p>';
   ?>
   <form name="personendetails" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
   <input name="page" type="hidden" value="teachpress/students.php" />
   <input name="action" type="hidden" value="show" />
   <input name="student_ID" type="hidden" value="<?php echo $student; ?>" />
   <input name="students_group" type="hidden" value="<?php echo $students_group; ?>" />
   <input name="search" type="hidden" value="<?php echo $search; ?>" />
   <input name="limit" type="hidden" value="<?php echo $entry_limit; ?>" />
   <?php
      $row3 = get_tp_student($student);
   ?>
 <h2 style="padding-top:0px;"><?php echo stripslashes($row3->firstname); ?> <?php echo stripslashes($row3->lastname); ?> <span class="tp_break">|</span> <small><a href="<?php echo 'admin.php?page=teachpress/students.php&amp;student_ID=' . $student . '&amp;search=' . $search . '&amp;students_group=' . $students_group . '&amp;limit=' . $entry_limit . '&amp;action=edit'; ?>" id="daten_aendern"><?php _e('Edit','teachpress'); ?> </a></small></h2>
     <div style="width:55%; padding-bottom:10px;">
     <table border="0" cellpadding="0" cellspacing="5" class="widefat">
        <thead>
        <?php
        echo '<tr>';
        echo '<td width="130"><strong>' . __('WordPress User-ID','teachpress') . '</strong></td>';
        echo '<td style="vertical-align:middle;">' . $row3->wp_id . '</td>';
        echo '</tr>';
        if (get_tp_option('regnum') == '1') {
            echo '<tr>';
            echo '<td><strong>' . __('Matr. number','teachpress') . '</strong></td>';
            echo '<td style="vertical-align:middle;">' . $row3->matriculation_number . '</td>';
            echo '</tr>';
        }
        if (get_tp_option('studies') == '1') {
            echo '<tr>';
            echo '<td><strong>' . __('Course of studies','teachpress') . '</strong></td>';
            echo '<td style="vertical-align:middle;">' . stripslashes($row3->course_of_studies) . '</td>';
            echo '</tr>';
        }
        if (get_tp_option('termnumber') == '1') { 
            echo '<tr>';
            echo '<td><strong>' . __('Number of terms','teachpress') . '</strong></td>';
            echo '<td style="vertical-align:middle;">' . $row3->semesternumber . '</td>';
            echo '</tr>';
        }
        if (get_tp_option('birthday') == '1') {
            echo '<tr>';
            echo '<td><strong>' . __('Date of birth','teachpress') . '</strong></td>';
            echo '<td style="vertical-align:middle;">' . $row3->birthday . '</td>';
            echo '</tr>';
        }
        echo '<tr>';
        echo '<td><strong>' . __('User account','teachpress') . '</strong></td>';
        echo '<td style="vertical-align:middle;">' . $row3->userlogin . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo'<td><strong>' . __('E-Mail') . '</strong></td>';
        echo '<td style="vertical-align:middle;"><a href="admin.php?page=teachpress/teachpress.php&amp;student_ID=' . $row3->wp_id . '&amp;search=' . $search . '&amp;students_group=' . $students_group . '&amp;limit=' . $entry_limit . '&amp;action=mail&amp;single=' . $row3->email . '" title="' . __('Send E-Mail to','teachpress') . ' ' . $row3->firstname . ' ' . $row3->lastname . '">' . $row3->email . '</a></td>';
        echo '</tr>';
        ?>
      </thead>   
     </table>
     </div>
   </form>
   <form method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
   <input name="page" type="hidden" value="teachpress/editstudent.php">
   <input name="student_ID" type="hidden" value="<?php echo $student; ?>">
   <input name="search" type="hidden" value="<?php echo $search; ?>">
   <h3><?php _e('Signups','teachpress'); ?></h3>
   <table class="widefat">
    <thead>
        <tr>
        <th>&nbsp;</th>
        <th><?php _e('Enrollment-Nr.','teachpress'); ?></th>
        <th><?php _e('Registered at','teachpress'); ?></th>
        <th><?php _e('Course','teachpress'); ?></th>
        <th><?php _e('Type'); ?></th>
        <th><?php _e('Date','teachpress'); ?></th>
        </tr>
    </thead>    
    <tbody>
    <?php
        // get signups
        $row = get_tp_student_signups($student, 'reg');
        if ( count($row) != 0) {	
            $row = get_tp_student_signups($student, 'reg');
            foreach($row as $row) {
                if ($row->parent_name != "") {
                    $row->parent_name = $row->parent_name . " ";
                }
                else {
                    $row->parent_name = "";
                }
                echo '<tr>';
                echo '<th class="check-column"><input name="checkbox[]" type="checkbox" value="' . $row->con_id . '"/></th>';
                echo '<td>' . $row->con_id . '</td>';
                echo '<td>' . $row->timestamp . '</td>';
                echo '<td>' . stripslashes($row->parent_name) . stripslashes($row->name) . '</td>';
                echo '<td>' . stripslashes($row->type) . '</td>';
                echo '<td>' . stripslashes($row->date) . '</td>';
                echo '</tr>';
            } 
        }
        else {
            echo '<tr><td colspan="6"><strong>' . __('Sorry, no entries matched your criteria.','teachpress') . '</strong></td></tr>';
        }?>
    </tbody>
   </table>
   <?php
   $row = get_tp_student_signups($student, 'wtl');
   if ( count($row) != 0 ) {
        echo '<h3>' . __('Waitinglist','teachpress') . '</h3>';
        ?>
        <table border="1" cellspacing="0" cellpadding="5" class="widefat">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?php _e('Enrollment-Nr.','teachpress'); ?></th>
                    <th><?php _e('Registered at','teachpress'); ?></th>
                    <th><?php _e('Course','teachpress'); ?></th>
                    <th><?php _e('Type'); ?></th>
                    <th><?php _e('Date','teachpress'); ?></th>
                </tr>
            </thead>    
            <tbody>
            <?php     
            foreach($row as $row) {
                if ( $row->waitinglist == 1 ) {
                    if ( $row->parent_name != "" ) {
                        $parent_name = $row->parent_name . " ";
                    }
                    else {
                        $parent_name = "";
                    }
                    echo '<tr>';
                    echo '<th class="check-column"><input name="checkbox[]" type="checkbox" value="' . $row->con_id . '"/></th>';
                    echo '<td>' . $row->con_id . '</td>';
                    echo '<td>' . $row->timestamp . '</td>';
                    echo '<td>' . stripslashes($parent_name) . stripslashes($row->name) . '</td>';
                    echo '<td>' . stripslashes($row->type) . '</td>';
                    echo '<td>' . stripslashes($row->date) . '</td>';
                    echo '</tr>';
                }
            }
                ?>
            </tbody>
        </table>
   <?php } ?>
   <table border="0" cellspacing="7" cellpadding="0" id="einzel_optionen">
     <tr>
        <td><?php _e('delete enrollment','teachpress'); ?></td>
        <td> <input name="delete" type="submit" value="<?php _e('Delete','teachpress'); ?>" id="teachpress_search_delete" class="button-secondary"/></td>
     </tr>
   </table>
   </form>
   </div>
<?php } 

function teachpress_edit_student_page() {
    global $user_ID;
    $student = htmlspecialchars($_GET['student_ID']);
    $students_group = htmlspecialchars($_GET['students_group']);
    $search = htmlspecialchars($_GET['search']);
    $entry_limit = intval($_GET['limit']);
    
    if ( isset($_POST['tp_change_user'] ) ) {
        $data = array (
            'matriculation_number' => isset($_POST['matriculation_number']) ? intval($_POST['matriculation_number']) : 0,
            'firstname' => htmlspecialchars($_POST['firstname']),
            'lastname' => htmlspecialchars($_POST['lastname']),
            'userlogin' => htmlspecialchars($_POST['userlogin']),
            'course_of_studies' => isset($_POST['course_of_studies']) ? htmlspecialchars($_POST['course_of_studies']) : '',
            'semester_number' => isset($_POST['semesternumber']) ? intval($_POST['semesternumber']) : 0,
            'birth_day' => isset($_POST['birth_day']) ? htmlspecialchars($_POST['birth_day']) : '00',
            'birth_month' => isset($_POST['birth_month']) ? htmlspecialchars($_POST['birth_month']) : '00',
            'birth_year' => isset($_POST['birth_year']) ? intval($_POST['birth_year']) : '0000',
            'email' => htmlspecialchars($_POST['email'])
        );
        tp_change_student($student, $data, $user_ID);
        get_tp_message( __('Saved') );
    }
    
    echo '<div class="wrap">';
    echo '<p><a href="admin.php?page=teachpress/students.php&amp;student_ID=' . $student . '&amp;search=' . $search . '&amp;students_group=' . $students_group . '&amp;limit=' . $entry_limit . '&amp;action=show" class="button-secondary" title="' . __('Back','teachpress') . '">&larr; ' . __('Back','teachpress') . ' </a></p>';
    echo '<h2>Edit Student</h2>';
    $user = get_tp_student($student, OBJECT);
    echo tp_registration_form($user, 'admin');
    echo '</div>';
}

?>