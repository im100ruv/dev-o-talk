<?php include('DBAdmin.php') ?>

<!DOCTYPE html>
<html>
<head>
	<title><?php getUserName() ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../node_modules/uikit/dist/css/uikit.min.css">
	<script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
	<script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</head>
<body>
	<section uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; bottom: #transparent-sticky-navbar">
		<nav class="uk-container uk-navbar-container uk-margin" style="background-color: #004D40" uk-navbar>
		    <div class="uk-navbar-left">
		        <a class="uk-navbar-item uk-logo" style="color: #FFFFFF" href="dashboard.php">{dev-o-talk}</a>
			</div>
		    <div class="uk-navbar-item uk-navbar-right">
		    	<form method="POST" action="profile.php">
		    		<input class="uk-text-small" type="text" name="targetUser" placeholder="type userid" />
		    		<input class="uk-button-small uk-button-primary" style="background-color: #009688" type="submit" name="btnUserSearch" value="Search" />
		    		<input class="uk-button-small uk-button-secondary" style="background-color: #00796B" type="submit" name="btnSelfSearch" value="See my own" />
		    	</form>
		        <?php 
		    		$img = getUserImage();
		    		echo
		        	'<img class="uk-navbar-item uk-navbar-nav uk-navbar-icon uk-border-circle" src="../assets/usrImg/'.$img.'.png" width="100px">';
		        ?>
		        <form method="POST" action="index.php">
		        	<input class="uk-button-small uk-button-danger" style="background-color: #AE2204;" type="submit" name="btnLogOut" value="Sign out" />
		        </form>
		    </div>
		</nav>
	</section>

	<section class="uk-position-relative uk-container">
		<div class="uk-section" style="background-color: #00695C; background-size: 100%; height: 250px;">
			<div class="uk-grid uk-grid-divider" data-uk-grid-margin>
				<div class="uk-vertical-align-middle uk-text-center uk-width-1-2">
					<div class="uk-grid">
						<div class="uk-width-1-2"></div>
						<div class="uk-width-1-2">
							<?php 
					    		$targetImg = getTargetImage();
					    		echo
								'<img class="uk-navbar-item uk-align-right uk-border-circle" src="../assets/usrImg/'.$targetImg.'.png">';
							?>
						</div>
					</div>					
				</div>
				<div class="uk-vertical-align-middle uk-text-center uk-width-1-2">
					<div class="uk-grid">
						<div class="uk-width-1-2">
							<h2 class="uk-heading-large" style="color: #FFFFFF"><?php getUserName() ?></h2>
							<h5 style="color: #FFFFFF"><?php getNickName() ?></h5>
						</div>
						<div class="uk-width-1-2">
							<?php 
							  	if(isset($_SESSION['target_user_id'])) {
								  	echo 
								  	'<form method="POST" action="profile.php">';
										 
									$result = getNotifConnectionDetails();
	    							if(mysqli_num_rows($result) > 0) {
	    								$row =mysqli_fetch_assoc($result);
	    								if($row['notif_status'] == 'pending') {
	    									echo 
	    									'<button class="uk-button-primary uk-button-large" style="background-color: #009688"> Connection Requested </button>';
	    								} elseif($row['notif_status'] == 'accepted') {
	    									echo 
	    									'<button class="uk-button-primary uk-button-large" style="background-color: #009688"> Connected </button>';
	    									echo 
	    									'<input type="submit" class="uk-button-danger uk-button-small" style="background-color: #AE2204;" name="btnDisconnectUser" value="Disconnect" />';
	    								} elseif($row['notif_status'] == 'deleted') {
	    									echo 
	    									'<input type="submit" class="uk-button-primary uk-button-large" style="background-color: #009688" name="btnConnectUser" value="Connect Now" />';
	    								}
	    							} else {
	    								echo 
	    								'<input type="submit" class="uk-button-primary uk-button-large" style="background-color: #009688" name="btnConnectUser" value="Connect Now" />';
	    							}
									echo 
									'</form>';
							  	}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
		if(!(isset($_SESSION['target_user_id']))) {
			echo 
			'<div id="profileModal" class="uk-modal uk-flex-top" uk-modal>
			    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" uk-overflow-auto>
			    	<p class="uk-modal-title">Your Profile Details</p>
			        <button class="uk-modal-close-default" type="button" uk-close></button>

			        <!-- Modal content-->
			        <form method="POST" action="profile.php" enctype="multipart/form-data">
					    <fieldset class="uk-fieldset">
					    	<div class="uk-margin">
					            <input class="uk-input" type="file" name="imgInput">
					        </div>
					        <div class="uk-margin">
					            <input class="uk-input" type="text" name="firstName" placeholder="first name">
					        </div>
					        <div class="uk-margin">
					            <input class="uk-input" type="text" name="lastName" placeholder="last name">
					        </div>
					        <div class="uk-margin">
					            <input class="uk-input" type="text" name="nickName" placeholder="nickname">
					        </div>
					        <div class="uk-margin">
					            <input class="uk-input" type="text" id="mobile" name="mobile" placeholder="mobile" onchange="isValidMobile();">
					        </div>
					        <div class="uk-margin">
					            <textarea class="uk-textarea" rows="5" name="location" placeholder="location"></textarea>
					        </div>
					        <div class="uk-margin">
					            <textarea class="uk-textarea" rows="5" name="qualification" placeholder="qualification"></textarea>
					        </div>
					        <div class="uk-margin">
					            <textarea class="uk-textarea" rows="5" name="achievement" placeholder="achievement"></textarea>
					        </div>
					        <div class="uk-margin">
					            <input type="submit" class="uk-button-primary uk-button-large" style="background-color: #004D40" name="btnProfileForm" value="Submit">
					        </div>
					    </fieldset>
					</form>
			    </div>
			</div>

			<div id="changePasswordModal" class="uk-modal uk-flex-top" uk-modal>
			    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" uk-overflow-auto>
			    	<p class="uk-modal-title">Change your password</p>
			        <button class="uk-modal-close-default" type="button" uk-close></button>

			        <!-- Modal content-->
			        <form method="POST" action="profile.php">
					    <div class="uk-margin">
							<input class="uk-input" type="password" placeholder="Old Password" name="oldPassword">
						</div>
						<div class="uk-margin">
							<input class="uk-input" type="password" placeholder="New Password" name="newPassword">
						</div>
						<div class="uk-margin">
							<input class="uk-input" type="password" placeholder="Confirm Password" name="cnfPassword">
						</div>
						<div class="uk-margin">
							<input type="submit" class="uk-button-primary uk-button-large" style="background-color: #004D40" name="btnChangePass" value="Set Password">
						</div>
					</form>
			    </div>
			</div>

			<div class="uk-container">
				<div class="uk-text-right"> 
					<button class="uk-button-primary uk-button-small" style="background-color: #009688" uk-toggle="target: #profileModal">Edit Profile</button> 
					<button class="uk-button-primary uk-button-small" style="background-color: #009688" uk-toggle="target: #changePasswordModal">Change Password</button>
				</div>
			</div>';
		}
	?>
	
	<?php 
		$result_profile = getUserProfile();
        if(mysqli_num_rows($result_profile) > 0) {
          	while($userRow =mysqli_fetch_assoc($result_profile)) { 
				echo
				'<section class="uk-section uk-position-relative">
					<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body" style="background-color: #E0F2F1">
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Username </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['user_id'] . ' </h3> </div>
						</div>
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Firstname </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['first_name'] . ' </h3> </div>
						</div>
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Lastname </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['last_name'] . ' </h3> </div>
						</div>
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Nickname </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['nick_name'] . ' </h3> </div>
						</div>
					</div>
					<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body" style="background-color: #E0F2F1">
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Email </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['email'] . ' </h3> </div>
						</div>
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Mobile </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['mobile'] . ' </h3> </div>
						</div>
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Location </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['location'] . ' </h3> </div>
						</div>
					</div>
					<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body" style="background-color: #E0F2F1">
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Qualification </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['qualification'] . ' </h3> </div>
						</div>
						<div class="uk-grid" uk-grid>
							<div class="uk-width-1-4"> <h3> Achievement </h3> </div>
							<div class="uk-width-3-4"> <h3> ' . $userRow['achievement'] . ' </h3> </div>
						</div>
					</div>';
					if(!isset($_SESSION['target_user_id'])) {
						echo 
						'<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body" style="background-color: #E0F2F1">
							<div class="uk-grid" uk-grid>
								<div class="uk-width-1-4"> <h3> Last Login </h3> </div>
								<div class="uk-width-3-4"> <h3> ' . $userRow['last_login'] . ' </h3> </div>
							</div>
						</div>';
					}
					
				echo '</section>';
			}
		}
	?>

	<footer style="position: relative; padding: 60px 0 0 0; background-color: #202020;">
		<div class="bottom-line">
			<div uk-grid>
				<div class="uk-text-left uk-width-auto@m">
						<a href="#">Terms &amp; Condition</a>
				</div>
				<div class="uk-text-middle uk-width-auto@m">
						<p>Made by your neighbours at Tirupati, India</p>
				</div>
				<div class="uk-text-right uk-width-expand@m" style="padding-right: 30px;">
					<p>&COPY;dev-o-talk 2018</p>
				</div>
			</div>
		</div>
		<a href="#impx-body" class="impx-to-top" data-uk-smooth-scroll="{offset: 0}"><i class="uk-icon-long-arrow-up"></i></a>
	</footer>

	<script>
		function isValidMobile() {
			var mob = document.getElementById('mobile').value;
			if (isNaN(mob)) {
				alert('enter only digits');
				document.getElementById('mobile').value = "";
			} else if(mob.length != 10){
				alert('enter valid mobile number of 10 digits');
				document.getElementById('mobile').value = "";
			}
		}
	</script>
</body>
</html>