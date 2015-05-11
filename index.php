<?php

    require("vendor/autoload.php");

    use \Bank\Validator\RequestParamsValidator;
    use \Bank\Response\JsonResponse;
    use \Bank\Util\StringUtil;

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
            $response->setData(
                $app->request->get()
            );

            //for testing purpose
            if ($app->request->get("tes") == "rejected"){
                $response->setStatus("payment_rejected");
                $response->setMessage("Payment rejected by the bank");
            }
            else {
                $response->setStatus("payment_ok");
                $response->setMessage("Payment created");
            }
        }

        $response->send();
    });


    /**
     * Home and documentation
     */
    $app->get("/", function() use($app)
    {
        //for demo query purpose...
        $params = array(
            "mid" => "abcd2345abcd2345abcd2345abcd2345",
            "ccn" => "4485491159053724",
            "amo" => "99",
            "tim" => time()
        );
        $params["tok"] = StringUtil::getTok($params['mid'], $params['ccn'], $params['amo'], $params['tim']);

        $app->render("doc.php", $params);
    });


    /**
     * Generate a new merchant database 
     */    
    $app->get("/merchant/generate/:num", function($num){
        $merchants = array("merchants" => array());
        for($i=0;$i<$num;$i++){
            $m = array();
            $m["mid"] = StringUtil::randomString(32);
            $m["secret"] = StringUtil::randomString(128);

            $merchants["merchants"][] = $m;
        }

        //demo merchant
        $m = array(
            "mid" => "abcd2345abcd2345abcd2345abcd2345",
            "secret" => "pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876pipo9876"
        );
        $merchants["merchants"][] = $m;
        
        $json = json_encode($merchants, JSON_PRETTY_PRINT);
        file_put_contents("merchants.json", $json);
    });
    

    $app->run();