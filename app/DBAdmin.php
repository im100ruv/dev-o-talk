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
// $_SESSION['stamp_temp_editor']
// $_SESSION['stamp_temp_textarea']
// $_SESSION['comment_temp_textarea']
// $_SESSION['curr_stamp_sn']
// ===============================================================


//Register a user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnRegister']) ) {
		$user_id = mysqli_real_escape_string($conn, $_POST['username']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		$confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

		$query_find = "SELECT * FROM user WHERE user_id = '$user_id'";
	    $query_status = mysqli_query($conn, $query_find);
	    // print_r($query_status);

	    if(mysqli_num_rows($query_status) == 0) {
	    	$query_insert = "INSERT INTO user (user_id, email, date_created, last_login, user_type, active_status)
							VALUES ('$user_id', '$email', NOW(), NOW(), 'general', '1')";

			$query_status = mysqli_query($conn, $query_insert);
			if($query_status) {
				$query_insert = "INSERT INTO login (user_id, password) VALUES ('$user_id','$password')";
				$query_status = mysqli_query($conn, $query_insert);
				displayMessage("Register successful.");
				prepareSessionVar($user_id);
			} else {
				displayMessage("Registration error...! Please try after sometime.");
			}
	    } else {
	    	displayMessage("User already exists.");
	    }
	}
}

//Login user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnLogin']) ) {
		$user_id = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$query_select = "SELECT * FROM login WHERE user_id = '$user_id' AND password = '$password'";
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
        }
        // if($_SESSION['type'] == 'Admin')
        //   header('Location: admin.php');
        // else
        //   header('Location: user.php');
        header('Location: dashboard.php');
  	}
}

// posting stamp
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( isset($_POST['btnPost']) ) {
		$stamp_text = mysqli_real_escape_string($conn, $_POST['stampText']);
		setStampTempTextarea($stamp_text);

		$user = $_SESSION['userId'];
		$stamp = $_SESSION['stamp_temp_textarea'];

		$query_insert_stamp = "INSERT INTO stamp (user_id, message, team_id, date_created) 
								VALUES ('$user', '$stamp', 'refTeam', NOW())";
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

// function getStampTempEditor(){
// 	echo $_SESSION['stamp_temp_editor'];
// }

// function getStampTempTextarea(){
// 	echo $_SESSION['stamp_temp_textarea'];
// }

function getUserId() {	
	if (isset($_SESSION['userId'])) {
		echo $_SESSION['userId'];
	}
}

function getUserName() {	
	if (isset($_SESSION['userName'])) {
		echo $_SESSION['userName'];
	}
}

function getUserEmail() {	
	if (isset($_SESSION['userEmail'])) {
		echo $_SESSION['userEmail'];
	}
}

function displayMessage($var) {
	echo "<script> alert('$var'); </script>";
}



// Filling storyboard
function getStoryboardPosts() {
	// query should show posts from friends only--- change query
	$query_posts = "SELECT * FROM stamp";

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

function getStampComments($var) {
	$query_comments = "SELECT * FROM comment WHERE stamp_sn = '$var'";

    global $conn;
    $query_status = mysqli_query($conn, $query_comments);
    return $query_status;
}



?>