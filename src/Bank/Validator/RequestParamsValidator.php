<?php

    namespace Bank\Validator;

    use Inacho\CreditCard as CreditCardValidator;

    class RequestParamsValidator
    {
        private $app;
        private $response;
        private $isValid = true;
        private $ccType;    //credit card type
        private $validCurrencies = array("eur");

        public function __construct($app, $response)
        {
            $this->app = $app;
            $this->response = $response;
        }

        public function validatePaymentCreate()
        {
            $this->validateCcn();
            $this->validateCvv();
            $this->validateExp();
            $this->validateAmo();
            $this->validateCur();
            $this->validateMid();
            $this->validateTim();
            $this->validateTok();

            return $this;
        }

        public function validateTok()
        {
            $tok = $this->get("tok");
            if (empty($tok)){
                return $this->addError("tok is empty. Please provide a transaction token.");
            }

            if (!preg_match("#^[a-f0-9]{64}$#", $tok)){
                return $this->addError("tok format is not valid (64 hexadecimal characters)");
            }

            $merchantDb = new \Bank\Client\Database();
            $mid = $this->get("mid");
            $string =   $merchantDb->getMerchantSecret($mid).
                        $mid. 
                        $this->get("ccn"). 
                        $this->get("amo"). 
                        $this->get("tim");

            $token = hash("sha256", $string);

            if ($token !== $tok){
                return $this->addError("tok is not valid.");
            }

            return true;
        }

        public function validateTim()
        {
            $tim = $this->get("tim");
            if (empty($tim)){
                return $this->addError("tim is empty. Please provide a UNIX timestamp for your transaction.");
            }

            if (!preg_match("#^\d{10}$#", $tim)){
                return $this->addError("tim format is not valid (10 digits)");
            }

            $thirdySecondsAgo = strtotime("- 30 minutes");

            if ($tim < $thirdySecondsAgo){
                return $this->addError("tim must be max 30 minutes from now.");
            }
            elseif($tim > time()){
                return $this->addError("tim must be present or in the past.");
            }

            return true;
        }

        public function validateMid()
        {
            $mid = $this->get("mid");
            if (empty($mid)){
                return $this->addError("mid is empty. Please provide a merchant ID.");
            }

            if (!preg_match("#^[a-z0-9]{32}$#", $mid)){
                return $this->addError("mid format is not valid (32 lowercase characters)");
            }

            $merchantDb = new \Bank\Client\Database();
            $merchant = $merchantDb->getMerchant($mid);
            
            if (!$merchant){
                return $this->addError("merchant id not found");
            }

            return true;
        }

        public function validateCcn()
        {
            $ccn = $this->get("ccn");
            if (empty($ccn)){
                return $this->addError("ccn is empty. Please provide a credit card number.");
            }

            if (!preg_match("#^\d+$#", $ccn)){
                return $this->addError("ccn format is not valid (only digits)");
            }

            $validationResult = CreditCardValidator::validCreditCard($ccn);
            if (!empty($validationResult['type'])){
                return $this->ccType = $validationResult['type'];
            }
            if (!$validationResult["valid"]){
                return $this->addError("ccn is not valid");
            }

            return true;
        }

        public function validateCvv()
        {
            $cvv = $this->get("cvv");
            if (empty($cvv)){
                return $this->addError("cvv is empty. Please provide a little number on the back.");
            }

            if (!preg_match("#^\d+$#", $cvv)){
                return $this->addError("cvv format is not valid (only digits)");
            }

            //if the credit card type was not found previously, abort, no way to validate
            if (empty($this->ccType)){
                return false;
            }

            $validationResult = CreditCardValidator::validCvc($cvv, $this->ccType);
            if (!$validationResult){
                return $this->addError("cvv is not valid");
            }

            return true;
        }

        public function validateExp()
        {
            $exp = $this->get("exp");
            if(empty($exp)){
                return $this->addError("exp is empty. Please provide a credit card expiry date.");
            }

            if (!preg_match("#^\d{6}$#", $exp)){
                return $this->addError("exp format is not valid (mmyyyy format please)");
            }

            $month = substr($exp, 0, 2);
            $year = substr($exp, 2, 4);

            if (!CreditCardValidator::validDate($year, $month)){
                return $this->addError("Invalid expiry date");
            }

            return true;
        }

        public function validateAmo()
        {
            $amo = $this->get("amo");
            if(empty($amo)){
                return $this->addError("amo is empty. Please provide a transaction amount.");
            }

            if (!preg_match("#^\d+$#", $amo)){
                return $this->addError("amo format is not valid (in cents, so only digits please)");
            }

            return true;
        }

        public function validateCur()
        {
            $cur = $this->get("cur");
            if(empty($cur)){
                return $this->addError("cur is empty. Please provide a transaction currency.");
            }

            if (!preg_match("#^[a-z]{3}$#", $cur)){
                return $this->addError("cur format is not valid (3 lowercase letters)");
            }

            if (!in_array($cur, $this->validCurrencies)){
                return $this->addError("this currency is currently not supported");
            }

            return true;
        }

        public function isValid()
        {
            return $this->isValid;
        }

        private function addError($message)
        {
            $this->isValid = false;
            $this->response->addError($message);
            return false;
        }

        private function get($paramName)
        {
            return trim($this->app->request->get($paramName));
        }

    }