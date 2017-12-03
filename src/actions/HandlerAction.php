<?php

namespace dmalchenko\unitpay\actions;

use yii\base\Actison;
use dmalchenko\unitpay\models\UnitPay;
use InvalidArgumentException;

class HandlerAction extends Action {

    public $secretKey = null;

    public $validateRequestFunction = null;
    public $checkFunction = null;
    public $payFunction = null;
    public $errorFunction = null;

    public function run() {
        $unitPay = new UnitPay($this->secretKey);
        try {
            $unitPay->checkHandlerRequest();

            list($method, $params) = [
                \Yii::$app->request->get('method'),
                \Yii::$app->request->get('params'),
            ];

            if (!is_callable($this->validateRequestFunction)) {
                throw new InvalidArgumentException('validateRequestFunction is not callable');
            }

            if (call_user_func($this->validateRequestFunction, $params)) {
                throw new InvalidArgumentException('Order validation Error!');
            }

            switch ($method) {
                case 'check':
                    $function = $this->checkFunction;
                    $message = 'Check Success. Ready to pay.';
                    break;
                case 'pay':
                    $function = $this->payFunction;
                    $message = 'Pay Success';
                    break;
                case 'error':
                    $function = $this->errorFunction;
                    $message = 'Error logged';
                    break;
                default:
                    throw new InvalidArgumentException("Method $method in not supported");
                    break;
            }
            if (!is_callable($function)) {
                throw new InvalidArgumentException("{$method}Function is not callable");
            }

            if (call_user_func($function, $params)) {
                echo $unitPay->getSuccessHandlerResponse($message);
            } else {
                throw new InvalidArgumentException("{$method}Function returned false");
            }
        } catch (\Exception $e) {
            echo $unitPay->getErrorHandlerResponse($e->getMessage());
        } finally {
            exit;
        }
    }
}