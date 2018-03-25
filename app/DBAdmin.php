<?php
session_start();

// Database constants
define('DB_URL', 'localhost');
// define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DATABASE', 'dev-o-talk');

//Check for connection
if(!mysqli_connect(DB_URL, DB_USER, DB_PASSWORD, DATABASE))
	header("Location: 404.php");

//make a connection variable
$conn = mysqli_connect(DB_URL, DB_USER, DB_PASSWORD, DATABASE);


// Session variables list:
// ===============================================================
// $_SESSION['userName']
// $_SESSION['userId']
// $_SESSION['userEmail']
// $_SESSION['userType']
// $_SESSION['userImg']
// $_SESSION['userNickName']
// $_SESSION['stamp_temp_editor']
// $_SESSION['stamp_temp_textarea']
// $_SESSION['comment_temp_textarea']
// $_SESSION['curr_stamp_sn']
// $_SESSION['target_user_id']
// $_SESSION['target_team_id']
// $_SESSION['editorFromPage']
// ===============================================================


//Register a user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnRegister']) ) {
		$user_id = mysqli_real_escape_string($conn, $_POST['username']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		$confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

		if(!($user_id == "")) {
			if(!($password == "") && !($confirmPassword == "") && !strcmp($password, $confirmPassword)) {

				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

					$query_find = "SELECT * FROM user WHERE user_id = '$user_id'";
				    $query_status = mysqli_query($conn, $query_find);
				    
				    if(mysqli_num_rows($query_status) == 0) {
				    	$query_insert = "INSERT INTO user (user_id, email, date_created, last_login, user_type, active_status)
										VALUES ('$user_id', '$email', NOW(), NOW(), 'general', '1')";

						$query_status = mysqli_query($conn, $query_insert);
						if($query_status) {
							$encPass = md5($password);
							$query_insert = "INSERT INTO login (user_id, password) VALUES ('$user_id','$encPass')";
							$query_status = mysqli_query($conn, $query_insert);
							displayMessage("Register successful.");
							prepareSessionVar($user_id);
						} else {
							displayMessage("Registration error...! Please try after sometime.");
						}
				    } else {
				    	displayMessage("User already exists.");
				    }
				} else {
					displayMessage("Please enter valid email address.");
				}
			} else {
				displayMessage("Passwords not matched.");
			}
		} else {
			displayMessage('Userid required.');
		}
	}
}

//Changing Password
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnChangePass']) ) {
		$oldPass = mysqli_real_escape_string($conn, $_POST['oldPassword']);
		$newPass = mysqli_real_escape_string($conn, $_POST['newPassword']);
		$cnfPass = mysqli_real_escape_string($conn, $_POST['cnfPassword']);

		$user = $_SESSION['userId'];

		if(!($oldPass == "")) {
			if(!($newPass == "") && !($cnfPass == "") && !strcmp($newPass, $cnfPass)) {

				if (strcmp($oldPass, $newPass)) {

					$encOld = md5($oldPass);
					$encNew = md5($newPass);

					$query_select = "SELECT * FROM login WHERE user_id = '$user' AND password = '$encOld'";
					$query_status = mysqli_query($conn, $query_select);
				    
				    if(!(mysqli_num_rows($query_status) == 0)) {
				    	$query_update = "UPDATE login SET password = '$encNew' WHERE user_id = '$user'";

						$query_status = mysqli_query($conn, $query_update);
						if($query_status) {
							displayMessage("Update successful.");
							header('Location: index.php');
						} else {
							displayMessage("Update error...! Please try after sometime.");
						}
				    } else {
				    	displayMessage("Worng old password entered.");
				    }
				} else {
					displayMessage("Old and new passwords cannot match.");
				}
			} else {
				displayMessage("Passwords not matched.");
			}
		} else {
			displayMessage('Old password required.');
		}
	}
}

//Login user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnLogin']) ) {
		$user_id = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$encPass = md5($password);

		$query_select = "SELECT * FROM login WHERE user_id = '$user_id' AND password = '$encPass'";
		$query_status = mysqli_query($conn, $query_select);
		// print_r($query_status);

		if(!(mysqli_num_rows($query_status) == 0)) {
			$query_update = "UPDATE user SET last_login = NOW() WHERE user_id = '$user_id'";
			$query_status = mysqli_query($conn, $query_update);
			displayMessage("Login successful.");
			prepareSessionVar($user_id);
		} else {
			displayMessage("Invalid user.");
		}
	}
}

