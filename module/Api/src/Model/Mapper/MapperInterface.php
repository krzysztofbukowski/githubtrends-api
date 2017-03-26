<?php
namespace Api\Model\Mapper;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
interface MapperInterface
{

    /**
     * Maps array values to the object's properties
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function map($data);
}