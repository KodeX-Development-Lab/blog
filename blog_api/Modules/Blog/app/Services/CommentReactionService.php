<?php
namespace Modules\Blog\Services;

use Modules\Blog\Repositories\CommentReactionRepository;

class CommentReactionService
{
    protected $repository;

    public function __construct(CommentReactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getGroupByCount($comment_id)
    {
        return $this->repository->getGroupByCount($comment_id);
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
