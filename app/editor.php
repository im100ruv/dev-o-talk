<?php include('DBAdmin.php') ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Editor</title>
  <style type="text/css" media="screen">
    body {
        overflow: hidden;
    }

    #editor {
        margin: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
  </style>
</head>
<body>

<pre id="editor" name="preEditor" style="width: 100%; height: 380px">
function foo(items) {
  var i;
  for (i = 0; i &lt; items.length; i++) {
      alert("Ace Rocks " + items[i]);
  }
}
</pre> 

  <form method="POST" action="editor.php">
    <input type="submit" id="btnEditorSave" name="btnEditorSave" style="position: absolute; top: 382px; left: 50px;" value="Save" onclick="sendCode();">
 </form>
 <!-- <code style="position: absolute; top: 382px; left: 150px;"><?php //getStampTempEditor(); ?></code> -->

<script src="src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.session.setMode("ace/mode/javascript");
    editor.resize();

    function sendCode() {
      var editor_value = editor.getValue();
      document.getElementById('btnEditorSave').value = editor_value;
    }
    
</script>

</body>
</html>
