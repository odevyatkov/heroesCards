<?php
include("./Canvas.php");
$fp = fopen("./units.txt", 'r');
$i = 0;
while (($s = fgets($fp)) !== false) {
    try {
        $row = explode("\t", $s);
        $url = $row[1];
        file_put_contents(
            "./images/".$i.".jpg",
            file_get_contents($url)
        );

        if ($i % 10 == 0) {
            print_r("\n");
            sleep(2);
        }
    } catch (Exception $e) {
        echo "\nERROR: cant create row ".$i."\n";
    }
    echo $i."\r";
    $i++;
}
echo "all done\n";
