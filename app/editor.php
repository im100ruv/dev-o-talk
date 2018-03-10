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
//write your code here..
</pre> 

  <form method="POST" action="editor.php">
    <input type="submit" id="btnEditorSave" name="btnEditorSave" style="position: absolute; top: 382px; left: 50px;" value="Save" onclick="sendCode();">
  </form>

  <select id="codeMode" style="position: absolute; top: 382px; left: 200px;" onchange="changeMode();">
    <option value="actionscript">actionscript</option>
    <option value="ada">ada</option>
    <option value="c_cpp">c_cpp</option>
    <option value="cobol">cobol</option>
    <option value="csharp">csharp</option>
    <option value="css">css</option>
    <option value="django">django</option>
    <option value="erlang">erlang</option>
    <option value="fortran">fortran</option>
    <option value="groovy">groovy</option>
    <option value="html_ruby">html_ruby</option>
    <option value="html">html</option>
    <option value="java">java</option>
    <option value="javascript">javascript</option>
    <option value="json">json</option>
    <option value="jsp">jsp</option>
    <option value="kotlin">kotlin</option>
    <option value="less">less</option>
    <option value="lisp">lisp</option>
    <option value="markdown">markdown</option>
    <option value="matlab">matlab</option>
    <option value="mysql">mysql</option>
    <option value="objectivec">objectivec</option>
    <option value="pascal">pascal</option>
    <option value="perl">perl</option>
    <option value="php">php</option>
    <option value="powershell">powershell</option>
    <option value="prolog">prolog</option>
    <option value="python">python</option>
    <option value="r">r</option>
    <option value="ruby">ruby</option>
    <option value="sass">sass</option>
    <option value="scala">scala</option>
    <option value="sh">sh</option>
    <option value="sql">sql</option>
    <option value="swift">swift</option>
    <option value="text">text</option>
    <option value="typescript">typescript</option>
    <option value="xml">xml</option>
  </select>

  <select id="codeTheme" style="position: absolute; top: 382px; left: 400px;" onchange="changeTheme();">
    <option value="ambiance">ambiance</option>
    <option value="chrome">chrome</option>
    <option value="clouds_midnight">clouds_midnight</option>
    <option value="clouds">clouds</option>
    <option value="cobalt">cobalt</option>
    <option value="dawn">dawn</option>
    <option value="dreamweaver">dreamweaver</option>
    <option value="eclipse">eclipse</option>
    <option value="github">github</option>
    <option value="monokai">monokai</option>
    <option value="terminal">terminal</option>
    <option value="tomorrow">tomorrow</option>
    <option value="twilight">twilight</option>
    <option value="xcode">xcode</option>
  </select>

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

    function changeMode() {
      var mod = document.getElementById('codeMode').value;
      console.log(mod);
      editor.session.setMode("ace/mode/" + mod);
    }

    function changeTheme() {
      var mod = document.getElementById('codeTheme').value;
      console.log(mod);
      editor.setTheme("ace/theme/" + mod);
    }
    
</script>

</body>
</html>
