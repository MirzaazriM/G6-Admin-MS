<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 9/3/18
 * Time: 9:51 AM
 */

namespace Model\Entity;


class Shared
{

    private $state;

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

}