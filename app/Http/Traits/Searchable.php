<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait
Searchable
{
    protected $request;

    protected $builder;
    protected $special = ['date'];

    protected $search_items = [];

    public final function scopeSearch($query, $search_items){
        return $this->search($query, $search_items);
    }

    public final function search(Builder $builder, $search_items)
    {
        $this->request = request();
        $this->builder = $builder;
        foreach ($search_items as $search_item => $search_params) {
            // $this->request->get() 可能获取到 "0" 因此和 null 进行全等判断
            if ($this->request->get($search_item) !== null || in_array($search_params['type'], $this->special)) {
                switch (strtolower($search_params['type'] ?? '')) {
                    case 'equal':
                        $this->search_equal($search_item, $search_params);
                        break;
                    case 'like':
                        $this->search_like($search_item, $search_params);
                        break;
                    case 'and':
                        $this->search_and($search_item, $search_params);
                        break;
                    case 'date':
                        view()->share('start_at', $this->request->get('start_at'));
                        view()->share('end_at', $this->request->get('end_at'));
                        $this->search_date($search_item, $search_params);
                        break;
                    case 'custom':
                        $search_function = 'custom_' . $search_item;
                        $this->$search_function($search_item, $search_params);
                        break;
                    default:
                        $this->search_equal($search_item, $search_params);
                }
                view()->share($search_item, $this->request->get($search_item));
            }
        }

        view()->share('search_items', $search_items);
        return $this->builder;
    }

    private function search_equal($search_item, $search_params)
    {
        $this->builder->where($search_item, $this->request->get($search_item));
    }

    private function search_like($search_item, $search_params)
    {
        $this->builder->where($search_item, "like", "%{$this->request->get($search_item)}%");
    }

    private function search_and($search_item, $search_params)
    {
        $this->builder->where($search_item, "&", $this->request->get($search_item));
    }

    private function search_date($search_item, $search_params)
    {
        $start_at = $this->request->get('start_at');
        $end_at = $this->request->get('end_at');
        $carbon_start = Carbon::parse($start_at);
        $carbon_end = Carbon::parse($end_at)->endOfDay();
        if ($start_at && $end_at) {
            $this->builder->whereBetween($search_item, [$carbon_start, $carbon_end]);
        } elseif ($start_at) {
            $this->builder->where($search_item, '>=', $carbon_start);
        } elseif ($end_at) {
            $this->builder->where($search_item, '<=', $carbon_end);
        }
    }
}