// Prepare user session variables
function prepareSessionVar($user_id) {
	global $conn;
	$query_select = "SELECT * FROM user WHERE user_id = '$user_id'";    
   	$query_execute = mysqli_query($conn, $query_select);
   	if(mysqli_num_rows($query_execute) == 1) {    
        while($row = $query_execute->fetch_assoc()) {
          if(!($row['first_name'] == "")) {
          	$_SESSION['userName'] = $row['first_name'];	
          } else {
          	$_SESSION['userName'] = $row['user_id'];
          }
          $_SESSION['userId'] = $row['user_id'];
          $_SESSION['userEmail'] = $row['email'];
          $_SESSION['userType'] = $row['user_type'];
          $_SESSION['userImg'] = $row['picture'];
          $_SESSION['userNickName'] = $row['nick_name'];
        }
        // if($_SESSION['type'] == 'Admin')
        //   header('Location: admin.php');
        // else
        //   header('Location: user.php');
        header('Location: app/dashboard.php');
        // echo '<script>windows.location.href = "dashboard.php";</script>';
  	}
}

// posting stamp
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnPost']) ) {
		$stamp_text = mysqli_real_escape_string($conn, $_POST['stampText']);
		setStampTempTextarea($stamp_text);

		$user = $_SESSION['userId'];
		$stamp = $_SESSION['stamp_temp_textarea'];

		if(isset($_SESSION['target_team_id'])) {
			$team = $_SESSION['target_team_id'];
			$query_insert_stamp = "INSERT INTO stamp (user_id, message, team_id, date_created) 
								VALUES ('$user', '$stamp', '$team', NOW())";
		} else {
			$query_insert_stamp = "INSERT INTO stamp (user_id, message, date_created) 
								VALUES ('$user', '$stamp', NOW())";
		}

		$query_status = mysqli_query($conn, $query_insert_stamp);

		unset($_SESSION['stamp_temp_textarea']);
	    unset($_SESSION['stamp_temp_editor']);
	}
}

// posting comment
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnComment']) ) {
		$stamp_serial = $_SESSION['curr_stamp_sn'];
		$stamp_text = mysqli_real_escape_string($conn, $_POST['stampText']);
		setCommentTempTextarea($stamp_text);

		$user = $_SESSION['userId'];
		$comment = $_SESSION['comment_temp_textarea'];

		$query_insert_comment = "INSERT INTO comment (stamp_sn, user_id, comment_message, date_commented) 
								VALUES ('$stamp_serial', '$user', '$comment', NOW())";
		$query_status = mysqli_query($conn, $query_insert_comment);

		unset($_SESSION['comment_temp_textarea']);
	    unset($_SESSION['stamp_temp_editor']);
	}
}

// Save textarea and invoke editor
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnEditor']) ) {
		$stamp_text = $_POST['stampText'];
		$_SESSION['editorFromPage'] = $_POST['fromPageHidden'];

		setStampTempTextarea($stamp_text);
		
		header('Location: editorModal.php');
	}
}

// Save textarea and invoke editor for comment purpose
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnEditorComment']) ) {
		$stamp_text = $_POST['stampText'];
		$_SESSION['editorFromPage'] = $_POST['fromPageHidden'];

		setCommentTempTextarea($stamp_text);
	
		header('Location: editorModal.php');
	}
}

// Save code editor text
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnEditorSave']) ) {
		// $btn_Editor_Save = mysqli_real_escape_string($conn, $_POST['btnEditorSave']);
		// $btn_Editor_Save = htmlentities($_POST['btnEditorSave']);
		$btn_Editor_Save = $_POST['btnEditorSave'];
		setStampTempEditor($btn_Editor_Save);
	}
}

// Prepare current stamp serial number for adding comment
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnAddCommentHidden']) ) {
		$_SESSION['curr_stamp_sn'] = $_POST['txtHidden'];
	}
}

// Set Target user (searched user)
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnUserSearch']) ) {
		if(isValidUser($_POST['targetUser']))
			$_SESSION['target_user_id'] = $_POST['targetUser'];
		else
			displayMessage('user not found');
	} elseif (isset($_POST['btnSelfSearch'])) {
		if( isset($_SESSION['target_user_id']) ) {
			unset($_SESSION['target_user_id']);
		}
	}
}

