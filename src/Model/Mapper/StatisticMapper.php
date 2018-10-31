<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:49 PM
 */

namespace Model\Mapper;

use PDOException;
use PDO;
use Component\DataMapper;

class StatisticMapper extends DataMapper
{

    public function getByCountries(){

        $data = [];

        try {

            $sql = "SELECT DISTINCT country, count(id) as sales FROM orders GROUP BY country ORDER BY sales DESC LIMIT 10";
            $statement = $this->connection->prepare($sql);
            $statement->execute([]);

            $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch (\PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get stats by countries mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get stats by countries mapper: ' . $e);
            }
        }

        return $data;
    }


    public function getByPeriod(int $period){

        $data = [];

        try {

            $sql = "SELECT COUNT(*) as sales FROM `orders` 
                    WHERE DATE(date) > (NOW() - INTERVAL ? DAY)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $period
            ]);

            $data = $statement->fetch(PDO::FETCH_ASSOC);

        }catch (\PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get stats by period mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get stats by period mapper: ' . $e);
            }
        }

        return $data;
    }


    public function getTransactions(){

        $data = [];

        /*
         * SELECT
                       o.id,
                       o.order_id,
                       o.country as location,
                       o.date,
                       o.shipped,
                       oi.name,
                       oi.quantity
                    FROM orders AS o
                    RIGHT JOIN order_items AS oi ON o.id = oi.order_id
                    GROUP BY o.id
                    ORDER BY date DESC
        */

        try {

            $sql = "SELECT
                       o.id,
                       o.order_id,
                       o.country as location,
                       o.date,
                       o.shipped
                    FROM orders AS o 
                    GROUP BY o.id
                    ORDER BY date DESC";
            $statement = $this->connection->prepare($sql);
            $statement->execute([]);

            $data = $statement->fetchAll(PDO::FETCH_ASSOC);

//            $data = [];
//            $counter = 0;
//
//            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
//                $data[$counter]['id'] = $row['id'];
//                $data[$counter]['order_id'] = $row['order_id'];
//                $data[$counter]['location'] = $row['location'];
//                $data[$counter]['date'] = $row['date'];
//                $data[$counter]['shipped'] = $row['shipped'];
//
//                $sqlProducts = "SELECT name, quantity FROM order_items WHERE order_id = ?";
//                $statementProducts = $this->connection->prepare($sqlProducts);
//                $statementProducts->execute([
//                    $row['id']
//                ]);
//
//                $data[$counter]['products'] = $statementProducts->fetchAll(PDO::FETCH_ASSOC);
//
//                $counter++;
//            }


        }catch (\PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get stat transactions mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get stat transactions mapper: ' . $e);
            }
        }

        return $data;
    }



    public function getPurchaseDetails(int $id){

        $data = [];

        try {

            $sql = "SELECT
                       o.id,
                       o.country as location,
                       o.name,
                       o.street,
                       o.city, 
                       o.customer_email as email,
                       o.amount,
                       o.shipping_amount,
                       o.date,
                       o.postal_code
                    FROM orders AS o 
                    WHERE o.id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $id
            ]);

            // $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            $counter = 0;

            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                $data[$counter]['id'] = $row['id'];
                $data[$counter]['location'] = $row['location'];
                $data[$counter]['street'] = $row['street'];
                $data[$counter]['postal_code'] = $row['postal_code'];
                $data[$counter]['email'] = $row['email'];
                $data[$counter]['city'] = $row['city'];
                $data[$counter]['name'] = $row['name'];

                $sqlProducts = "SELECT name, quantity FROM order_items WHERE order_id = ?";
                $statementProducts = $this->connection->prepare($sqlProducts);
                $statementProducts->execute([
                    $row['id']
                ]);

                $data[$counter]['products'] = $statementProducts->fetchAll(PDO::FETCH_ASSOC);

                $counter++;
            }


        }catch (\PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get purchase details mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get purchase details mapper: ' . $e);
            }
        }

        return $data;
    }




    public function getQuantitiesSold(){

        $data = [];

        try {

            $sql = "SELECT 
                      name, 
                      SUM(quantity) AS sold 
                    FROM order_items 
                    GROUP BY name";
            $statement = $this->connection->prepare($sql);
            $statement->execute([]);

            $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch (\PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get stat quantities mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get stat quantities mapper: ' . $e);
            }
        }

        return $data;
    }


    public function changeState(string $state, int $id){

        $data = [];

        try {

            $sql = "UPDATE orders SET shipped = ? WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $state,
                $id
            ]);

            if($statement->rowCount() > 0){
                $data = [1];
            }

        }catch (\PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Change purchase state mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Change purchase state mapper: ' . $e);
            }
        }

        return $data;
    }
}