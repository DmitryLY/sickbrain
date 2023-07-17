<?php

    use CarSharing\Classes\Cost;

    include 'vendor/autoload.php';

    $input = [
        'rate' => 'student' , 'km' => 2 , 'minutes' => 120 , 'driverAge' => 28 , 'additionalServices' => [ 'wi-fi' ]
    ];

    $input = [
        'rate' => 'daily' , 'km' => 2 , 'minutes' => 1480 , 'driverAge' => 20 , 'additionalServices' => [ 'wi-fi' , 'driver' ]
    ];

    ( new Cost( $input , null ) )->getCost()->jsonOut();



?>