<?php
    class ThemeEditor extends Plugin {

 


        public function adminView(){
            global $security;
            $tokenCSRF = $security->getTokenCSRF();

       global $site;
       $root = PATH_THEMES;
         $dir  = $root.$site->theme();

         echo '
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css"/>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/blackboard.min.css"/>
         <style>
         .CodeMirror {
          height: 65vh;
          margin-top:20px;
      }



         </style>
         
         ';
         
$formget = '
       <h3>ThemeEditor</h3>
       <div class="bg-primary text-light mb-2 border p-3 lead">You edit <b>'.$site->theme().'</b> theme files. If you want edit different, activate another theme.</div>
<form method="get" >
         <div  class="bg-light d-flex border p-3">
      
         ';

         echo $formget;

         echo '<select name="edited" class="edited form-control" style="width:90%;display:inline-block;padding:5px;box-sizing:border-box;">';
         
foreach (glob($dir.'/{,*/,*/*/,*/*/*/}*{.php,.js,.css}',GLOB_BRACE) as $files) {
    
    $newfiles = str_replace('/','\  ',$files);
    $newerfiles = str_replace(' ','',$newfiles);
     
 $basename = str_replace($root.$site->theme(),"",$newerfiles);
  

    echo '<option value="'.$newerfiles.'" '.($_GET['edited']===$newerfiles ? "selected":"").'>'.$basename.'</option>';

    

}

echo '</select>
<input type="submit" style="width:10%;" name="edit" value="Edit File"  class="btn btn-primary d-inline-block edit">
</div>
</form>';



$formsubmit = '<form method="post" >
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="'.$tokenCSRF.'">

<textarea name="editors" id="editors" style="width:100%;height:80vh;">'.@file_get_contents($_GET['edited']).'</textarea>
<input name="dir" type="hidden" value="'.$_GET['edited'].'"><br>
<div class="bg-light border p-3">
<input type="submit" name="saveedit" value="Save changes" class="btn btn-primary ">
</div>
</form>';

echo $formsubmit;

 

if(isset($_POST['saveedit'])){
file_put_contents($_POST['dir'],$_POST['editors']);
echo "<meta http-equiv='refresh' content='0'>";
}

 
$script ='<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js"></script>
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("editors"), {
    styleActiveLine: true,
    lineNumbers: true,
    matchBrackets: true,
    theme:"blackboard",
    mode: "text/x-scss",
  
   
   
});
</script>
';

echo $script;



    }


    public function adminSidebar()
    {
        $pluginName = Text::lowercase(__CLASS__);
        $url = HTML_PATH_ADMIN_ROOT.'plugin/'.$pluginName;
        $html = '<a id="current-version" class="nav-link" href="'.$url.'">Theme Editor</a>';
        return $html;
    }

    }
?>