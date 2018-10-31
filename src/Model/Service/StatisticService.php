<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:49 PM
 */

namespace Model\Service;


use Model\Entity\ResponseBootstrap;
use Model\Mapper\StatisticMapper;

class StatisticService
{

    private $statisticMapper;

    public function __construct(StatisticMapper $statisticMapper)
    {
        $this->statisticMapper = $statisticMapper;
    }


    public function getCountryStats():ResponseBootstrap {
        // create response object
        $response = new ResponseBootstrap();

        // call mapper to retrieve data from database
        $data = $this->statisticMapper->getByCountries();

        if(!empty($data)){
           $response->setStatus(200);
           $response->setMessage('Success');
           $response->setData($data);
        }else {
            $response->setStatus(204);
            $response->setMessage('No content');
        }

        // return $response
        return $response;
    }


    public function getPeriodStats(int $period):ResponseBootstrap {
        // create response object
        $response = new ResponseBootstrap();

        // call mapper to retrieve data from database
        $data = $this->statisticMapper->getByPeriod($period);

        if(!empty($data)){
            $response->setStatus(200);
            $response->setMessage('Success');
            $response->setData($data);
        }else {
            $response->setStatus(204);
            $response->setMessage('No content');
        }

        // return $response
        return $response;
    }


    public function getTransactions():ResponseBootstrap {
        // create response object
        $response = new ResponseBootstrap();

        // call mapper to retrieve data from database
        $data = $this->statisticMapper->getTransactions();

        if(!empty($data)){
            $response->setStatus(200);
            $response->setMessage('Success');
            $response->setData($data);
        }else {
            $response->setStatus(204);
            $response->setMessage('No content');
        }

        // return $response
        return $response;
    }


    public function getPurchase(int $id):ResponseBootstrap {
        // create response object
        $response = new ResponseBootstrap();

        // call mapper to retrieve data from database
        $data = $this->statisticMapper->getPurchaseDetails($id);

        if(!empty($data)){
            $response->setStatus(200);
            $response->setMessage('Success');
            $response->setData($data);
        }else {
            $response->setStatus(204);
            $response->setMessage('No content');
        }

        // return $response
        return $response;
    }


    public function getQuantitiesSold():ResponseBootstrap {
        // create response object
        $response = new ResponseBootstrap();

        // call mapper to retrieve data from database
        $data = $this->statisticMapper->getQuantitiesSold();

        if(!empty($data)){
            $response->setStatus(200);
            $response->setMessage('Success');
            $response->setData($data);
        }else {
            $response->setStatus(204);
            $response->setMessage('No content');
        }

        // return $response
        return $response;
    }


    public function changeState(string $state, int $id):ResponseBootstrap {
        // create response object
        $response = new ResponseBootstrap();

        // call mapper to retrieve data from database
        $data = $this->statisticMapper->changeState($state, $id);

        if(!empty($data)){
            $response->setStatus(200);
            $response->setMessage('Success');
        }else {
            $response->setStatus(304);
            $response->setMessage('Not modified');
        }

        // return $response
        return $response;
    }
}