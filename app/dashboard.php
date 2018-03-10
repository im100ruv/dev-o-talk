<?php include('DBAdmin.php') ?>
<?php unset($_SESSION['target_user_id']); ?>
<?php unset($_SESSION['target_team_id']); ?>
<?php unset($_SESSION['stamp_temp_editor']); ?>
<?php unset($_SESSION['stamp_temp_textarea']); ?>
<?php unset($_SESSION['comment_temp_textarea']); ?>
 
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
	        <a class="uk-navbar-item uk-logo" href="#">{dev-o-talk}</a>
		</div>
	    <div class="uk-navbar-item uk-navbar-right">
	        <img class="uk-navbar-item uk-navbar-nav uk-navbar-icon" src="../assets/img/kb.png" width="100px">
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
						<div class="uk-width-1-2"></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div id="notifModal" class="uk-modal uk-flex-top" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" uk-overflow-auto>
	    	<p class="uk-modal-title">Your Notifications</p>
	        <button class="uk-modal-close-default" type="button" uk-close></button>

	        <!-- Modal content-->
	        <?php
	        	$notifs = getNotificationDetails();
	        	$numRows = mysqli_num_rows($notifs);
	        	if($numRows > 0) {
	    			while($row =mysqli_fetch_assoc($notifs)) {
	    				echo 
	    				'<ul class="uk-list uk-list-striped">
						    <li> 
						    	<form method="POST" action="profile.php">
						    		' . $row["notif_by"] . '&nbsp;'; 
						    		if($row['notif_by_type'] == 'team') {
						    			echo 'invited you to join the team.'; 
						    		} elseif ($row['notif_by_type'] == 'individual') {
						    			echo 'requested to connect with you.';
						    		}
							    	echo 
							    	'<div class="uk-text-right">
							    		<input type="text" name="hiddenByValue" value="' . $row["notif_by"] . '" hidden />
							    		<input type="text" name="hiddenByTypeValue" value="' . $row["notif_by_type"] . '" hidden />
							    		<input type="submit" class="uk-button-primary uk-button-small" name="btnConfirmConnect" value="Connect" />
							    		<input type="submit" class="uk-button-secondary uk-button-small" name="btnViewProfile" value="View Profile" />
							    	</div>
						    	</form>
						    </li>
						</ul>';
	    			}
	    		} else {
	    			echo 'No new notifications';
	    		}
	        ?>
	    </div>
	</div>

	<div id="createTeamModal" class="uk-modal uk-flex-top" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" uk-overflow-auto>
	    	<p class="uk-modal-title">Enter Team Details</p>
	        <button class="uk-modal-close-default" type="button" uk-close></button>

	        <!-- Modal content-->
	        <form method="POST" action="team.php">
			    <fieldset class="uk-fieldset">
			        <div class="uk-margin">
			            <input class="uk-input" type="text" name="teamName" placeholder="team id">
			        </div>
			        <div class="uk-margin">
			            <textarea class="uk-textarea" rows="5" name="teamDesc" placeholder="description"></textarea>
			        </div>
			        <div class="uk-margin">
			            <input type="submit" class="uk-button-primary uk-button-large" name="btnTeamCreate" value="Create">
			        </div>
			    </fieldset>
			</form>
	    </div>
	</div>

	<div id="teamModal" class="uk-modal uk-flex-top" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" uk-overflow-auto>
	    	<p class="uk-modal-title">Your Teams</p>
	        <button class="uk-modal-close-default" type="button" uk-close></button>

	        <!-- Modal content-->
	        <?php
	        	$myTeams = getMyTeams();
	        	$numTeamRows = mysqli_num_rows($myTeams);
	        	if($numTeamRows > 0) {
	    			while($row =mysqli_fetch_assoc($myTeams)) {
	    				echo 
	    				'<ul class="uk-list uk-list-striped">
						    <li> 
						    	<form method="POST" action="team.php">
							    	<div class="uk-text-center">
							    		<input type="submit" class="uk-button-primary uk-button-large" name="btnMyTeam" value="' . $row["partner_1"] . '" />
							    	</div>
						    	</form>
						    </li>
						</ul>';
	    			}
	    		} else {
	    			echo 'No Teams Joined.';
	    		}
	        ?>
	        <div class="uk-text-right">
	        	<button class="uk-button uk-button-secondary" uk-toggle="target: #createTeamModal">Create A Team</button>
	        </div>
	    </div>
	</div>

	<div id="friendModal" class="uk-modal uk-flex-top" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical" uk-overflow-auto>
	    	<p class="uk-modal-title">Your Friends</p>
	        <button class="uk-modal-close-default" type="button" uk-close></button>

	        <!-- Modal content-->
	        <?php
	        	$myFriends = getMyFriends();
	        	$numFriendRows = mysqli_num_rows($myFriends);
	        	if($numFriendRows > 0) {
	    			while($friendRow =mysqli_fetch_assoc($myFriends)) {
	    				echo 
	    				'<ul class="uk-list uk-list-striped">
						    <li> 
						    	<form method="POST" action="timeline.php">
							    	<div class="uk-text-center">
							    		<input type="submit" class="uk-button-primary uk-button-large" name="btnMyFriend" value="' . $friendRow["partner_1"] . '" />
							    	</div>
						    	</form>
						    </li>
						</ul>';
	    			}
	    		} else {
	    			echo 'No friends yet.';
	    		}
	        ?>
	    </div>
	</div>

	<section class="uk-section uk-position-relative">
		<div class="uk-container uk-container-small uk-card uk-card-default">
			<br>
			<div class="uk-child-width-1-3 uk-grid-small uk-text-center" uk-grid>
			    <div>
			        <a href="./profile.php"><div class="uk-card uk-card-secondary uk-card-body">Profile</div></a>
			    </div>
			    <div>
			        <a href="./storyboard.php"><div class="uk-card uk-card-secondary uk-card-body">Storyboard</div></a>
			    </div>
			    <div>
			        <div class="uk-card uk-card-secondary uk-card-body" uk-toggle="target: #notifModal">Notifications 
			        	<?php 
			        		if($numRows > 0) {
			        			echo '(' . $numRows . ')';
			        		} 
			        	?>		
			        </div>
			    </div>
			    <div>
			        <a href="./timeline.php"><div class="uk-card uk-card-secondary uk-card-body">Stamps</div></a>
			    </div>
			    <div>
			        <div class="uk-card uk-card-secondary uk-card-body" uk-toggle="target: #teamModal">Team</div>
			    </div>
			    <div>
			        <div class="uk-card uk-card-secondary uk-card-body" uk-toggle="target: #friendModal">Friends</div>
			    </div>
			</div>
			<br>
		</div>
	</section>
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