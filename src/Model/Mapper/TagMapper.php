<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:32 PM
 */

namespace Model\Mapper;


use Component\DataMapper;

class TagMapper extends DataMapper
{

    public function getConfiguration(){
        return $this->configuration;
    }

}