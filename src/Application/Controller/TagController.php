<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:32 PM
 */

namespace Application\Controller;

use Model\Entity\ResponseBootstrap;
use Model\Service\TagService;
use Symfony\Component\HttpFoundation\Request;

class TagController
{

    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }


    /**
     * Get all tags
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll(Request $request):ResponseBootstrap {
        // call service for data
        return $this->tagService->getTags();
    }


    /**
     * Get tags by ids
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

        // check if data is present
        if (isset($ids)){
            return $this->tagService->getTagsByIds($ids);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Delete tag
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteRemove(Request $request):ResponseBootstrap {
        // get id from url
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if id is set
        if(isset($id)){
            return $this->tagService->deleteTag($id);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Add tag
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function postAdd(Request $request):ResponseBootstrap {
        // get data
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];

        // create response object
        $response = new ResponseBootstrap();

        // check if data is set
        if (isset($name)){
            return $this->tagService->addTag($name);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }
}