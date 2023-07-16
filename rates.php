<?php

$rates = [
        'base' =>[
            'rate' => 'base',
            'km' => 10,
            'cost_time_unit' => 3,
            'minutes_per_unit' => 1,
        ],
        'daily' =>[
            'rate' => 'daily',
            'cost_time_unit' => 1000,
            'minutes_per_unit' => 60*24,
            'before_cond' => [
                function( array $input ){
                    if( $input['minutes'] < 60*24 )
                        return new \ErrorException('Тариф не доступен');
                }
            ]
        ],
        'hourly' =>[
            'rate' => 'hourly',
            'cost_time_unit' => 200,
            'minutes_per_unit' => 60,
            'before_cond' => [
                function( array $input ){
                    if( $input['minutes'] > 60 )
                        return new \ErrorException('Тариф не доступен');
                }
            ]
        ],
        'student' =>[
            'rate' => 'student',
            'km' => 4,
            'cost_time_unit' => 1,
            'minutes_per_unit' => 1,
            'before_cond' => [
                function( array $input ){
                    if( $input['driverAge'] > 25 )
                        return new \ErrorException('Тариф не доступен');
                }
            ]
        ],
    ];

