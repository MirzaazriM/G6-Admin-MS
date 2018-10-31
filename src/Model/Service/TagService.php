<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:32 PM
 */

namespace Model\Service;


use Model\Entity\ResponseBootstrap;
use Model\Mapper\TagMapper;
use Monolog\Logger;

class TagService
{

    private $tagMapper;
    private $configuration;
    private $monolog;

    public function __construct(TagMapper $tagMapper)
    {
        $this->tagMapper = $tagMapper;
        $this->configuration = $tagMapper->getConfiguration();
        $this->monolog = new Logger('monolog');
    }


    /**
     * Get all tags service
     *
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTags():ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['tags_url'] . '/tags/all', []);

            // set data to variable
            $data = $result->getBody()->getContents();

            // check status code and set appropriate response
            if($result->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData(json_decode($data));
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get tags service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get tags by ids service
     *
     * @param string $ids
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTagsByIds(string $ids):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['tags_url'] . '/tags/ids?ids=' . $ids, []);

            // set data to variable
            $data = $result->getBody()->getContents();

            // check status code and set appropriate response
            if($result->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData(json_decode($data));
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get tags by ids service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Add tag service
     *
     * @param string $name
     * @return ResponseBootstrap
     */
    public function addTag(string $name):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->post($this->configuration['tags_url'] . '/tags',
                [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'name' => $name
                    ]
                ]);

            // check status code and set appropriate response
            if($result->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            }else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Add tag service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Delete tag service
     *
     * @param int $id
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteTag(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('DELETE', $this->configuration['tags_url'] . '/tags?id=' . $id, []);

            // check status code and set appropriate response
            if($result->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            }else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Delete tag service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }
}