// Set notification table for user connections
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnConnectUser']) ) {
		$notifToId = $_SESSION['target_user_id'];
		$notifById = $_SESSION['userId'];

		$query_check = "SELECT * FROM notification WHERE ((notif_to = '$notifToId' AND notif_by = '$notifById') OR (notif_to = '$notifById' AND notif_by = '$notifToId')) AND notif_status = 'pending'";
		$query_status = mysqli_query($conn, $query_check);

		if(!(mysqli_num_rows($query_status) > 0)) {
			$query_insert_notif = "INSERT INTO notification (notif_by, notif_to, notif_by_type, notif_date, notif_status) 
									VALUES ('$notifById', '$notifToId', 'individual', NOW(), 'pending')";
			$query_status = mysqli_query($conn, $query_insert_notif);	
		}
	}
}

// Set notification table for team invite
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnInviteToTeam']) ) {
		$notifToId = $_POST['newMember'];
		$notifById = $_SESSION['target_team_id'];

		$query_insert_notif = "INSERT INTO notification (notif_by, notif_to, notif_by_type, notif_date, notif_status) 
								VALUES ('$notifById', '$notifToId', 'team', NOW(), 'pending')";
		$query_status = mysqli_query($conn, $query_insert_notif);
	}
}

// Make relation between users
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnConfirmConnect']) ) {
		$notifById = $_POST['hiddenByValue'];
		$notifByTypeId = $_POST['hiddenByTypeValue'];
		$notifToId = $_SESSION['userId'];

		$query_update_notif = "UPDATE notification SET notif_status = 'accepted' WHERE notif_to = '$notifToId' AND notif_by = '$notifById'";
		$query_status = mysqli_query($conn, $query_update_notif);

		if($query_status) {
			if($notifByTypeId == 'individual') {
				$relType = 'friend';
			} elseif ($notifByTypeId == 'team') {
				$relType = 'member';
			}
			$query_insert_relation = "INSERT INTO relation (partner_1, partner_2, relation_type, date_related, relation_status) 
										VALUES ('$notifById', '$notifToId', '$relType', NOW(), 'connected')";
			$query_status = mysqli_query($conn, $query_insert_relation);

			$msg = "Congratulation! You are now connected to " . $notifById . ".";
			header("Location: dashboard.php");
			displayMessage($msg);
		}
	}
}

// View connection request profile
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnViewProfile']) ) {
		$notifById = $_POST['hiddenByValue'];
		$notifByTypeId = $_POST['hiddenByTypeValue'];

		if($notifByTypeId == 'individual') {
			$_SESSION['target_user_id'] = $notifById;
			header("Location: profile.php");

		} elseif ($notifByTypeId == 'team') {
			$_SESSION['target_team_id'] = $notifById;
			header("Location: team.php");
		}
	}
}

// Set team according to selection
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnMyTeam']) ) {
		$_SESSION['target_team_id'] = $_POST['btnMyTeam'];
	}
}

// Set team according to selection
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnMyFriend']) ) {
		$_SESSION['target_user_id'] = $_POST['btnMyFriend'];
	}
}

// Delete relation between users
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnDisconnectUser']) ) {
		$notifToId = $_SESSION['target_user_id'];
		$notifById = $_SESSION['userId'];

		$query_delete_relation = "UPDATE relation SET relation_status = 'disconnected' WHERE (partner_1 = '$notifToId' AND partner_2 = '$notifById') OR (partner_2 = '$notifToId' AND partner_1 = '$notifById') AND relation_status = 'connected'";
		$query_status = mysqli_query($conn, $query_delete_relation);

		$query_delete_notif = "UPDATE notification SET notif_status = 'deleted' WHERE (notif_to = '$notifToId' AND notif_by = '$notifById') OR (notif_by = '$notifToId' AND notif_to = '$notifById') AND notif_status = 'accepted'";
		$query_status = mysqli_query($conn, $query_delete_notif);
	}
}

