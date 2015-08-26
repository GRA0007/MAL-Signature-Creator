<?php
if (empty($_COOKIE['MALSIG_LOGIN'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<title>MyAnimeList Signature Creator</title>
		<meta name="description" content="A dynamic html-based signature creator for MyAnimeList." />
		<meta name="keywords" content="html,signature,MAL,myanimelist,anime,manga,dynamic,account,watched,complete,list,png,jpg,jpeg,image,picture" />
		<link rel="stylesheet" href="/experiments/MAL_sig/res/main.css" />
	</head>
	<body>
		<div id="content">
			<h1><a href="/experiments/MAL_sig/">MyAnimeList Signature Creator</a></h1>
			<?php
			if (empty($_GET['page'])) {
			?>
			<table id="front-table">
				<tr>
					<td valign="top" width="50%" style="border-right:5px solid #FFF;">
						<div id="changelog">
							<h2>Changelog</h2>
							<ul>
								<li>[0.2&#945;] Updated UI and colours.</li>
								<li>[0.1&#945;] Created login and signup pages, and a basic signature editor.</li>
							</ul>
						</div>
					</td>
					<td>
						<div id="column2">
							<a class="button" href="/experiments/MAL_sig/login/">Login</a>
							<a class="button" href="/experiments/MAL_sig/signup/">Signup</a>
						</div>
					</td>
				</tr>
			</table>
			<?php
			} else if ($_GET['page'] == 'login') {
			?>
			<form method="POST" action="/experiments/MAL_sig/php/login.php" id="login">
				<h2>Login</h2>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" />
				<label for="password">Password</label>
				<input type="password" name="password" id="password" />
				<center>
					<button type="submit">Login</button>
					<span id="signup-link">Don't have an account? <a href="/experiments/MAL_sig/signup/">Click here to signup!</a></span>
				</center>
			</form>
			<?php
			} else if ($_GET['page'] == 'signup') {
			?>
			<form method="POST" action="/experiments/MAL_sig/php/signup.php" id="signup">
				<h2>Signup</h2>
				<label for="username">MyAnimeList Username</label>
				<input type="text" name="username" id="username" />
				<label for="password">Password (Not your MAL password)</label>
				<input type="password" name="password" id="password" />
				<center>
					<button type="submit">Signup</button>
					<span id="login-link">Already have an account? <a href="/experiments/MAL_sig/login/">Click here to login!</a></span>
				</center>
			</form>
			<?php
			} else {
			?>
			<span style="text-align:center; display:block; line-height:250px;">An error occured. (001)</span>
			<?php
			}
			?>
		</div>
		<a href="http://myanimelist.net/animelist/Benpai" id="footer">Benpai&#x1F4CE;</a>
	</body>
</html>
<?php
} else {
	header("Location: ./editor/");
	die('You are already logged in. Logout to view this page.');
}
?>