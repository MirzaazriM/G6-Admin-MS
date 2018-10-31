<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:54 PM
 */

namespace Model\Service;


use Dubture\Monolog\Reader\LogReader;
use Model\Entity\ResponseBootstrap;
use Model\Mapper\MonologMapper;
use Monolog\Logger;

class MonologService
{

    private $monologMapper;
    private $configuration;
    private $monolog;

    public function __construct(MonologMapper $monologMapper)
    {
        $this->monologMapper = $monologMapper;
        $this->configuration = $monologMapper->getConfiguration();
        $this->monolog = new Logger('monolog');
    }


    /**
     * Get logs
     *
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLogs(string $type):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // set configuration index
            $index = $type . '_url';

            // check if there is need to call MS for data
            if($index === 'admin_url'){
                // get data from this MS
                $data = json_encode($this->getAdminLogs());
            }else {
                // create guzzle client and send request for data
                $client = new \GuzzleHttp\Client();
                $result = $client->request('GET', $this->configuration[$index] . '/monolog/logs', []);

                // set data to variable
                $data = $result->getBody()->getContents();
            }

            // check status code and set appropriate response
            if(!empty($data)){
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
            $this->monolog->addError('Get logs service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Delete log service
     *
     * @param string $type
     * @param string $date
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteLog(string $type, string $date):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // set status variable
            $status = 304;

            // set configuration index
            $index = $type . '_url';

            // check if log to delete is on another MS
            if($index === 'admin_url'){
                $status = $this->deleteAdminLog($date);
            }else {
                // create guzzle client and send request for data
                $client = new \GuzzleHttp\Client();
                $result = $client->request('DELETE', $this->configuration[$index] . '/monolog/log?date=' . $date, []);
                $status = $result->getStatusCode();
            }

            // check status code and set appropriate response
            if($status === 200){
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
            $this->monolog->addError('Delete log service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get logs
     *
     * @return array
     */
    public function getAdminLogs() {

        try {
            // set responses variable and read file
            $responses = [];
            $reader = new LogReader('../resources/loggs/monolog.txt');

            // Get logs
            foreach ($reader as $key=>$log) {
                if(!empty($log['date'])){
                    array_push($responses,
                        [
                            'id' => $key,
                            'date' => $log['date']->format('Y-m-d H:i:s'),
                            'logger'=> $log['logger'],
                            'level' => $log['level'],
                            'message' => $log['message']
                        ]
                    );
                }
            }

            // return data
            return $responses;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get admin logs service: ' . $e);

            // set response on failure
            return [];
        }
    }


    /**
     * Delete admin log
     *
     * @param string $date
     * @return int
     */
    public function deleteAdminLog(string $date) {

        try{
            // set variable for tracking if anything has been deleted
            $deleted = false;

            // open file
            $file_out = file("../resources/loggs/monolog.txt");

            // loop through file lines and find logg to delete
            foreach($file_out as $key=>$line){
                if(strpos($line, $date) !== false){
                    //Delete the recorded line
                    unset($file_out[$key]);

                    // change tracker value
                    $deleted = true;
                }
            }

            // write new data into file
            file_put_contents("../resources/loggs/monolog.txt", $file_out);

            // return response
            if($deleted === true){
                return 200;
            }else {
                return 304;
            }

        }catch(\Exception $e){
            // write monolog entry
            $this->monolog->addError('Delete admin log service: ' . $e);

            // set response on failure
            return 304;
        }
    }

}