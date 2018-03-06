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
<body onload="UIkit.modal('editorModal').show();">
	<div id="editorModal" class="uk-modal-container" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body">
	        <!-- Modal content-->
      		<button class="uk-modal-close-default" type="button" uk-close></button>
  			<p>Multi-language Editor for you..</p>
  			<iframe src="editor.php" width="100%" height="400px"  frameborder="0"></iframe>
        	<div class="uk-text-right">
        		<?php displayMessage($_SESSION['editorFromPage']); ?>
        		<?php 
        			echo '<a href="'.$_SESSION['editorFromPage'].'.php"><button class="uk-button uk-button-primary" type="button"> <- Go </button></a>';
        		?>
        	</div>
	    </div>
	</div>
	<button class="uk-button uk-button-secondary uk-align-left" uk-toggle="target: #editorModal">Editor</button>
	<script>
		document.getElementById('editorModal').show();
	</script>
</body>
</html>