<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:48 PM
 */

namespace Application\Controller;


use Model\Entity\ResponseBootstrap;
use Model\Service\StatisticService;
use Symfony\Component\HttpFoundation\Request;

class StatisticController
{

    private $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }


    public function getCountries(Request $request):ResponseBootstrap {
        // call service for data
        return $this->statisticService->getCountryStats();
    }


    public function getPeriods(Request $request):ResponseBootstrap {
        // get period
        $period = $request->get('period');

        // create response object
        $response = new ResponseBootstrap();

        // check if data is present
        if(isset($period)){
            return $this->statisticService->getPeriodStats($period);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    public function getTransactions(Request $request):ResponseBootstrap {
        return $this->statisticService->getTransactions();
    }


    public function getPurchase(Request $request):ResponseBootstrap {
        // get id
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        if(isset($id)){
            return $this->statisticService->getPurchase($id);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        return $response;
    }


    public function getQuantities(Request $request):ResponseBootstrap {
        return $this->statisticService->getQuantitiesSold();
    }


    public function postState(Request $request):ResponseBootstrap {
        // get data
        $data = json_decode($request->getContent(), true);

        $state = $data['state'];
        $id = $data['id'];

        // create response object
        $response = new ResponseBootstrap();

        if(isset($state) && isset($id)){
            return $this->statisticService->changeState($state, $id);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        return $response;
    }
}