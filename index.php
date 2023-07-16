<?php

    class Cost {

        private Rate $rate;
        private array $additional_services;
        private array $input;
        private $error = '';
        private $cost = 0;

        public function read_cost(){
            return $this->cost;
        }    

        public function getCost(){

            foreach( $this->rate->before_cond as $cond ){

                $result = $cond( $this->input );

                if( $result instanceof \ErrorException ){
                    $this->error = $result->getMessage();
                    break;
                }
                    
            }
            
            if( isset( $this->input['additionalServices'] ) && $this->input['additionalServices'] )
                foreach( $this->additional_services as $id => $service ){

                    if( !in_array( $id , $this->input['additionalServices'] ) )
                        continue;

                    $result = $service( $this->input );

                    if( $result instanceof \ErrorException ){
                        $this->error = $result->getMessage();
                        break;
                    }

                    $this->cost += $result;
                }

            if( ! $this->error) {

                if(isset($this->input['km'])) {
                    $this->cost += ($this->input['km'] * $this->rate->km);
                }

                if(isset($this->input['minutes'])) {
                    $this->cost += ($this->input['minutes'] / $this->rate->minutes_per_unit) * $this->rate->cost_time_unit ;
                }

            }

            return $this;
            
        }

        public function json_out(){

            if( $this->error )
                $out = [ 'error' => $this->error ];
            else
                $out = [ 'result' => $this->cost ];

            header('Content-type: application/json');
            echo json_encode( $out );

        }

        public function __construct( Rate $rate , array $input = [] , array $additional_services = [] ){

            $this->input = $input;
            $this->rate = $rate;
            $this->additional_services = $additional_services;
            

            foreach ($this->additional_services as &$closure) {
                $closure = \Closure::bind( $closure , $this , $this );
            }

        }

    }

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

        public function __construct( array $input ){

            foreach( $input as $k => $v ){
                if( !isset( $this->$k ) || gettype( $this->$k ) !== gettype( $v ) )
                    continue;

                $this->$k = $v;
            }

        }

    }

    include 'rates.php';
    include 'additional_services.php';

    $input = [
        'rate' => 'student' , 'km' => 2 , 'minutes' => 120 , 'driverAge' => 20 , 'additionalServices' => [ 'wi-fi'  ]
    ];

    $input = [
        'rate' => 'daily' , 'km' => 2 , 'minutes' => 1440 , 'driverAge' => 20 , 'additionalServices' => [ 'wi-fi' , 'driver' ]
    ];


    print_r( ( new Cost( new Rate( $rates[ $input['rate'] ] ) , $input , $additional_services ) )->getCost()->json_out() );

    //print_r( ( new Cost( new Rate( $rates['student'] ) , $additional_services ) )->getCost( $input )->json_out() );


    /*$example = [
        'rate' => [ 'base', 'daily', 'hourly', 'student' ] , 'km' => 0 , 'minutes' => 0 , 'driverAge' => 16 , 'additionalServices' => [ 'wi-fi' , 'driver' ]
    ];*/


?>