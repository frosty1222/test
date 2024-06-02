<?php

namespace App\Services;

use App\Helpers\CustomResponse;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryService
{
    public $category;
    public function __construct() {
      $this->category = new Category();
    }
    public function getAll($request){
        $data = [];
       if($request->filled('search')){
           $data = $this->category->getAll($request->filled('search'));
       }else{
        $data = $this->category->getAll();
       }
       return $data;
    }
    public function createCate($request){
        $validator = $this->checkInputCate($request);
        if($validator['status'] != 'success') return $validator;

        $create = $this->category->create($request->name);
        return $create;
    }
    public function editCate($request){
        $validator = $this->checkInputCateEdit($request);
        if($validator['status'] != 'success') return $validator;
        $findCate = $this->category::find($request->id);
        $create = $findCate->edit($request->name);
        return $create;
    }
    public function deleteCate($request){
        if(!is_array($request->id) || count($request->id)){
            return false;
        }
        $delete = $this->category->deleteRecord($request);
        return $delete;
    }
    public function checkName($request){
       if(!$request->filled('name')){
         return false;
       }
       $check = $this->category->checkName($request->name);
       return $check;
    }
    private function checkInputCate($request){
        $rule = [
            'name' =>"required",
        ];
        
        $validator= Validator::make($request->all(),$rule,[
            'name.required' => 'Name can not be empty!',
        ]);
        if ($validator->fails()) {
            $message="";
            foreach($validator->errors()->getMessages() as $mes){
                $message=$message.$mes[0].', ';
            }
            return CustomResponse::response(CustomResponse::$error_array,$message);
        }

        return CustomResponse::$success_array;
    } 
    private function checkInputCateEdit($request){
        $rule = [
            'name' =>"required",
            'id' =>"required",
        ];
        
        $validator= Validator::make($request->all(),$rule,[
            'name.required' => 'Name can not be empty!',
            'id.required' => 'Id can not be empty!',
        ]);
        if ($validator->fails()) {
            $message="";
            foreach($validator->errors()->getMessages() as $mes){
                $message=$message.$mes[0].', ';
            }
            return CustomResponse::response(CustomResponse::$error_array,$message);
        }

        return CustomResponse::$success_array;
    } 
}
