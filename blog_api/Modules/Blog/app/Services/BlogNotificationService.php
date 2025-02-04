<?php
namespace Modules\Blog\Services;

use Modules\Blog\Repositories\BlogNotificationRepository;

class BlogNotificationService
{
    protected $repository;

    public function __construct(BlogNotificationRepository $repository)
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

    public function save($data)
    {
        return $this->repository->save($data);
    }

    public function read($noti_id)
    {
        return $this->repository->read($noti_id);
    }

    public function markAsRead($user_id)
    {
        return $this->repository->markAsRead($user_id);
    }
}
