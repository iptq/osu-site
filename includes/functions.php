<?php

$APIKEY = "c5b021c1a351654df7873b05dcdf474f90f0c08d";

function calc_acc($c300, $c100, $c50, $c0) {
    $points = 6 * $c300 + 2 * $c100 + $c50;
    $total = 6 * ($c300 + $c100 + $c50 + $c0);
    return round($points / $total, 4);
}

?>