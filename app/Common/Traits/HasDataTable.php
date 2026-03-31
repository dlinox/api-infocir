<?php

namespace App\Common\Traits;

use Illuminate\Support\Str;

trait HasDataTable
{
    public function scopeSearch($query, $search, $columns = [])
    {
        $columns = $columns ?: $query->getModel()::$searchColumns ?? [];

        $query->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhereRaw($column . ' LIKE ?', ["%$search%"]);
            }
        });

        return $query;
    }

    public function scopeFilter($query, $filters)
    {
        foreach ($filters as $filter => $value) {
            $keySnakeCase = Str::snake($filter);
            if (!is_null($value) && (!is_array($value) || !empty($value))) {
                $query->where(function ($query) use ($keySnakeCase, $value) {
                    if (is_array($value)) {
                        $query->whereIn($keySnakeCase, $value);
                    } else if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                        $query->whereDate($keySnakeCase, $value);
                    } else {
                        $query->where($keySnakeCase, $value);
                    }
                });
            }
        }
        return $query;
    }

    public function scopeSort($query, $sorts)
    {
        foreach ($sorts as $sort) {
            $keySnakeCase = Str::snake($sort['key']);
            $query->orderBy($keySnakeCase, $sort['order']);
        }
        return $query;
    }

    public function scopeRange($query, $ranges)
    {
        foreach ($ranges as $column => $range) {
            $from = $range['from'] ?? null;
            $to = $range['to'] ?? null;

            $keySnakeCase = Str::snake($column);

            if ($from && $to) {
                $query->whereBetween($keySnakeCase, [$from, $to]);
            } elseif ($from) {
                $query->where($keySnakeCase, $from);
            } elseif ($to) {
                $query->where($keySnakeCase, $to);
            }
        }

        return $query;
    }

    public static function scopeDataTable($query, $request, $searchColumns = [])
    {
        $itemsPerPage = $request->has('perPage') ? $request->perPage : 10;

        $itemsPerPage = $itemsPerPage == -1 ? 999999 : $itemsPerPage;

        if ($request->has('filters') && is_array($request->filters)) {
            $query->filter($request->filters);
        }

        if ($request->has('ranges') && is_array($request->ranges)) {
            $query->range($request->ranges);
        }

        if ($request->has('search')) {
            $searchColumns = $searchColumns ?: $query->getModel()->searchColumns;
            $query->search($request->search, $searchColumns);
        }

        if ($request->has('sortBy')) {
            $query->sort($request->sortBy);
        }

        $items = $query->paginate($itemsPerPage);

        return [
            'data' => $items->getCollection(),
            'currentPage' => $items->currentPage(),
            'perPage' => $items->perPage(),
            'total' => $items->total(),
        ];
    }
}
