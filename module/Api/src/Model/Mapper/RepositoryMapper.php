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
        $repository = new Repository();

        if (!empty($data['details'])) {
            $repository->setForks($data['details']->forks_count);
            $repository->setStars($data['details']->stargazers_count);
            $repository->setWatchers($data['details']->watchers_count);
            $repository->setFullName($data['details']->full_name);
        }

        if (!empty($data['latest_release'])) {
            $repository->setLatestRelease($data['latest_release']->created_at);
        }

        if (!empty($data['pull_requests']['open'])) {
            $repository->setOpenPullRequestsCount($data['pull_requests']['open']->total_count);
        }

        if (!empty($data['pull_requests']['closed'])) {
            $repository->setClosedPullRequestsCount($data['pull_requests']['closed']->total_count);
        }

        if (!empty($data['pull_requests']['merged']) && count($data['pull_requests']['merged']->items) > 0) {
            $repository->setLastMergedPullRequestDate(
                $data['pull_requests']['merged']->items[0]->updated_at
            );
        }

        return $repository;
    }
}