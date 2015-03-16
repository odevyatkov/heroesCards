<?php
include("./Canvas.php");
$fp = fopen("./units.txt", 'r');
$i = 0;
while (($s = fgets($fp)) !== false) {
    try {
        $row = explode("\t", $s);
        $unit = [
            'name' => $row[0],
            //'image' => $row[1],
            'image' => "./images/".$i.".jpg",
            'attack' => $row[2],
            'defense' => $row[3],
            'damage' => strtr($row[4], [" - "=>"-"]),
            'health' => $row[5],
            'speed' => $row[6],
            'shots' => $row[8],
            'descr' => trim($row[10]),
            'level' => $row[9],
            'growth' => $row[7], //!!!!!<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<,
        ];
        if ($unit['descr'] && substr($unit['descr'], -1) != '.') {
            $unit['descr'] .= '.';
        }
        $canvas = new Canvas();
        $canvas->addOverlay();
        $canvas->addMainImage($unit['image']);
        $canvas->addName($unit['name']);
        $canvas->addAttack('Атака', $unit['attack']);
        $canvas->addDefense('Защита', $unit['defense']);
        if ($unit['shots']) {
            $canvas->addShots('Выстрелы', $unit['shots']);
        }
        $canvas->addDamage('Урон', $unit['damage']);
        $canvas->addHealth('Здоровье', $unit['health']);
        $canvas->addSpeed('Скорость', $unit['speed']);
        $canvas->addDescr($unit['descr']);
        $canvas->getImage()->writeImage('./res/'.$i.".png");
    } catch (Exception $e) {
        print_r($e->getMessage());
        die();
        echo "ERROR: cant create row ".$i."\n";
    }
    echo $i."\r";
    $i++;
}
echo "all done\n";
