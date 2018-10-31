<?php
namespace Model\Core\Helper\Monolog;

use Component\DataMapper;

class MonologSender
{

    /**
     * Send monolog log to the Monolog MS
     *
     * @param $configuration
     * @param $code
     * @param $record
     */
    public function sendMonologRecord($configuration, $code, $record) {
        // check code and set type
        if((int)$code >= 1000 && (int)$code <= 1749){
            $type = 'ERROR';
        }else {
            $type = 'WARNING';
        }

        // create guzzle client and send it data
        $client = new \GuzzleHttp\Client();
        $client->post($configuration['monolog_url'] . '/monolog/add',
            [
                \GuzzleHttp\RequestOptions::JSON => [
                    'microservice' => 'TAGS',
                    'type' => $type,
                    'record' => $record
                ]
            ]);
    }
}