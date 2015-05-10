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

        $validator->validateCcn();
        $validator->validateCvv();
        $validator->validateExp();
        $validator->validateAmo();
        $validator->validateCur();

        //request seems valid...
        if ($validator->isValid()){
            //simulates a payment
            //alter response
        }

        $response->send();
    });

    $app->run();