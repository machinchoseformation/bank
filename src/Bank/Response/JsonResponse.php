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
            $content = array(
                'status' => $this->status,
                'message' => $this->message,
                'data' => $this->data,
                'errors' => $this->errors,
            );

            $json_content = json_encode($content);
            return $json_content;
        }

        public function addError($error)
        {
            $this->errors[] = $error;
        }

        public function setMessage($message)
        {
            $this->message = $message;
        }
        
        public function setData($data)
        {
            $this->data = $data;
        }

        public function setStatus($status)
        {
            $this->status = $status;
        }

        public function getStatus()
        {
            return $this->status;
        }
    }