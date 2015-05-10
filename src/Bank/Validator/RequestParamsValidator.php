<?php

    namespace Bank\Validator;

    use Inacho\CreditCard as CreditCardValidator;

    class RequestParamsValidator
    {
        private $app;
        private $response;
        private $isValid = true;
        private $ccType;
        private $validCurrencies = array("eur");

        public function __construct($app, $response)
        {
            $this->app = $app;
            $this->response = $response;
        }

        public function isValid()
        {
            return $this->isValid;
        }

        public function validateCcn()
        {
            $ccn = $this->get("ccn");
            if (empty($ccn)){
                return $this->addError("ccn is empty");
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
                return $this->addError("cvv is empty");
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
                return $this->addError("exp is empty");
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
                return $this->addError("amo is empty");
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
                return $this->addError("cur is empty");
            }

            if (!in_array($cur, $this->validCurrencies)){
                return $this->addError("this currency is currently not supported");
            }

            return true;
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