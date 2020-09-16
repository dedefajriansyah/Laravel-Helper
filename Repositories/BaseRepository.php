<?php
/*
 * Author Dede Fajriansyah
 * Created on Tuesday February 11th 2020
 * Email dede.fajriansyah97@gmail.com
 * Telegram https://t.me/dedefajriansyah
 *
 * Copyright (c) 2020 FAJRIANSYAH.COM
 */

namespace Fajriansyah\Repositories;

use Carbon\Carbon;
use Fajriansyah\Contracts\BaseContract;

class BaseRepository implements BaseContract
{
    /**
     * @var
     */
    protected $model;

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Get All
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return $this->model->defaultSelect()->get();
    }

    /**
     * Get List
     *
     * @param array $request
     * @return void
     */
    public function paginate(array $request, array $with = [], array $withCount = [])
    {
        $request["perPage"] = $request["perPage"] ?? 20;
        $request["search"] = $request["search"] ?? null;

        $query = $this->model->defaultSelect()->with($with)->withCount($withCount);
        if (!is_null($request["search"])) {
            foreach ($this->model->getFillable() as $key => $column) {
                if ($key === 0) {
                    $query->where($column, "LIKE", "%" . $request["search"] . "%");
                } else {
                    $query->orWhere($column, "LIKE", "%" . $request["search"] . "%");
                }
            }
        }

        return $query->paginate($request["perPage"]);
    }

    /**
     * @param string $orderBy
     * @param string $orderType
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllOrderBy(string $orderBy, string $orderType)
    {
        $orderType = strtoupper($orderType);
        if (!in_array($orderType, ["ASC", "DESC"])) {
            $orderType = "DESC";
        }
        return $this->model
            ->defaultSelect()
            ->orderBy($orderBy, $orderType)
            ->get();
    }

    /**
     * Find By
     *
     * @param string $column
     * @param string $value
     * @param string $operand
     * @param array $with
     * @return void
     */
    public function findBy(string $column, string $value, string $operand = "=", array $with = [], array $withCount = [])
    {
        return $this->model
            ->defaultSelect()
            ->with($with)
            ->withCount($withCount)
            ->where($column, $operand, $value)
            ->first();
    }

    /**
     * Get Many Where
     *
     * @param string $column
     * @param [type] $value
     * @return void
     */
    public function getManyWhere(array $where = [])
    {
        return $this->model
            ->defaultSelect()
            ->when($where !== [], function ($query) use ($where) {
                $query->where($where);
            })
            ->get();
    }

    /**
     * Count Where
     *
     * @param array $where
     * @return void
     */
    public function countWhere(array $where)
    {
        return $this->model
            ->defaultSelect()
            ->where($where)
            ->count();
    }

    /**
     * @param array $request
     *
     * @return mixed
     */
    public function create(array $request)
    {
        return $this->model->create($request);
    }

    /**
     * @param       $id
     * @param array $request
     *
     * @return mixed
     */
    public function update(int $id, array $request)
    {
        $object = $this->model->defaultSelect()->whereId($id)->firstOrFail();
        $object->fill($request);
        $object->save();
        return $object;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete(int $id)
    {
        $object = $this->model->defaultSelect()->whereId($id)->firstOrFail();
        $object->delete();
        return $object;
    }

    /**
     * @param array $where
     *
     * @return mixed
     */
    public function deleteWhere(array $where)
    {
        return $this->model->defaultSelect()->where($where)->delete();
    }

    /**
     * @param array $where
     *
     * @return mixed
     */
    public function forceDeleteWhere(array $where)
    {
        return $this->model->defaultSelect()->where($where)->forceDelete();
    }

    /**
     * First or create
     *
     * @param array $requestKey
     * @param array $request
     * @return void
     */
    public function firstOrCreate(array $requestKey, array $request)
    {
        return $this->model->firstOrCreate($requestKey, $request);
    }

    /**
     * Update or create
     *
     * @param array $requestKey
     * @param array $request
     * @return void
     */
    public function updateOrCreate(array $requestKey, array $request = null)
    {
        if (is_null($request)) {
            $request = $requestKey;
        }
        return $this->model->updateOrCreate($requestKey, $request);
    }

    /**
     * Get Basic Report
     *
     * @param integer $howLastDay
     * @return void
     */
    public function getBasicReport(int $howLastDay = null)
    {
        $query = $this->model
            ->when(!is_null($howLastDay), function ($query) use ($howLastDay) {
                $query->whereDate("created_at", ">=", Carbon::now()->subDays($howLastDay));
            });
        return [
            "query" => $query,
            "base_object" => [
                "total" => $query->count("id")
            ]
        ];
    }

    /**
     * First Where
     *
     * @param array $where
     * @return void
     */
    public function firstWhere(array $where)
    {
        return $this->model
            ->defaultSelect()
            ->where($where)
            ->firstOrFail();
    }
}
