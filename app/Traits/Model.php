<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

trait Model {

    public function getModel()
    {
        return app()->make($this->model)->query();
    }

    public function trim($data)
    {
        $columnList = Schema::getColumnListing(app()->make($this->model)->getTable());
        foreach ($data as $key => $item) {
            if (!in_array($key, $columnList)) unset($data[$key]);
        }
        return $data;
    }

    public function create($data)
    {
        return app()->make($this->model)->query()->create($this->trim($data));
    }

    public function save($data)
    {
        $model = app()->make($this->model);
        $model->fill($this->trim($data));
        $model->save();
        return $model;
    }

    public function updateOrCreate($conditions, $data)
    {
        return app()->make($this->model)->query()->updateOrCreate($conditions, $this->trim($data));
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($id, $data)
    {
        $model = app()->make($this->model)->query()->find($id);
        if (! $model) {
            throw new \Exception("model id {$model} 不存在");
        }
        $model->update($this->trim($data));

        return $model;
    }

    public function updateBy($cnds, $data)
    {
        $model = app()->make($this->model)->query()->where($cnds)->update($this->trim($data));
        return $model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id, $with=[])
    {
        return app()->make($this->model)->query()->with($with)->find($id);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return app()->make($this->model)->query()->first();
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        return app()->make($this->model)->destroy($id);
    }

    public function deleteBy($cnds)
    {
        return app()->make($this->model)->query()->where($cnds)->delete();
    }

    public function beforeList()
    {
        $api_caller = Auth::user();
        if ($api_caller) {
            $full_class_name = get_class($api_caller);
            $base_class_name = basename(str_replace('\\', '/', $full_class_name));
            $foo_name        = 'getListBy' . $base_class_name;

            if (method_exists($this, $foo_name)) {
                return $this->$foo_name();
            }
        }

        return app()->make($this->model)->query();
    }

    public function list($with=[])
    {
        $model = $this->beforeList();
        if ($model instanceof JsonResponse) {
            return $model;
        }
        $model = $model->with($with);
        $model = $this->filter($model);
        $model = $this->orderBy($model);
        if (empty(request('page'))) {
            $model = $model->get();
        } else {
            $model = $model->paginate();
        }

        if (! $model) {
            throw new \Exception("model {$model} 不存在");
        }

        $model = $this->afterList($model);
        return $model;
    }

    public function afterList($model)
    {
        $hiddenFields = $this->hiddenFields ?? [];

        foreach ($model as &$item) {
            foreach ($hiddenFields as $field) {
                unset($item[$field]);
            }
        }
        return $model;
    }

    public function filter($model)
    {
        $searchFields = array_merge($this->whereFields,  $this->likeFields, $this->whereInFields, $this->betweenFields);

        $fields = request()->all();
        if (empty($searchFields)) {
            return $model;
        }

        $fieldsKeys = array_keys($fields);
        foreach ($fieldsKeys as $key) {
            if (!in_array($key, $searchFields)) {
                unset($fields[$key]);
            }
        }

        foreach ($fields as $key => $item) {
            if (in_array($key, $this->whereFields)) {
                $model->where($key, $item);
            }
            if (in_array($key, $this->likeFields)) {
                $model->where($key, 'like', '%' . $item . '%');
            }
            if (in_array($key, $this->whereInFields)) {
                $model->whereIn($key, $item);
            }
            if (in_array($key, $this->betweenFields)) {
                $model->whereBetween($key, $item);
            }
            if (in_array($key, $this->notEqualFields)) {
                $model->where($key, '<>', $item);
            }
        }
        return $model;
    }

    public function orderBy($model)
    {
        foreach ($this->orderByFields as $key => $sort) {
            $model->orderBy($key, $sort);
        }
        return $model;
    }

}
