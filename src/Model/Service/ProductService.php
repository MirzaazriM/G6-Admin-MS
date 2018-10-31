<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:44 PM
 */

namespace Model\Service;

use Model\Entity\ResponseBootstrap;
use Model\Mapper\ProductMapper;
use Monolog\Logger;

class ProductService
{

    private $productMapper;
    private $configuration;

    public function __construct(ProductMapper $productMapper)
    {
        $this->productMapper = $productMapper;
        $this->configuration = $productMapper->getConfiguration();
        $this->monolog = new Logger('monolog');
    }


    /**
     * Get single product
     *
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProduct(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['products_url'] . '/products?id=' . $id, []);

            // set data to variable
            $data = $result->getBody()->getContents();

            // check status code and set appropriate response
            if($result->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData([json_decode($data, true)]);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get product service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get all products
     *
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProducts():ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['products_url'] . '/products/all', []);

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
            $this->monolog->addError('Get products service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get number of active products
     *
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getActiveProducts():ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['products_url'] . '/products/active', []);

            // set data to variable
            $data = $result->getBody()->getContents();
            $data = json_decode($data);

            // check status code and set appropriate response
            if($result->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData([$data]);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get number of active products service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get last 2 added products
     *
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLastProducts():ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $this->configuration['products_url'] . '/products/last', []);

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
            $this->monolog->addError('Get last two added products service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Delete product
     *
     * @param int $id
     * @param string $productId
     * @return ResponseBootstrap
     */
    public function deleteProduct(int $id, string $productId, string $skuId):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->request('DELETE', $this->configuration['products_url'] . '/products?id=' . $id . '&product_id=' . $productId . '&sku_id=' . $skuId, []);

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
            $this->monolog->addError('Delete product service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Add product
     *
     * @param string $name
     * @param string $description
     * @param string $price
     * @param string $discount
     * @param array $images
     * @param array $tags
     * @param array $supplements
     * @return ResponseBootstrap
     */
    public function addProduct(string $name, string $description, string $price, string $discount, array $images, array $tags, array $supplements, array $dimensions):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->post($this->configuration['products_url'] . '/products',
                [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'discount' => $discount,
                        'images' => $images,
                        'tags' => $tags,
                        'supplements' => $supplements,
                        'dimensions' => $dimensions
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
            $this->monolog->addError('Add product service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Edit product
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $price
     * @param string $discount
     * @param array $images
     * @param array $tags
     * @param array $supplements
     * @param string $outOfStock
     * @return ResponseBootstrap
     */
    public function editProduct(int $id, string $skuId, string $productId, string $name, string $description, string $price, string $discount, array $images, array $tags, array $supplements, string $outOfStock, array $dimensions):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create guzzle client and send request for data
            $client = new \GuzzleHttp\Client();
            $result = $client->put($this->configuration['products_url'] . '/products',
                [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'id' => $id,
                        'sku_id' => $skuId,
                        'product_id' => $productId,
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'discount' => $discount,
                        'images' => $images,
                        'tags' => $tags,
                        'supplements' => $supplements,
                        'out_of_stock' => $outOfStock,
                        'dimensions' => $dimensions
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
            $this->monolog->addError('Edit product service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }

}