// Set user profile
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnProfileForm']) ) {
		$user = $_SESSION['userId'];

		$usrProf = getUserProfile();
		$usrProfRow = mysqli_fetch_assoc($usrProf);

		$fname = ($_POST['firstName'] == "") ? $usrProfRow['first_name'] : $_POST['firstName'];
		$lname = ($_POST['lastName'] == "") ? $usrProfRow['last_name'] : $_POST['lastName'];
		$nname = ($_POST['nickName'] == "") ? $usrProfRow['nick_name'] : $_POST['nickName'];
		$mob = ($_POST['mobile'] == "") ? $usrProfRow['mobile'] : $_POST['mobile'];
		$loc = ($_POST['location'] == "") ? $usrProfRow['location'] : $_POST['location'];
		$quali = ($_POST['qualification'] == "") ? $usrProfRow['qualification'] : $_POST['qualification'];
		$achiv = ($_POST['achievement'] == "") ? $usrProfRow['achievement'] : $_POST['achievement'];

		$imgVal = $usrProfRow['picture'];

		if (!empty($_FILES['imgInput']['name'])) {
		    $ext = pathinfo($_FILES['imgInput']['name'], PATHINFO_EXTENSION);
		    $picName = $_FILES['imgInput']['name'];
		    $tmp_name = $_FILES["imgInput"]["tmp_name"];
		    $query_num = "SELECT MAX(sn) FROM picture";
		    $query_status = mysqli_query($conn, $query_num);
		    $val = mysqli_fetch_assoc($query_status)['MAX(sn)'];

		    $num = ($val > 0 ? $val + 1 : 1);
		    if($ext == 'png') {
		        if (isset($tmp_name)) {
		            if(!empty($tmp_name)) {
		                $location = '../assets/usrImg/';
		                $full_name = $num.'.'.$ext;
		                if(file_exists($location)){
		                	if(move_uploaded_file($tmp_name, $location.$full_name)) {
		                		$query_insert_pic = "INSERT INTO picture (img_name, user_id, date_uploaded) VALUES ('$full_name', '$user', NOW())";
		                		$query_status = mysqli_query($conn, $query_insert_pic);

		                		if($query_status) {
		                			$imgVal = $num;
		                			$_SESSION['userImg'] = $num;
		                			displayMessage('Image uploaded successfully.');	
		                		} else {
			                		displayMessage('Image upload unsuccessful.');
			                	}
			                }	
		                }
		            }
		        }
		    } else {
		    	displayMessage('Pleae upload only png images.');
		    }
		}
		$query_update_user = "UPDATE user SET first_name = '$fname', last_name = '$lname', nick_name = '$nname', mobile = '$mob', location = '$loc', qualification = '$quali', achievement = '$achiv', picture = '$imgVal' WHERE user_id = '$user'";
		$query_status = mysqli_query($conn, $query_update_user);

		$_SESSION['userNickName'] = $nname;
		if(!($fname == "")) {
			$_SESSION['userName'] = $fname;
		}
	}
}

// Create a team
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnTeamCreate']) ) {
		$user = $_SESSION['userId'];
		$tname = $_POST['teamName'];
		$tdesc = $_POST['teamDesc'];

		$query_insert_team = "INSERT INTO team (team_id, team_desc, team_admin_id, date_created, team_status) 
								VALUES ('$tname', '$tdesc', '$user', NOW(), 'active')";
		$query_status = mysqli_query($conn, $query_insert_team);

		$query_insert_relation = "INSERT INTO relation (partner_1, partner_2, relation_type, date_related, relation_status) 
										VALUES ('$tname', '$user', 'member', NOW(), 'connected')";
		$query_status = mysqli_query($conn, $query_insert_relation);

		$_SESSION['target_team_id'] = $tname;
	}
}

// Prepare session temp for stamp editor
function setStampTempEditor($var){
	$_SESSION['stamp_temp_editor'] = $var;
}

function setStampTempTextarea($var){
	$_SESSION['stamp_temp_textarea'] = $var;
}

function setCommentTempTextarea($var){
	$_SESSION['comment_temp_textarea'] = $var;
}


// Getting values
function getTextArea(){
	if( isset($_SESSION['stamp_temp_textarea']) && isset($_SESSION['stamp_temp_editor'])) {
		$txt = $_SESSION['stamp_temp_textarea'];
		$edt = $_SESSION['stamp_temp_editor'];

		$_SESSION['stamp_temp_textarea'] = $txt . ' ' . '<code><pre>' . $edt . '</pre></code>';
		echo $_SESSION['stamp_temp_textarea'];
	}
}

