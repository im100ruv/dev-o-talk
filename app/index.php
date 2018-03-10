<?php include('DBAdmin.php') ?>

<!DOCTYPE html>
<html>
	<head>
		<title>{dev-o-talk}</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../node_modules/uikit/dist/css/uikit.min.css">
		<script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
		<script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
	</head>
	<body>
		<section class="uk-position-relative">
			<div class="uk-container uk-background-norepeat uk-background-cover uk-section" style="background: url(../assets/img/landing.jpg) no-repeat; background-size: 100%; height: 550px; ">
				<div style="background-color: rgba(80,80,77,0.7);" class="uk-position-top uk-navbar-container uk-navbar-transparent">
					<div class="uk-navbar-left">
						<ul class="uk-navbar-nav">
							<li style="color: #0094E7; font-size: 30px; margin-left: 120px"><b><a href="index.php">{dev-o-talk}</a></b></li>
							<li style="color: #FFFFFF; font-size: 18px; margin-left: 40px;"> The private social network for developers</li>
						</ul>
						<div class="uk-navbar-right">
							<ul type="none" class="uk-grid">
								<li class="uk-width-1-1" style="color: #FFFFFF; font-size: 20px;"><span uk-icon="icon: sign-in"></span><a href="#signInForm">Sign in</a></li>
								<li class="uk-width-1-1"></li>
							</ul>
						</div>
					</div>
				</div><br><br><br><br><br><br><br><br><br><br>
				<div class="uk-grid" style="">
					<div class="uk-width-1-3"></div>
					<div class="uk-text-center uk-width-1-3">
						<h4 class="uk-heading-large" style="color: #FFFFFF; font-size: 30px;">Discover and connect to developers around you.</h4>
						<a class="uk-button uk-button-primary uk-button-large" style="background-color: #3fa962" href="#signInForm"><span uk-icon="icon: social"></span>&nbsp;&nbsp;<b>Connect Now</b></a>
					</div>
					<div class="uk-width-3-3"></div>
				</div>
			</div>
		</section>
		<section>
			<div class="uk-section uk-section-muted">
				<div class="uk-container">
					<div class="uk-grid">
						<div class="uk-width-1-2" uk-grid>
							<div>
								<br><br><br><h3>dev-o-talk is a social network for developers</h3>
								<p>dev-o-talk allows you to connect to your developer friends, ask and answer queries related to part of your code, post links to new developments in technologies, create your private teams and chat.</p>
							</div>
						</div>
						<div class="uk-width-1-2">
							<img src="../assets/img/placeit.png" alt="Logo" class="services-image" width="500px">
						</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="signInForm">
			<div class="uk-section uk-section-muted">
				<div class="uk-container">
					<div class=" uk-child-width-1-2@s" uk-grid>
						<div>
							<img src="../assets/img/devNetwork.png" alt="Logo" class="services-image">
						</div>
						<div>
							<!-- This is the tabbed navigation containing the toggling elements -->
							<ul class="uk-tab" data-uk-tab="{connect:'#my-id'}">
							    <li><a href="#signInForm"><span uk-icon="icon: sign-in"></span>Sign In</a></li>
							    <li><a href="#registerForm"><span uk-icon="icon: file-edit"></span>Register</a></li>
							</ul>

							<!-- This is the container of the content items -->
							<ul id="my-id" class="uk-switcher uk-margin">
							    <li id="signInForm">
							    	<form class="uk-panel uk-panel-box uk-form" method="POST" action="index.php">
										<div class="uk-form-row">
											<input class="uk-width-1-1 uk-form-large" type="text" placeholder="Username" name="username">
										</div>
										<div class="uk-form-row">
											<input class="uk-width-1-1 uk-form-large" type="password" placeholder="Password" name="password">
										</div>
										<div class="uk-form-row">
											<input type="submit" name="btnLogin" class="uk-width-1-1 uk-button uk-button-primary uk-button-large" value="Login" />
										</div>
										<div class="uk-form-row uk-text-small">
											<!-- <label class="uk-float-left"><input type="checkbox"> Remember Me</label>
											<a class="uk-float-right uk-link uk-link-muted" href="#">Forgot Password?</a> -->
										</div>
									</form>
							    </li>
							    <li id="registerForm">
							    	<form class="uk-panel uk-panel-box uk-form" method="POST" action="index.php">
										<div class="uk-form-row">
											<input class="uk-width-1-1 uk-form-large" type="text" placeholder="Username" name="username" required>
										</div>
										<div class="uk-form-row">
											<input class="uk-width-1-1 uk-form-large" type="text" placeholder="Email" name="email" required>
										</div>
										<div class="uk-form-row">
											<input class="uk-width-1-1 uk-form-large" type="password" placeholder="Password" name="password" required>
										</div>
										<div class="uk-form-row">
											<input class="uk-width-1-1 uk-form-large" type="password" placeholder="Confirm Password" name="confirmPassword" required>
										</div>
										<div class="uk-form-row">
											<input type="submit" name="btnRegister" class="uk-width-1-1 uk-button uk-button-primary uk-button-large" value="Register" />
										</div>
									</form>
							    </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section>
			<div class="uk-section uk-light" style="background-color: #094A1F">
				<div class="uk-container">
					<div class="uk-column-1-3@m uk-text-center">
						<div class="uk-card uk-card-default uk-card-body">
							<h1 class="uk-card-title" style="font-size: 50px">Ask Queries</h1>
						</div>
						<div class="uk-card uk-card-default uk-card-body">
							<h1 class="uk-card-title" style="font-size: 50px">Code Alike</h1>
						</div>
						<div class="uk-card uk-card-default uk-card-body">
							<h1 class="uk-card-title" style="font-size: 50px">Team Up</h1>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section>
			<div class="uk-section uk-section-muted">
				<div class="uk-container uk-card uk-text-center">
					<div class=" uk-child-width-1-3@m" uk-grid>
						<div>
							<img src="../assets/icon/members.png" alt="Logo" width="150px">
							<h5 >100+</h5>
							<p>members</p>
						</div>
						<div>
							<img src="../assets/icon/code.png" alt="Logo" width="150px">
							<h5>1000+</h5>
							<p>code snippets</p>
						</div>
						<div>
							<img src="../assets/icon/team.png" alt="Logo" width="150px">
							<h5>20+</h5>
							<p>groups</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<footer style="position: relative; padding: 60px 0 0 0; background-color: #202020;">
			<div class="uk-container uk-container-center" style="box-sizing: border-box;">
				<div class="uk-grid uk-margin-bottom">
					<!-- Footer Logo -->
					<div class="uk-width-1-1 uk-text-center">
						<div class="">
							<p style="color: #0094E7; text-align: center; margin-left: 30px"><b><a href="index.php">{dev-o-talk}</a></b></p>
						</div>
					</div>
					<!-- Footer Logo End -->
					<!-- Footer Menu -->
					<div class="uk-width-1-1  uk-text-center" style="text-align: center;">
						<ul style=" list-style: none; color: white;">
							<li><a href="#">About</a></li>
							<li><a href="#">Contact</a></li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="bottom-line">
				<div uk-grid>
					<div class="uk-text-left uk-width-auto@m">
							<a href="#">Terms &amp; Condition</a>
					</div>
					<div class="uk-text-middle uk-width-auto@m" style="margin-left: 300px">
							<p>Made by your neighbour in Tirupati, India</p>
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