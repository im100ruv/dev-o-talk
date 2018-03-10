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
	<nav class="uk-container uk-navbar-container uk-margin" uk-navbar>
	    <div class="uk-navbar-left">
	        <a class="uk-navbar-item uk-logo" href="dashboard.php">{dev-o-talk}</a>
		</div>
	    <div class="uk-navbar-item uk-navbar-right">
	    	<form method="POST" action="profile.php">
	    		<input class="uk-text-small" type="text" name="targetUser" placeholder="type userid" />
	    		<input class="uk-button-small uk-button-primary" type="submit" name="btnUserSearch" value="Search" />
	    		<input class="uk-button-small uk-button-secondary" type="submit" name="btnSelfSearch" value="See my own" />
	    	</form>
	        <img class="uk-navbar-item uk-navbar-nav uk-navbar-icon" src="../assets/img/kb.png">
	        <form method="POST" action="index.php">
	        	<input class="uk-button-small uk-button-danger" type="submit" name="btnLogOut" value="Log out" />
	        </form>
	    </div>
	</nav>

	<section class="uk-position-relative">
		<div class="uk-section" style="background-color: #2F9A44; background-size: 100%; height: 250px;">
			<div class="uk-grid uk-grid-divider" data-uk-grid-margin>
				<div class="uk-vertical-align-middle uk-text-center uk-width-1-2">
					<div class="uk-grid">
						<div class="uk-width-1-2"></div>
						<div class="uk-width-1-2">
							<img class="uk-navbar-item uk-align-right" src="../assets/img/kb.png">
						</div>
					</div>					
				</div>
				<div class="uk-vertical-align-middle uk-text-center uk-width-1-2">
					<div class="uk-grid">
						<div class="uk-width-1-2">
							<h2 class="uk-heading-large" style="color: #FFFFFF"><?php getUserName() ?></h2>
							<h5>iOS developer</h5>
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
	    									'<button class="uk-button-primary uk-button-large"> Connection Requested </button>';
	    								} elseif($row['notif_status'] == 'accepted') {
	    									echo 
	    									'<button class="uk-button-primary uk-button-large"> Connected </button>';
	    									echo 
	    									'<input type="submit" class="uk-button-danger uk-button-small" name="btnDisconnectUser" value="Disconnect" />';
	    								} elseif($row['notif_status'] == 'deleted') {
	    									echo 
	    									'<input type="submit" class="uk-button-primary uk-button-large" name="btnConnectUser" value="Connect Now" />';
	    								}
	    							} else {
	    								echo 
	    								'<input type="submit" class="uk-button-primary uk-button-large" name="btnConnectUser" value="Connect Now" />';
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
			        <form method="POST" action="profile.php">
					    <fieldset class="uk-fieldset">
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
					            <input class="uk-input" type="text" name="mobile" placeholder="mobile">
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
					            <input type="submit" class="uk-button-primary uk-button-large" name="btnProfileForm" value="Submit">
					        </div>
					    </fieldset>
					</form>
			    </div>
			</div>

			<div class="uk-text-right"> <button class="uk-button-primary uk-button-small" uk-toggle="target: #profileModal">Edit Profile</button> </div>';
		}
	?>
	
	<?php 
		$result_profile = getUserProfile();
        if(mysqli_num_rows($result_profile) > 0) {
          	while($userRow =mysqli_fetch_assoc($result_profile)) { 
				echo
				'<section class="uk-section uk-position-relative">
					<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body">
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
					<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body">
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
					<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body">
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
						'<div class="uk-container uk-container-medium uk-card uk-card-default uk-card-body">
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
</body>
</html>