function getCommentTextArea(){
	if( isset($_SESSION['comment_temp_textarea']) && isset($_SESSION['stamp_temp_editor'])) {
		$txt = $_SESSION['comment_temp_textarea'];
		$edt = $_SESSION['stamp_temp_editor'];

		$_SESSION['comment_temp_textarea'] = $txt . ' ' . '<code><pre>' . $edt . '</pre></code>';
		echo $_SESSION['comment_temp_textarea'];
	}
}


function getUserId() {	
	if (isset($_SESSION['userId'])) {
		echo $_SESSION['userId'];
	}
}

// Name to display based on firstname availability
function getUserName() {
	if (isset($_SESSION['target_user_id'])) {
		global $conn;
		$user = $_SESSION['target_user_id'];
		$query_name = "SELECT first_name FROM user WHERE user_id = '$user'";	    
	    $query_status = mysqli_query($conn, $query_name);

	    $name = mysqli_fetch_assoc($query_status)['first_name'];
	    if ($name == "") {
	    	echo $_SESSION['target_user_id'];
	    } else {
	    	echo $name;
	    }
	} elseif (isset($_SESSION['userName'])) {
		echo $_SESSION['userName'];
	}
}

function getNickName() {
	$nick = $_SESSION["userNickName"];

	if(isset($_SESSION['target_user_id'])) {
		$nick = $_SESSION["target_user_id"];	
	}
	
    if ($nick == "") {
    	echo "A dev-o-talk user";
    }
    echo $nick;
}

function getUserImage() {
	$img = $_SESSION["userImg"];
    if ($img == "") {
    	return "defaultUser";
    }
    return $img;
}

// profile picture for self or team
function getTargetImage() {
	global $conn;

	if (isset($_SESSION['target_user_id'])) {
		$user = $_SESSION['target_user_id'];
	} elseif (isset($_SESSION['target_team_id'])) {
		return "defaultTeam";
	} else {
		$user = $_SESSION['userId'];
	}
	
	$query_img = "SELECT picture FROM user WHERE user_id = '$user'";	    
    $query_status = mysqli_query($conn, $query_img);

    $img = mysqli_fetch_assoc($query_status)['picture'];
    if ($img == "") {
    	return "defaultUser";
    }
    return $img;
}

// profile image of other users
function getProfileImage($usr) {
	$query_img = "SELECT picture FROM user WHERE user_id='$usr'";

    global $conn;
    $query_status = mysqli_query($conn, $query_img);
    
    $img = mysqli_fetch_assoc($query_status)['picture'];
    if ($img == "") {
    	return "defaultUser";
    }
    return $img;
}

function getUserEmail() {	
	if (isset($_SESSION['userEmail'])) {
		echo $_SESSION['userEmail'];
	}
}

// get team name based on selection
function getTeamName() {	
	if (isset($_SESSION['target_team_id'])) {
		echo $_SESSION['target_team_id'];
	}
}

function displayMessage($var) {
	echo "<script> alert('$var'); </script>";
}

// Filling storyboard
function getStoryboardPosts() {
	$me = $_SESSION['userId'];
	$query_posts = "SELECT * FROM stamp WHERE team_id IN ('') AND (user_id IN (SELECT partner_1 FROM relation WHERE partner_2 = '$me' AND relation_type = 'friend' AND relation_status = 'connected') OR user_id IN (SELECT partner_2 FROM relation WHERE partner_1 = '$me' AND relation_type = 'friend' AND relation_status = 'connected') OR user_id = '$me') ORDER BY date_created DESC";

    global $conn;
    $query_status = mysqli_query($conn, $query_posts);
    return $query_status;
}

// Filling team storyboard
function getTeamStoryboard() {
	$teamName = $_SESSION['target_team_id'];
	$query_posts = "SELECT * FROM stamp WHERE team_id = '$teamName' ORDER BY date_created DESC";

    global $conn;
    $query_status = mysqli_query($conn, $query_posts);
    return $query_status;
}

function getUserPosts() {
	if (isset($_SESSION['target_user_id'])) {
		$user = $_SESSION['target_user_id'];
		$query_posts = "SELECT * FROM stamp WHERE user_id = '$user' AND team_id = '' ORDER BY date_created DESC";	
	} else {
		$user = $_SESSION['userId'];
		$query_posts = "SELECT * FROM stamp WHERE user_id = '$user' ORDER BY date_created DESC";	
	}

    global $conn;
    $query_status = mysqli_query($conn, $query_posts);
    return $query_status;
}

