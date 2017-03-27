<?php
namespace Api\Model\Mapper;

use Api\Model\Repository;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class RepositoryMapper implements MapperInterface
{

    /**
     * {@inheritdoc}
     */
    public function map($data)
    {
        if (is_array($data)) {
            $data = (object) $data;
        }

        $repository = new Repository();


        $repository->setForks($data->forks_count);
        $repository->setStars($data->stargazers_count);
        $repository->setWatchers($data->watchers_count);
        $repository->setFullName($data->full_name);


        return $repository;
    }
}