<?php

    namespace CarSharing\Classes;

    class Rate {

        private $rate = '';
        private $km = 0; // стоимость километра
        private $cost_time_unit = 0; // стоимость временной единицы
        private $minutes_per_unit = 0; // количество минут во временной единице
        private $before_cond = []; // функции для проверки соответствия тарифа входным данным

        public function __get( $name ){
            if( isset( $this->$name ) )
                return $this->$name;
        }        

        public function __construct( string $rate_name = null , array $rate = null  ){

            $rate = $rate ? $rate : ( $rate_name ? @( include ( __DIR__ . '/../src/rates.php' ) )[ $rate_name ] : null );

            if( !$rate )
                throw new \ErrorException('Тариф не выбран');

            foreach( $rate as $k => $v ){
                if( !isset( $this->$k ) || gettype( $this->$k ) !== gettype( $v ) )
                    continue;

                $this->$k = $v;
            }

        }

    }
