<?php
namespace Modules\Blog\Services;

use Modules\Blog\Repositories\PostReactionRepository;

class PostReactionService
{
    protected $repository;

    public function __construct(PostReactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getGroupByCount($post_id)
    {
        return $this->repository->getGroupByCount($post_id);
    }

    public function getByParams($request)
    {
        return $this->repository->getByParams($request);
    }

    public function getAll($request)
    {
        return $this->repository->getAll($request);
    }

    public function makeReaction($data)
    {
        return $this->repository->makeReaction($data);
    }
}
