<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 09.07.2019
 * Time: 15:46
 */

namespace App\Repositories;

use App\Partner as Model;
use function foo\func;


class PartnersRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getEdit($id){
        return $this->startConditions()->find($id);
    }

    public function getModel($id){
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

    public function getByIndication($indication){
       return $this->startConditions()->
       where('indication',$indication)->
       with('brands')->
       first();
    }

    public function getEditByIndication($indication){
        return $this->startConditions()->
        where('indication', $indication)->
        first();
    }

    public function getPartners(){
        $columns = [
            'id',
            'name',
            'working',
            'shift_date',
            'indication',
        ];
        $partners = $this->startConditions()->
        select($columns)->
        get();
        return $partners;
    }

    public function getAllPartners(){
        $columns = [
            'id',
            'name',
            'working',
            'shift_date',
            'indication',
        ];
        $partners = $this->startConditions()->
        select($columns)->
        where('working',true)->
        get();
        return $partners;
    }

    public function getAll($partner_id = ''){
        $columns = [
            'id',
            'name',
            'indication',
            'working',
        ];
        $goods = $this->startConditions()->
        orderBy('id','DESC')->
        with('brands')->
        select($columns);
        if(!empty($partner_id)){
            $goods->where('id',$partner_id);
        }
        $goods = $goods->get();
        return $goods;
    }
    public function getAllWithPagination($search = array(['name'=>'','category'=>''])){
        $columns = [
            'id',
            'name',
            'indication',
            'working',
        ];
        $goods = $this->startConditions()->
        orderBy('id','DESC')->
        select($columns);

        $goods = $goods->paginate(config('parser.pagination_number'));
        return $goods;
    }
}