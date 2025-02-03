<?php
namespace Modules\Blog\Services;

use Modules\Blog\Repositories\BlogUserRepository;
use Modules\Blog\Repositories\PostReactionRepository;

class BlogUserService
{
    protected $repository;

    public function __construct(BlogUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByParams($request)
    {
        return $this->repository->getByParams($request);
    }

    public function getAll($request)
    {
        return $this->repository->getAll($request);
    }

    public function getUser($user_id)
    {
        return $this->repository->getUser($user_id);
    }

    public function follow($data)
    {
        return $this->repository->follow($data);
    }
}
