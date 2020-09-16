<?php
/*
 * Author Dede Fajriansyah
 * Created on Sunday February 23rd 2020
 * Email dede.fajriansyah97@gmail.com
 * Telegram https://t.me/dedefajriansyah
 *
 * Copyright (c) 2020 FAJRIANSYAH.COM
 */

namespace Fajriansyah\Contracts;

interface BaseContract
{
    public function getModel();
    public function setModel($model);
    public function firstOrCreate(array $requestKey, array $request);
    public function updateOrCreate(array $requestKey, array $request = null);
    public function paginate(array $request, array $with = [], array $withCount = []);
    public function getAll();
    public function getAllOrderBy(string $orderBy, string $orderType);
    public function findBy(string $column, string $value, string $operand = "=", array $with = [], array $withCount = []);
    public function getManyWhere(array $where = []);
    public function countWhere(array $where);
    public function create(array $request);
    public function update(int $id, array $request);
    public function delete(int $id);
    public function deleteWhere(array $where);
    public function forceDeleteWhere(array $where);
    public function getBasicReport(int $howLastDay = null);
}
