<?php

namespace Modules\Blog\Services;

use Modules\Blog\Repositories\PostRepository;

class PostService
{
    protected $repository;

    public function __construct(PostRepository $repository)
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

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}