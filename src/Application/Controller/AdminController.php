<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:16 PM
 */

namespace Application\Controller;


use Model\Entity\ResponseBootstrap;
use Model\Service\AdminService;
use Symfony\Component\HttpFoundation\Request;

class AdminController
{

    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }


    /**
     * Get all admins
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function getAll(Request $request):ResponseBootstrap {
        // call service for data
        return $this->adminService->getAdmins();
    }


    /**
     * Edit admin
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function putEdit(Request $request):ResponseBootstrap {
        // get admins data
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $scope = $data['scope'];
//        $name = $data['name'];
//        $email = $data['email'];
//        $status = $data['status'];
//        $image = $data['image'];

        // create response object
        $response = new ResponseBootstrap();

        // check if all neccessary data is present
        if(isset($id) && isset($scope)){
            return $this->adminService->editAdmin($id, $scope);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Add admin
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function postAdd(Request $request):ResponseBootstrap {
        // get admins data
        $data = json_decode($request->getContent(), true);
        //$name = $data['name'];
        $email = $data['email'];
        $scope = $data['scope'];
        //$status = $data['status'];
        //$image = $data['image'];

        // create response object
        $response = new ResponseBootstrap();

        // check if all neccessary data is present
        if(isset($email) && isset($scope)){
            return $this->adminService->addAdmin($email, $scope);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    public function deleteRemove(Request $request):ResponseBootstrap {
        // get id from URL
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if id is set
        if(isset($id)){
            return $this->adminService->deleteAdmin($id);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }
}