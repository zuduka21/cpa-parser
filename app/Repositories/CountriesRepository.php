<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 09.07.2019
 * Time: 15:46
 */

namespace App\Repositories;

use App\Country as Model;
use function foo\func;

class CountriesRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public function FindById($id){
        return $this->startConditions()->
        find($id);
    }

    public function getEdit($id){
        return $this->startConditions()->
        with('brands')->
        find($id);
    }

    public function getByIdOrFirst($id){
        $model = $this->startConditions()->
        with('brands')->
        find($id);
        if(empty($model)){
            return $this->startConditions()->
            with('brands')->first();
        }
        return $model;
    }

    public function getModel($id){
        return $this->startConditions()->
        with('brands')->
        find($id);
    }

    public function getAll(){
        $goods = $this->startConditions()->
        orderBy('name','ASC');

        $goods = $goods->get();
        return $goods;
    }

    public function getWithPaginationAll($search = array(['name'=>''])){
        $goods = $this->startConditions()->
        with('brands')->
        orderBy('name','ASC');

        $goods = $goods->paginate(config('countries.pagination_number'));
        return $goods;
    }
}