function getUserProfile() {
	if (isset($_SESSION['target_user_id'])) {
		$user = $_SESSION['target_user_id'];	
	} else {
		$user = $_SESSION['userId'];	
	}
	$query_posts = "SELECT * FROM user WHERE user_id = '$user'";

    global $conn;
    $query_status = mysqli_query($conn, $query_posts);
    return $query_status;
}

function getStamp($var) {
	$query_posts = "SELECT * FROM stamp WHERE sn = '$var'";

    global $conn;
    $query_status = mysqli_query($conn, $query_posts);
    return $query_status;
}

function getNotifConnectionDetails() {
	$to = $_SESSION['target_user_id'];
	$by = $_SESSION['userId'];
	$query_notif = "SELECT * FROM notification WHERE (notif_to = '$to' AND notif_by = '$by') OR (notif_to = '$by' AND notif_by = '$to') ORDER BY sn DESC";

    global $conn;
    $query_status = mysqli_query($conn, $query_notif);
    return $query_status;
}

function getNotificationDetails() {
	$to = $_SESSION['userId'];
	$query_notif = "SELECT * FROM notification WHERE notif_to = '$to' AND notif_status = 'pending'";

    global $conn;
    $query_status = mysqli_query($conn, $query_notif);
    return $query_status;
}

// get team details
function getTeamDetails() {
	$team = $_SESSION['target_team_id'];
	$query_team = "SELECT * FROM team WHERE team_id = '$team' AND team_status = 'active'";

    global $conn;
    $query_status = mysqli_query($conn, $query_team);
    return $query_status;
}

function getMyTeams() {
	$me = $_SESSION['userId'];
	$query_teams = "SELECT partner_1 FROM relation WHERE partner_2 = '$me' AND relation_type = 'member' AND relation_status = 'connected'";

    global $conn;
    $query_status = mysqli_query($conn, $query_teams);
    return $query_status;
}

function getMyFriends() {
	$me = $_SESSION['userId'];
	$query_friends = "SELECT partner_1 AS friends FROM relation WHERE partner_2 = '$me' AND relation_type = 'friend' AND relation_status = 'connected' UNION SELECT partner_2 FROM relation WHERE partner_1 = '$me' AND relation_type = 'friend' AND relation_status = 'connected'";

    global $conn;
    $query_status = mysqli_query($conn, $query_friends);
    return $query_status;
}

// Get team members
function getTeamMembers() {
	$team = $_SESSION['target_team_id'];
	$query_teams = "SELECT partner_2 FROM relation WHERE partner_1 = '$team' AND relation_type = 'member' AND relation_status = 'connected'";

    global $conn;
    $query_status = mysqli_query($conn, $query_teams);
    return $query_status;
}

function getStampComments($var) {
	$query_comments = "SELECT * FROM comment WHERE stamp_sn = '$var'";

    global $conn;
    $query_status = mysqli_query($conn, $query_comments);
    return $query_status;
}

// check user in team
function isUserInTeam() {
	$me = $_SESSION['userId'];
	$team = $_SESSION['target_team_id'];
	$query_user = "SELECT partner_2 FROM relation WHERE partner_1 = '$team' AND partner_2 = '$me' AND relation_type = 'member' AND relation_status = 'connected'";

    global $conn;
    $query_status = mysqli_query($conn, $query_user);
    if(mysqli_num_rows($query_status) > 0) {
    	return true;
    }
    return false;
}

// check user in user
function isValidUser($var) {
	$query_user = "SELECT user_id FROM user WHERE user_id = '$var'";

    global $conn;
    $query_status = mysqli_query($conn, $query_user);
    if(mysqli_num_rows($query_status) > 0) {
    	return true;
    }
    return false;
}

// check user is team admin
function isTeamAdmin() {
	$me = $_SESSION['userId'];
	$team = $_SESSION['target_team_id'];
	$query_admin = "SELECT team_admin_id FROM team WHERE team_id = '$team' AND team_admin_id = '$me' AND team_status = 'active'";

    global $conn;
    $query_status = mysqli_query($conn, $query_admin);
    if(mysqli_num_rows($query_status) > 0) {
    	return true;
    }
    return false;
}

// logout
if( isset($_POST['btnLogOut']) ) {
	session_destroy();
}

?>