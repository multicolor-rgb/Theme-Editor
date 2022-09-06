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
         <h3>ThemeEditor</h3>         
         <br>
         ';
         


         if(isset($_GET["themename"])){
          echo '
            <div class="bg-primary text-light mb-2 border p-3 lead">You edit <b>'.$_GET["themename"].'</b> theme files . </div>';
         }

         
echo '
    
<form method="get">
         <div  class="bg-light d-block border p-3">
      
         ';
 



         echo '
         <p class="mb-2">Choose theme</p>

         <select name="themename" class="form-control d-block themename my-2">';

         
        foreach (glob(PATH_THEMES.'/*',GLOB_BRACE) as $folder) {
    
         $basenames = str_replace(PATH_THEMES,"",$folder);
         $newbasenames = str_replace('/','',$basenames);
         
            echo '<option value="'.$newbasenames.'" >'.$newbasenames.'</option>';
         
        };

         echo '</select>';

         if(isset($_GET['themename'])){


         echo '
         <p class="mb-2">Choose file</p>
         <select name="edited" class="edited form-control mb-2 " style="width:100%;display:inline-block;padding:5px;box-sizing:border-box;">';
         
foreach (glob(PATH_THEMES.@$_GET["themename"].'/{,*/,*/*/,*/*/*/}*{.php,.js,.css}',GLOB_BRACE) as $files) {
    
    $newfiles = str_replace('/','\  ',$files);
    $newerfiles = str_replace(' ','',$newfiles);
     
 $basenames = str_replace(PATH_THEMES.@$_GET["themename"],"",$files);
 
    echo '<option value="'.$basenames.'" >'.$basenames.'</option>';
 
}



         }


         echo '</select>
<input type="submit" name="edit" value="Edit File 📄"  class="btn btn-primary d-inline-block edit mt-2 col-md-2 ">
</div>
</form>';


echo '

<script>
const edited = "'.@$_GET["edited"].'";
const themename = "'.@$_GET["themename"].'";
document.querySelector(".edited").value = edited;
document.querySelector(".themename").value = themename;

</script>


';


 $branddir = PATH_THEMES.@$_GET["themename"].@$_GET['edited'];
$brand = str_replace('/','\  ',$branddir);
$brander = str_replace(' ','',$brand);
 


$formsubmit = '<form method="post" >
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="'.$tokenCSRF.'">

<textarea name="editors" id="editors" style="width:100%;height:80vh;">'.@file_get_contents(PATH_THEMES.@$_GET["themename"].@$_GET["edited"]).'</textarea>
<input name="dir" type="hidden" value="'.$brander.'"><br>
<div class="bg-light border p-3">
<input type="submit" name="saveedit" value="Save changes 💾" class="btn btn-primary col-md-2">
</div>
</form>';

echo $formsubmit;

 

if(isset($_POST['saveedit'])){
file_put_contents(PATH_THEMES.@$_GET["themename"].@$_GET["edited"],$_POST['editors']);
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


	
<div class="text-light bg-dark" style="padding:20px;text-align:center;box-sizing:border-box;margin-top:20px;" id="paypal">

<p>If you want support my work, and you want to see new plugins:) </p>

<a href="https://www.paypal.com/donate/?hosted_button_id=TW6PXVCTM5A72">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"  />
</a>


</div>
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