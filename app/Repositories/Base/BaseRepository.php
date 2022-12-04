<?php

namespace App\Repositories\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Model
     */
    protected $originalModel;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->originalModel = $model;
    }


    public function getModel(): Model
    {
        return $this->model;
    }


    public function setModel($model): BaseRepository
    {
        $this->model = $model;

        return $this;
    }

    public function resetModel(): BaseRepository
    {
        $this->model = new $this->originalModel;

        return $this;
    }

    public function make(array $with = []): Model|Builder
    {
        if (!empty($with)) {
            $this->model = $this->model->with($with);
        }

        return $this->model;
    }


    public function all(array $columns = ['*'], array $relations = []): Collection|array
    {
        return $this->model->with($relations)->get($columns);
    }


    public function findById(int $id, array $columns = ['*'], array $relations = [], array $append = [])
    {
        $this->make($relations);
        return $this->model->select($columns)->find($id)->append($append);
    }

    public function create(array $payload)
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function updateById(int $id, array $payload)
    {
        $model = $this->findById($id);
        return $model->update($payload);
    }

    public function deleteById(int $id)
    {
        return $this->findById($id)->delete();
    }

    public function allBy(array $condition, array $relations = [], array $columns = ['*']): Collection|array
    {
        $this->applyConditions($condition);

        $data = $this->make($relations)->select($columns);

        return $data->get();
    }

    public function getListPaginateBy(array $condition, array $relations = [], array $columns = ['*'], $paginate = 10): LengthAwarePaginator
    {
        $this->applyConditions($condition);
        return $this->make($relations)->select($columns)->paginate($paginate);
    }

    public function getFirstBy(array $condition = [], array $columns = [], array $relations = [])
    {
        $this->make($relations);

        $this->applyConditions($condition);

        $data = $this->model->select('*');
        if (!empty($columns)) {
            $data = $this->model->select($columns);
        }
        return $data->first();
    }

    public function firstOrCreate(array $data, array $with = [])
    {
        $data = $this->model->firstOrCreate($data, $with);

        $this->resetModel();

        return $data;
    }

    public function pluck($column, $key = null, array $condition = [])
    {
        $this->applyConditions($condition);

        $select = [$column];
        if (!empty($key)) {
            $select = [$column, $key];
        }

        $data = $this->model->select($select);

        return $data->pluck($column, $key)->all();
    }

    public function createOrUpdate($data, array $condition = []): Model|bool
    {
        if (is_array($data)) {
            $item = $this->getFirstBy($condition);
            if (empty($condition)) {
                $item = new $this->model;
            }

            if (empty($item)) {
                $item = new $this->model;
            }

            $item = $item->fill($data);
        } elseif ($data instanceof Model) {
            $item = $data;
        } else {
            return false;
        }

        $this->resetModel();
        if ($item->save()) {
            return $item;
        }

        return false;
    }


    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }

    public function deleteBy(array $condition = []): bool
    {
        $this->applyConditions($condition);

        $data = $this->model->get();

        if (empty($data)) {
            return false;
        }

        foreach ($data as $item) {
            $item->delete();
        }

        $this->resetModel();

        return true;
    }

    public function count(array $condition = [])
    {
        $this->applyConditions($condition);

        $data = $this->model->count();

        $this->resetModel();

        return $data;
    }

    public function getByWhereIn($column, array $value = [], array $args = [])
    {
        $data = $this->model->whereIn($column, $value);

        if (!empty(Arr::get($args, 'where'))) {
            $this->applyConditions($args['where']);
        }

        if (!empty(Arr::get($args, 'paginate'))) {
            return $data->paginate((int)$args['paginate']);
        } elseif (!empty(Arr::get($args, 'limit'))) {
            return $data->limit((int)$args['limit']);
        }

        return $data->get();
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function update(array $condition, array $data): bool
    {
        $this->applyConditions($condition);

        $data = $this->model->update($data);

        $this->resetModel();

        return $data;
    }

    protected function applyConditions(array $where, &$model = null)
    {
        if (!$model) {
            $newModel = $this->model;
        } else {
            $newModel = $model;
        }

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $condition, $val] = $value;
                $newModel = match (strtoupper($condition)) {
                    'IN' => $newModel->whereIn($field, $val),
                    'NOT_IN' => $newModel->whereNotIn($field, $val),
                    'OR' => $newModel->where(function ($query) use ($field, $val) {
                        if (is_array($val)) {
                            foreach ($val as $item) {
                                $query = $query->orWhere(function ($q) use ($item) {
                                    [$field, $condition, $val] = $item;
                                    return match (strtoupper($condition)) {
                                        'IN' => $q->whereIn($field, $val),
                                        'NOT_IN' => $q->whereNotIn($field, $val),
                                        default => $q->where($field, $condition, $val),
                                    };
                                });
                            }
                        } else {
                            $query = $query->orWhere($field, $val);
                        }

                        return $query;
                    }),
                    'ORDER_BY' => $newModel->orderBy($field, $val),
                    default => $newModel->where($field, $condition, $val),
                };
            } else {
                $newModel = $newModel->where($field, $value);
            }
        }
        if (!$model) {
            $this->model = $newModel;
        } else {
            $model = $newModel;
        }
    }
}
