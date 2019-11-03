<?php

namespace sistema\Repositories\Contracts;

interface RepositoryInterface {

 /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'));
 
    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*')) ;
 
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) ;
 
    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id") ;
 
    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) ;
 
    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*')) ;
 
    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*')) ;
 
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeModel() ;
      
    public function findLikeBy($attribute, $value, $columns = array('*')) ;
    
    public function findByArray($attributefilters) ;
    
    public function findByColumn($attributefilters,$opciones) ;
    
    public function findByCount($array);
    
    public function debug($data);
    
    public function generaLetrero($total,$totalPaginador,$opciones);
 
}