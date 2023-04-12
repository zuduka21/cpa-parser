<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 09.07.2019
 * Time: 15:46
 */

namespace App\Repositories;

use App\Brand as Model;
use function foo\func;

class BrandsRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getEdit($id){
        return $this->startConditions()->
        with('countries')->
        find($id);
    }

    public function getModel($id){
        return $this->startConditions()->
        with('partner')->
        with('countries')->
        find($id);
    }

    public function getAllWithPartner($search = array(['name'=>''])){
        $goods = $this->startConditions()->
        orderBy('name','DESC')->
        with('partner');

        $goods = $goods->get();
        return $goods;
    }

    public function getAll($search = array(['name'=>''])){
        $goods = $this->startConditions()->
        orderBy('name','DESC');

        $goods = $goods->get();
        return $goods;
    }
}