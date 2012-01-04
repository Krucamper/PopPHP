<?php

require_once '../../bootstrap.php';

use Pop\Color\Rgb,
    Pop\Graph\Graph;

try {

    $x = array('0M', '50M', '100M', '150M', '200M');
    $y = array('1995', '2000', '2005', '2010', '2015');

    //$data = array(5, 25, 100, 76, 175);
    $data = array(
        array(5, new Rgb(200, 15, 15)),
        array(25, new Rgb(80, 5, 10)),
        array(100, new Rgb(80, 180, 100)),
        array(76, new Rgb(50, 125, 210)),
        array(175, new Rgb(80, 180, 10))
    );

    //$graph = new Graph('graph.gif', 640, 480, Graph::IMAGICK);
    $graph = new Graph('graph.svg', 640, 480);
    $graph->addFont('../assets/fonts/times.ttf')
          ->setFontColor(new Rgb(128, 128, 128))
          ->setFillColor(new Rgb(10, 125, 210))
          ->showX(true)
          ->showText(true)
          ->addHBarGraph($data, $x, $y)
          ->output();

} catch (\Exception $e) {
    echo $e->getMessage();
}

?>