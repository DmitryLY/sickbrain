<?php

$additional_services = [ 

    'wi-fi' => function( array $input ){
        if( $this->rate->rate == 'base' || $input['minutes'] < 120 )
            return new \ErrorException('Дополнительная опция не доступна');
        
        $rate_input = [ 'cost_time_unit' => 15 , 'minutes_per_unit' => 60 ];
        $cost_input = [ 'minutes' => $input['minutes'] ];

        return ( new Cost( new Rate( $rate_input ) , $cost_input ) )->getCost()->read_cost();

    }, 
    'driver' => function( array $input ){
        if(  $this->rate->rate == 'student' )
            return new \ErrorException('Дополнительная опция не доступна');

        return 100;
    }

];