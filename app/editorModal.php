<?php include('DBAdmin.php') ?>

<!DOCTYPE html>
<html>
<head>
	<title>Editor</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../node_modules/uikit/dist/css/uikit.min.css">
	<script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
	<script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</head>
<body>
	<div id="editorModal" class="uk-modal-container" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body">
	        <!-- Modal content-->
      		<button class="uk-modal-close-default" type="button" uk-close></button>
  			<p>Multi-language Editor for you..</p>
  			<iframe src="editor.php" width="100%" height="400px"  frameborder="0"></iframe>
        	<div class="uk-text-right">
        		<?php 
        			echo '<a href="'.$_SESSION['editorFromPage'].'.php"><button class="uk-button uk-button-primary" style="background-color: #004D40" type="button"> <- Go </button></a>';
        		?>
        	</div>
	    </div>
	</div>
	<button id="edtModal" class="uk-button uk-button-secondary uk-align-left" style="background-color: #009688" uk-toggle="target: #editorModal">Editor</button>
	<script>
		document.getElementById('edtModal').click();
	</script>
</body>
</html>