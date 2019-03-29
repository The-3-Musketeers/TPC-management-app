<?php
  $zip = new ZipArchive();
  $filename = "./resumes.zip";
  if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
  }
  $dir = 'resume/';
  createZip($zip,$dir);
  $zip->close();

  function createZip($zip,$dir){
    if (is_dir($dir)){
      if ($dh = opendir($dir)){
         while (($file = readdir($dh)) !== false){
           if (is_file($dir.$file)) {
              if($file != '' && $file != '.' && $file != '..'){
                 $zip->addFile($dir.$file);
              }
           }else{
              if(is_dir($dir.$file) ){
                if($file != '' && $file != '.' && $file != '..'){
                  $zip->addEmptyDir($dir.$file);
                  $folder = $dir.$file.'/';
                  createZip($zip,$folder);
                }
              }
           }
         }
         closedir($dh);
       }
    }
  }
  $filename = "resumes.zip";

  if (file_exists($filename)) {
     header('Content-Type: application/zip');
     header('Content-Disposition: attachment; filename="'.basename($filename).'"');
     header('Content-Length: ' . filesize($filename));
     flush();
     readfile($filename);
     unlink($filename);

   }
?>
