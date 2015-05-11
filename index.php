<?php

    require("vendor/autoload.php");

    use \Bank\Validator\RequestParamsValidator;
    use \Bank\Response\JsonResponse;

    $app = new Slim\Slim();

    /**
     * Creates a new payment
     */
    $app->get("/payment/create", function() use ($app)
    {
        $response = new JsonResponse();
        $validator = new RequestParamsValidator($app, $response);

        $validator->validatePaymentCreate();

        //request seems valid...
        if ($validator->isValid()){
            //simulates a payment
            sleep(1);
            //alter response
            $response->setMessage("Payment created");
            $response->setData(
                $app->request->get()
            );
        }

        $response->send();
    });

    /**
     * Generate a new merchant database 
     */
    $app->get("/merchant/generate/:num", function($num){
        $merchants = array("merchants" => array());
        for($i=0;$i<$num;$i++){
            $m = array();
            $m["mid"] = \Bank\Util\StringUtil::randomString(32);
            $m["secret"] = \Bank\Util\StringUtil::randomString(128);

            $merchants["merchants"][] = $m;
        }
        $json = json_encode($merchants, JSON_PRETTY_PRINT);
        file_put_contents("merchants.json", $json);
    });

    $app->run();