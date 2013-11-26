<?php
$make_dir_en = "files/123/12";
if ( !file_exists($make_dir_en) ) {
							
mkdir($make_dir_en);
echo "Directory Created";
}
else echo "Already Exists";
?>