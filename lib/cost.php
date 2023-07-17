<?php

    namespace CarSharing\Classes;

    class Cost {

        private Rate $rate;
        private array $additional_services = [];
        private array $input = [];
        private $error = '';
        private $cost = 0;

        public function readCost(){
            return $this->cost;
        }    

        private function beforeCondition(){

            foreach( $this->rate->before_cond as $cond ){
                $result = $cond( $this->input );
            }

        }

        private function costAdditionalServices(){

            if( !isset( $this->input['additionalServices'] ) || !$this->input['additionalServices'] )
                return;


            foreach( $this->additional_services as $id => $service ){

                if( !in_array( $id , $this->input['additionalServices'] ) )
                    continue;

                $result = $service( $this->input );

                if( $result instanceof \ErrorException ){
                    throw $result;
                }

                $this->cost += $result;
            }

        }

        public function getCost(){

            try {

                $this->beforeCondition();
                $this->costAdditionalServices();

            }catch( \ErrorException $error ){
                $this->error = $error->getMessage();
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

        public function jsonOut(){

            if( $this->error )
                $out = [ 'error' => $this->error ];
            else
                $out = [ 'result' => ceil( $this->cost ) ];

            header('Content-type: application/json;');
            echo json_encode( $out , JSON_UNESCAPED_UNICODE  );

        }

        public function __construct( array $input = [] , Rate $rate = null ){

            if( !$rate && isset( $input['rate'] ) && $input['rate'] )
                try {
                    $rate = new Rate($input['rate'], null);
                }catch( \ErrorException $e){
                    $this->error = $e->getMessage();
                    return;
                }

            $this->input = $input;
            $this->rate = $rate;
            $this->additional_services = include( __DIR__ . '/../src/additional_services.php' );

            foreach ($this->additional_services as &$closure) {
                $closure = \Closure::bind( $closure , $this );
            }

        }

    }
