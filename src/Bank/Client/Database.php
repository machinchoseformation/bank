<?php
    
    namespace Bank\Client;

    class Database
    {

        private $merchants = array
        (
            array(
                "id" => "a",
                "secret" => "ab"
            ),
            array(
                "id" => "b",
                "secret" => "bc"
            ),
            array(
                "id" => "c",
                "secret" => "cd"
            ),
            array(
                "id" => "d",
                "secret" => "de"
            ),
        );

        public function getMerchant($id)
        {
            for($i=0,$count=count($this->merchants);$i<$count;$i++){
                if ($this->merchants[$i]['id'] == $id){
                    return $this->merchants[$i];
                }
            }

            return false;
        }

        public function getMerchantSecret($id)
        {
            for($i=0,$count=count($this->merchants);$i<$count;$i++){
                if ($this->merchants[$i]['id'] == $id){
                    return $this->merchants[$i]['secret'];
                }
            }

            return false;
        }

    }