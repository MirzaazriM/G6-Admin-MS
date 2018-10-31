<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/30/18
 * Time: 4:39 PM
 */

namespace Model\Mapper;


use Component\DataMapper;

class SupplementMapper extends DataMapper
{

    public function getConfiguration() {
        return $this->configuration;
    }
}