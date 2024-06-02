<?php

namespace App\Services;

use App\Helpers\CustomResponse;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductServices
{
    public $product;
    public function __construct() {
        $this->product = new Product();
    }
    public function getAll($request){
        $data = [];
        if($request->filled('search')){
            $data = $this->product->getAll($request->filled('search'));
        }else{
         $data = $this->product->getAll();
        }
        return $data;
    }
    public function createProduct($request){
        $validator = $this->checkInputProdutCreate($request);
        if($validator['status'] != 'success') return $validator;

        $create = $this->product->create($request->name);
        return $create;
    }
    public function editProduct($request){
        $validator = $this->checkInputProductEdit($request);
        if($validator['status'] != 'success') return $validator;
        $findProduct = $this->product::find($request->product_id);
        $create = $findProduct->create($request);
        return $create;
    }
    public function deleteProduct($request){
        if(!is_array($request->id) || count($request->id)){
            return false;
        }
        $delete = $this->product->deleteRecord($request);
        return $delete;
    }
    public function checkName($request){
        if(!$request->filled('name')){
          return false;
        }
        $check = $this->product->checkName($request->name);
        return $check;
     }
    private function checkInputProdutCreate($request){
        $rule = [
            'name' =>"required",
            'discount_price' =>"required",
            'actual_price' =>"required",
            'category_id' =>"required",
        ];
        
        $validator= Validator::make($request->all(),$rule,[
            'name.required' => 'Name can not be empty!',
            'discount_price.required' => 'Discount price can not be empty!',
            'actual_price.required' => 'Actual price can not be empty!',
            'category_id.required' => 'Category Id can not be empty!',
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
    private function checkInputProductEdit($request){
        $rule = [
            'name' =>"required",
            'discount_price' =>"required",
            'actual_price' =>"required",
            'category_id' =>"required",
            'product_id'=>"required"
        ];
        
        $validator= Validator::make($request->all(),$rule,[
            'name.required' => 'Name can not be empty!',
            'discount_price.required' => 'Discount price can not be empty!',
            'actual_price.required' => 'Actual price can not be empty!',
            'category_id.required' => 'Category Id can not be empty!',
            'product_id.required' => 'Product Id can not be empty!',
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
