<?php
$fileName = "./res/index.html";
file_put_contents($fileName, '<html>');
file_put_contents($fileName, '<body>', FILE_APPEND|LOCK_EX);
$files = scandir('./res/');
foreach ($files as $file) {
    if (strpos($file, ".png")!==false) {
        file_put_contents($fileName, '<img src="./'.$file.'">', FILE_APPEND|LOCK_EX);
    }
}
file_put_contents($fileName, '</body>', FILE_APPEND|LOCK_EX);
file_put_contents($fileName, '</html>', FILE_APPEND|LOCK_EX);
//file_put_contents($fileName, '<body>', FILE_APPEND|LOCK_EX);
