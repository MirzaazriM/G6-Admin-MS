<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:44 PM
 */

namespace Application\Controller;


use Model\Entity\ResponseBootstrap;
use Model\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;

class ProductController
{

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    /**
     * Get single product
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(Request $request):ResponseBootstrap
    {
        // get id
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if parameters are present
        if (isset($id)){
            return $this->productService->getProduct($id);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Get all products
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll(Request $request):ResponseBootstrap {
        // call service for data
        return $this->productService->getProducts();
    }


    /**
     *  Get active products
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getActive(Request $request):ResponseBootstrap
    {
        // call service for data
        return $this->productService->getActiveProducts();
    }


    /**
     * Get last 2 added products
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLast(Request $request):ResponseBootstrap
    {
        // call service for data
        return $this->productService->getLastProducts();
    }


    /**
     * Delete product by its id
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(Request $request):ResponseBootstrap
    {
        // get id
        $id = $request->get('id');
        $productId = $request->get('product_id');
        $skuId = $request->get('sku_id');

        // create response object
        $response = new ResponseBootstrap();

        // check if data is present
        if (isset($id) && isset($productId)){
            return $this->productService->deleteProduct($id, $productId, $skuId);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Add product
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(Request $request):ResponseBootstrap
    {
        // get data
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $price = $data['price'];
        $discount = $data['discount'];
        $description = $data['description'];
        $images = $data['images'];
        $tags = $data['tags'];
        $supplements = $data['supplements'];
        $dimensions = $data['dimensions'];

        // create response object
        $response = new ResponseBootstrap();

        // check if data is present
        if (isset($name) && isset($price) && isset($description) && isset($images)  && isset($discount)  && isset($tags)  && isset($supplements) && isset($dimensions)){
            return $this->productService->addProduct($name, $description, $price, $discount, $images, $tags, $supplements, $dimensions);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Edit product
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(Request $request):ResponseBootstrap
    {
        // get data
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $skuId = $data['sku_id'];
        $productId = $data['product_id'];
        $name = $data['name'];
        $price = $data['price'];
        $discount = $data['discount'];
        $description = $data['description'];
        $images = $data['images'];
        $tags = $data['tags'];
        $supplements = $data['supplements'];
        $outOfStock = $data['out_of_stock'];
        $dimensions = $data['dimensions'];

        // create response object
        $response = new ResponseBootstrap();

        // check if data is present
        if (isset($id) && isset($skuId) && isset($productId) && isset($name) && isset($price) && isset($description) && isset($images)  && isset($discount)  && isset($tags) && isset($supplements) && isset($outOfStock) && isset($dimensions)){
            return $this->productService->editProduct($id, $skuId, $productId, $name, $description, $price, $discount, $images, $tags, $supplements, $outOfStock, $dimensions);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


}