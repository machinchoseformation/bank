<?php

    namespace Bank\Response;

    class JsonResponse
    {

        private $message;
        private $data;
        private $errors = array();
        private $status;

        public function send()
        {
            $json_content = $this->prepare();
            header('Content-Type: application/json');
            echo $json_content;
            die();
        }

        private function prepare()
        {
            $content = array();
            $content['errors'] = $this->errors;
            $content['data'] = $this->data;
            $content['message'] = $this->message;

            $json_content = json_encode($content);
            return $json_content;
        }

        public function addError($error)
        {
            $this->errors[] = $error;
        }

        public function setMessage($message){
            $this->message = $message;
        }
        
        public function setData($data){
            $this->data = $data;
        }
    }