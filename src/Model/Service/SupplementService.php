<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:38 PM
 */

namespace Model\Service;


use Model\Entity\ResponseBootstrap;
use Model\Mapper\SupplementMapper;
use Monolog\Logger;

class SupplementService
{

    private $supplementMapper;
    private $configuration;
    private $monolog;

    public function __construct(SupplementMapper $supplementMapper)
    {
        $this->supplementMapper = $supplementMapper;
        $this->configuration = $supplementMapper->getConfiguration();
        $this->monolog = new Logger('monolog');
    }


    /**
     * Get single supplement
     *
     * @param int $id
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplement(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['supplements_url'] . '/supplements?id=' . $id, []);

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
            $this->monolog->addError('Get supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get supplements limited
     *
     * @param int $from
     * @param int $limit
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplements(int $from, int $limit):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['supplements_url'] . '/supplements/all?from=' . $from . '&limit=' . $limit, []);

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
            $this->monolog->addError('Get supplements service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get search results
     *
     * @param string $term
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSearchResults(string $term):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['supplements_url'] . '/supplements/search?term=' . $term, []);

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
            $this->monolog->addError('Get search supplements service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get supplements by ids
     *
     * @param string $ids
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplementsByIds(string $ids):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['supplements_url'] . '/supplements/ids?ids=' . $ids, []);

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
            $this->monolog->addError('Get supplements by ids service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Add supplement
     *
     * @param string $name
     * @param string $description
     * @param array $images
     * @param array $tags
     * @return ResponseBootstrap
     */
    public function addSupplement(string $name, string $description, array $images, array $tags):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->post($this->configuration['supplements_url'] . '/supplements',
                [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'name' => $name,
                        'description' => $description,
                        'images' => $images,
                        'tags' => $tags
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
            $this->monolog->addError('Add supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Edit supplement
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $outOfStock
     * @param array $images
     * @param array $tags
     * @return ResponseBootstrap
     */
    public function editSupplement(int $id, string $name, string $description, string $outOfStock, array $images, array $tags):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->put($this->configuration['supplements_url'] . '/supplements',
                [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'id' => $id,
                        'name' => $name,
                        'description' => $description,
                        'out_of_stock' => $outOfStock,
                        'images' => $images,
                        'tags' => $tags
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
            $this->monolog->addError('Edit supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Delete supplement
     *
     * @param int $id
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteSupplement(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('DELETE', $this->configuration['supplements_url'] . '/supplements?id=' . $id, []);

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
            $this->monolog->addError('Edit supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }
}