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

    $app->run();