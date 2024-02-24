<?php

namespace App\ToDo;

use Psr\SimpleCache\CacheInterface;
use Yiisoft\Hydrator\Hydrator;

class TodoRepository
{
    const CACHE_KEY = 'todos';

    public function __construct(private Hydrator $hydrator, private CacheInterface $cache)
    {
    }

    public function getNew(?array $data = []): Todo
    {
        if (!empty($data)) {
            return $this->hydrator->create(Todo::class, $data);
        } else {
            return new Todo();
        }
    }

    public function findAll(): array
    {
        $list = $this->cache->has(self::CACHE_KEY) ? $this->cache->get(self::CACHE_KEY) : [];
        return array_map(fn(array $data) => $this->getNew($data), $list);
    }

    public function findOne(string $id): Todo
    {
        $list = $this->findAll();
        $result = array_filter($list, fn(Todo $item) => $item->id == $id);

        return current($result);
    }

    public function save(Todo $todo): bool
    {
        $list = $this->findAll();
        if (empty($todo->id)) {
            $todo->id = uniqid();
            $list[] = $todo;
        } else {
            foreach ($list as &$item) {
                if ($todo->id == $item->id) {
                    $item = $todo;
                    continue;
                }
            }
        }

        return $this->saveList($list);
    }

    public function delete(Todo $todo)
    {
        $list = $this->findAll();
        $list = array_filter($list, fn(Todo $item) => $item->id !== $todo->id);

        return $this->saveList($list);
    }

    public function saveList(array $list): bool
    {
        $list = array_map(fn(Todo $item) => (array)$item, $list);

        return $this->cache->set(self::CACHE_KEY, $list);
    }

    public function findCompleted()
    {
        $list = $this->findAll();
        return array_filter($list, fn(Todo $item) => $item->is_complete);
    }
}
