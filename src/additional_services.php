<?php

    use CarSharing\Classes\Cost;
    use CarSharing\Classes\Rate;

    return [ 

        'wi-fi' => function( array $input ){
            if( $this->rate->rate == 'base' || $input['minutes'] < 120 )
                return new \ErrorException('Дополнительная опция не доступна');
            
            $rate_input = [ 'cost_time_unit' => 15 , 'minutes_per_unit' => 60 ];
            $cost_input = [ 'minutes' => $input['minutes'] ];

            return ( new Cost( $cost_input , new Rate( null , $rate_input ) ) )->getCost()->readCost();

        }, 
        'driver' => function( array $input ){
            if(  $this->rate->rate == 'student' )
                return new \ErrorException('Дополнительная опция не доступна');

            return 100;
        }

    ];