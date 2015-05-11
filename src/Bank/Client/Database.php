<?php
    
    namespace Bank\Client;

    class Database
    {

        private $merchants = array();

        public function __construct()
        {
            $dbContent = file_get_contents("data/merchants.json");
            $array = json_decode($dbContent, true);
            $this->merchants = $array["merchants"];
        }

        public function getMerchant($mid)
        {
            for($i=0,$count=count($this->merchants);$i<$count;$i++){
                if ($this->merchants[$i]['mid'] == $mid){
                    return $this->merchants[$i];
                }
            }

            return false;
        }

        public function getMerchantSecret($mid)
        {
            for($i=0,$count=count($this->merchants);$i<$count;$i++){
                if ($this->merchants[$i]['mid'] == $mid){
                    return $this->merchants[$i]['secret'];
                }
            }

            return false;
        }

    }