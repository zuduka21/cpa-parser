<?php
/**
 * Created by PhpStorm.
 * User: RavenXion
 * Date: 09.07.2019
 * Time: 15:33
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    protected $model;

    public function __construct(){
        $this->model = app($this->getModelClass());
    }

    abstract protected function getModelClass();

    protected function startConditions(){
        return clone $this->model;
    }
}