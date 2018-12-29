<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait CommonExportTrait
{
    protected $request;
    protected $builder;
    protected $page = 20;


    public final function scopeExport($query,$export_items = null){
        return $this->export($query,$export_items);
    }

    public final function export(Builder $builder ,$export_items)
    {
        $this->request = request();
        $this->builder = $builder;
        //判断是否导出，如果是导出就用get(),不是就返回分页数据paginate
        if($this->request->get('export')){
            $data = $builder->get();
        }else{
            if($export_items !== null){
                $this->page = $export_items['page']??20;
            }
            $data = $builder->paginate($this->page);
        }
        //判断是否存配置

        return $data;
    }



}