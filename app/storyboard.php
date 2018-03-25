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

	<section class="uk-section uk-position-relative">
		<div class="uk-container uk-container-small uk-card uk-card-default" style="background-color: #E0F2F1">
			<div class="uk-grid uk-grid-margin uk-grid-stack" uk-grid>
			    <div class="uk-width-1-1 uk-first-column">
			    	<?php 
			    		$img = getUserImage();
			    		echo
			        	'<img class="uk-navbar-item uk-navbar-nav uk-navbar-icon uk-border-circle" src="../assets/usrImg/'.$img.'.png" width="100px">';
			        ?>
			    	<form class="uk-form" method="POST" action="storyboard.php">
			    		<input type="text" name="fromPageHidden" value="storyboard" hidden />
			    		<div class="uk-margin">
				            <textarea id="stampText" class="uk-textarea" rows="8" placeholder="Write your stamp here.." name="stampText"><?php getTextArea(); ?></textarea>
				        </div>
			    		<input type="submit" name="btnEditor" class="uk-button uk-button-secondary uk-align-left" style="background-color: #009688" value="Open Editor" />
			    		<input type="submit" name="btnPost" class="uk-button uk-button-primary uk-align-right" style="background-color: #004D40" value="Post" />
			    	</form>
			    </div>
			</div>
		</div>
	</section>
	
	<?php 
		$result_stamps = getStoryboardPosts();
        if(mysqli_num_rows($result_stamps) > 0) {
          	while($row =mysqli_fetch_assoc($result_stamps)) { 
          		$result_comments = getStampComments($row['sn']);
				echo
				'<section class="uk-section uk-position-relative">
					<div class="uk-container uk-container-small uk-card uk-card-default" style="background-color: #E0F2F1">
						<div class="uk-grid uk-grid-margin uk-grid-stack" uk-grid>
						    <div class="uk-width-1-1 uk-first-column">';
						    	$img = getProfileImage($row['user_id']);
					    		echo
					        	'<img class="uk-navbar-item uk-navbar-nav uk-navbar-icon uk-align-left uk-border-circle" src="../assets/usrImg/'.$img.'.png" width="100px">';
						    	echo
						    	'<div class="uk-column-1-1">
						    		<label class="uk-text-large">' . $row['user_id'] . '</label>
						    		<label class="uk-text-small">&nbsp; posted</label>
						    	</div>
						    	<div class="uk-column-1-1">
						    		<label class="uk-text-small">' . $row['date_created'] . '</label>	
						    	</div>
						    	<div class="uk-section">
					    			' . $row['message'] . '
						    	</div>
						    </div>
						</div>
					    <div class="uk-width-5-6"></div>
					    <label class="uk-width-1-6 uk-align-right">' . mysqli_num_rows($result_comments) . ' comments </label>
					    <div></div>
					</div>';
					if(mysqli_num_rows($result_comments) > 0) {
			          	while($row_comments =mysqli_fetch_assoc($result_comments)) { 
							echo
							'<div class="uk-container uk-container-small uk-card uk-card-default" style="background: #E0E0E0">
								<div class="uk-grid uk-grid-margin uk-grid-stack" uk-grid>
								    <div class="uk-width-1-1 uk-first-column">';
								    	$img = getProfileImage($row_comments['user_id']);
							    		echo
							        	'<img class="uk-navbar-item uk-navbar-nav uk-navbar-icon uk-align-left uk-border-circle" src="../assets/usrImg/'.$img.'.png" width="100px">';
								    	echo
								    	'<div class="uk-column-1-1">
								    		<label class="uk-text-large">' . $row_comments['user_id'] . '</label>
								    		<label class="uk-text-small">&nbsp; ' . $row_comments['date_commented'] . '</label>
								    	</div>
								    	<div class="uk-section">
							    			' . $row_comments['comment_message'] . '
								    	</div>
								    </div>
								</div>
							    <div></div>
							</div>';
						}
					}
				echo 
					'<div class="uk-container uk-container-small uk-card uk-card-default" style="background: lightgrey">
					    <div class="uk-width-5-6"></div>
					    <label class="uk-width-1-6 uk-align-center" style="color: #00796B" name="demo" onclick="document.getElementById(' . $row['sn'] . ').click();"> 
					    	Add a comment 
					    </label>
					    <form method="POST" action="stamp.php">
					    	<input type="text" name="txtHidden" value="' . $row['sn'] . '" hidden />
					    	<input type="submit" id="' . $row['sn'] . '" name="btnAddCommentHidden" hidden />
					    </form>
					    <div></div>
					</div>
				</section>';
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