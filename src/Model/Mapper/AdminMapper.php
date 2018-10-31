<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:16 PM
 */

namespace Model\Mapper;

use Model\Entity\Admin;
use Model\Entity\Shared;
use PDOException;
use PDO;
use Component\DataMapper;

class AdminMapper extends DataMapper
{

    public function getAdmins(){

        try {
            // set database instructions
            $sql = "SELECT 
                        id,
                        name,
                        email,
                        scope,
                        status,
                        image
                    FROM admins";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // set response values if any
            if($statement->rowCount() > 0){
                // set data variable
                $data = [];

                // set counter variable
                $counter = 0;

                // loop through fetched data
                while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                    // set values
                    $data[$counter]['id'] = $row['id'];
                    $data[$counter]['name'] = $row['name'];
                    $data[$counter]['email'] = $row['email'];
                    $data[$counter]['scope'] = $row['scope'];
                    $data[$counter]['status'] = $row['status'];
                    $data[$counter]['image'] = $row['image'];

                    // increment array index
                    $counter++;
                }
            }

        }catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get admins mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get admins mapper: ' . $e);
            }
        }

        // return data
        return $data;
    }


    /**
     * Update admin values
     *
     * @param Admin $admin
     * @param Shared $shared
     * @return Shared
     */
    public function editAdmin(Admin $admin, Shared $shared):Shared {

        try {
            // set database instructions
            $sql = "UPDATE admins SET
                        scope = ?
                    WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $admin->getScope(),
                $admin->getId()
            ]);

            // set response values if any
            if($statement->rowCount() > 0){
                $shared->setState(200);
            }else {
                $shared->setState(304);
            }

        }catch (PDOException $e){
            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Edit admin mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Edit admin mapper: ' . $e);
            }
        }

        // return data
        return $shared;
    }


    /**
     * Insert admin
     *
     * @param Admin $admin
     * @param Shared $shared
     * @return Shared
     */
    public function addAdmin(Admin $admin, Shared $shared):Shared {

        try {
            // set database instructions
            $sql = "INSERT INTO admins
                      (email, scope)
                       VALUES(?,?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $admin->getEmail(),
                $admin->getScope()
            ]);

            // set response values if any
            if($statement->rowCount() > 0){
                $shared->setState(200);
            }else {
                $shared->setState(304);
            }

        }catch (PDOException $e){
            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Add admin mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Add admin mapper: ' . $e);
            }
        }

        // return data
        return $shared;
    }


    /**
     * Delete admin
     *
     * @param Admin $admin
     * @param Shared $shared
     * @return Shared
     */
    public function deleteAdmin(Admin $admin, Shared $shared):Shared {

        try {
            // set database instructions
            $sql = "DELETE FROM admins WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $admin->getId()
            ]);

            // set state
            if($statement->rowCount() > 0){
                $shared->setState(200);
            }else {
                $shared->setState(304);
            }

        }catch(PDOException $e){
            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Delete admin mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Delete admin mapper: ' . $e);
            }
        }

        // return response
        return $shared;
    }
}