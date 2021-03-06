<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Refund;
use Payconn\Ipara\Response\RefundResponse;
use Payconn\Ipara\Token;

class RefundRequest extends IparaRequest
{
    public function send(): ResponseInterface
    {
        /** @var Refund $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();
        $hash = $token->getPrivateKey().$model->getOrderId().$this->getIpAddress().$model->getTransactionDate();

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $headers = [
            'version' => '1.0',
            'transactionDate' => $model->getTransactionDate(),
            'token' => $token->getPublicKey().':'.base64_encode(sha1($hash, true)),
        ];
        // inquiry refundHash
        $response = $httpClient->request('POST', $model->getBaseUrl().'/corporate/payment/refund/inquiry', [
            \GuzzleHttp\RequestOptions::JSON => [
                'clientIp' => $this->getIpAddress(),
                'orderId' => $model->getOrderId(),
                'amount' => $this->getAmount(),
            ],
            'headers' => $headers,
        ]);
        $refundHashResponse = @json_decode($response->getBody()->getContents(), true);
        if (!isset($refundHashResponse['result']) || 0 == $refundHashResponse['result']) {
            return new RefundResponse($model, $refundHashResponse);
        }
        $response = $httpClient->request('POST', $model->getBaseUrl().'/corporate/payment/refund', [
            \GuzzleHttp\RequestOptions::JSON => [
                'clientIp' => $this->getIpAddress(),
                'orderId' => $model->getOrderId(),
                'amount' => $this->getAmount(),
                'refundHash' => $refundHashResponse['refundHash'],
            ],
            'headers' => $headers,
        ]);

        return new RefundResponse($model, @json_decode($response->getBody()->getContents(), true));
    }
}
