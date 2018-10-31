<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:15 PM
 */

namespace Model\Service;


use Model\Entity\Admin;
use Model\Entity\ResponseBootstrap;
use Model\Entity\Shared;
use Model\Mapper\AdminMapper;
use Monolog\Logger;

class AdminService
{

    private $adminMapper;
    private $monolog;

    public function __construct(AdminMapper $adminMapper)
    {
        $this->adminMapper = $adminMapper;
        $this->monolog = new Logger('monolog');
    }


    /**
     * Get admins service
     *
     * @return ResponseBootstrap
     */
    public function getAdmins():ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // call mapper for data
            $data = $this->adminMapper->getAdmins();

            // check returned data and set appropriate response
            if(!empty($data)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData(
                    $data
                );
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get admins service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Edit admin
     *
     * @param int $id
     * @param string $scope
     * @return ResponseBootstrap
     */
    public function editAdmin(int $id, string $scope):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create admin entity and set its values
            $entity = new Admin();
            $entity->setId($id);
            $entity->setScope($scope);
//            $entity->setName($name);
//            $entity->setEmail($email);
//            $entity->setStatus($status);
//            $entity->setImage($image);

            // create shared entity
            $shared = new Shared();

            // call mapper for editing admin
            $result = $this->adminMapper->editAdmin($entity, $shared);

            // check result and set appropriate response
            if($result->getState() === 200){
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
            $this->monolog->addError('Edit admin service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Add admin service
     *
     * @param string $email
     * @param string $scope
     * @return ResponseBootstrap
     */
    public function addAdmin(string $email, string $scope):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create admin entity and set its values
            $entity = new Admin();
            $entity->setEmail($email);
            $entity->setScope($scope);
//            $entity->setName($name);
//            $entity->setStatus($status);
//            $entity->setImage($image);

            // create shared entity
            $shared = new Shared();

            // call mapper for adding admin
            $result = $this->adminMapper->addAdmin($entity, $shared);

            // check result and set appropriate response
            if($result->getState() === 200){
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
            $this->monolog->addError('Add admin service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Delete admin
     *
     * @param int $id
     * @return ResponseBootstrap
     */
    public function deleteAdmin(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Admin();
            $entity->setId($id);

            // create shared entity
            $shared = new Shared();

            // call mapper for deleting admin
            $result = $this->adminMapper->deleteAdmin($entity, $shared);

            // set response
            if($result->getState() === 200){
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
            $this->monolog->addError('Delete admin service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }

}