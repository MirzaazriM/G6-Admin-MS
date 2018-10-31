<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:38 PM
 */

namespace Application\Controller;


use Model\Entity\ResponseBootstrap;
use Model\Service\SupplementService;
use Symfony\Component\HttpFoundation\Request;

class SupplementController
{

    private $supplementService;

    public function __construct(SupplementService $supplementService)
    {
        $this->supplementService = $supplementService;
    }

    /**
     * Get single supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(Request $request):ResponseBootstrap {
        // get id
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if id is set
        if (isset($id)){
            return $this->supplementService->getSupplement($id);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Get supplement by ids
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIds(Request $request):ResponseBootstrap {
        // get ids
        $ids = $request->get('ids');

        // create response object
        $response = new ResponseBootstrap();

        // check if ids are set
        if (isset($ids)){
            return $this->supplementService->getSupplementsByIds($ids);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Get all supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll(Request $request):ResponseBootstrap
    {
        // get data from url
        $from = $request->get('from');
        $limit = $request->get('limit');

        // create response object
        $response = new ResponseBootstrap();

        // check if neccesary data is set
        if(isset($from) && isset($limit)){
            // call service
            return $this->supplementService->getSupplements($from, $limit);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Get searched supplements
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSearch(Request $request):ResponseBootstrap
    {
        // get data from url
        $term = $request->get('term');

        // create response object
        $response = new ResponseBootstrap();

        // check if neccesary data is set
        if(isset($term)){
            // call service
            return $this->supplementService->getSearchResults($term);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Add supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function post(Request $request):ResponseBootstrap {
        // get body data
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $description = $data['description'];
        $images = $data['images'];
        $tags = $data['tags'];

        // create response object
        $response = new ResponseBootstrap();

        // check data
        if (isset($name) && isset($description) && isset($images) && isset($tags)){
            return $this->supplementService->addSupplement($name, $description, $images, $tags);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Edit supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function put(Request $request):ResponseBootstrap {
        // get body data
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $outOfStock = $data['out_of_stock'];
        $images = $data['images'];
        $tags = $data['tags'];

        // create response object
        $response = new ResponseBootstrap();

        // check if data is set
        if(isset($id) && isset($name) && isset($description) && isset($outOfStock) && isset($images) && isset($tags)){
            return $this->supplementService->editSupplement($id, $name, $description, $outOfStock, $images, $tags);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Delete supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function delete(Request $request):ResponseBootstrap {
        // get id from url
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if id is set
        if (isset($id)){
            // call service
            return $this->supplementService->deleteSupplement($id);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }
}