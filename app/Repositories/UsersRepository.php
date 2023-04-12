<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 09.07.2019
 * Time: 15:46
 */

namespace App\Repositories;

use App\User as Model;
use function foo\func;


class UsersRepository extends CoreRepository
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
        with('roles')->
        find($id);
    }

    public function getAllUsers($search = array(['name'=>'','category'=>''])){
        $good_columns = [
            'id',
            'name',
            'email'
        ];
        $goods = $this->startConditions()->
        orderBy('id','DESC')->
        select($good_columns)->
        with('roles');

        $goods = $goods->get();
        return $goods;
    }
}