<?php
	//Form to accept or deny access
?>
<html>
	<head>
		<title>Authorize Access</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		
	</head>
	<body>
		<form method="POST" class="form" action="<?=$authorizePath?>">
		  <label>Do you authorize <?=$clientId?> to access your information on your behalf?</label><br />
		  <input type="submit" class="btn btn-success" name="authorized" value="yes">
		  <input type="submit" class="btn btn-default" name="authorized" value="no">
		</form>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>