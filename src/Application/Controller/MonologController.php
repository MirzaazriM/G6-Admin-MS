<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:54 PM
 */

namespace Application\Controller;

use Model\Entity\ResponseBootstrap;
use Model\Service\MonologService;
use Symfony\Component\HttpFoundation\Request;

class MonologController
{

    private $monologService;

    public function __construct(MonologService $monologService)
    {
        $this->monologService = $monologService;
    }


    /**
     * Get logs for specified MS
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLogs(Request $request):ResponseBootstrap {
        // get type
        $type = $request->get('type');

        // create response object
        $response = new ResponseBootstrap();

        // set allowed types
        $types = ['products', 'supplements', 'tags', 'admin', 'mobile'];

        // check if right data is set
        if(in_array($type, $types)){
            return $this->monologService->getLogs($type);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Delete log
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteLog(Request $request): ResponseBootstrap {
        // get date and type
        $date = $request->get('date');
        $type = $request->get('type');

        // create response object
        $response = new ResponseBootstrap();

        // set allowed types
        $types = ['products', 'supplements', 'tags', 'admin', 'mobile'];

        // check if date is set
        if(isset($date) && in_array($type, $types)){
            return $this->monologService->deleteLog($type, $date);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }
}