<?php

namespace App\Http\Controllers;

use App\Helpers\CustomResponse;
use App\Services\ProductServices;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $productService;
    public function __construct() {
        $this->productService = new ProductServices();
    }
    public function getAll(Request $request){
        $data = $this->productService->getAll($request);
        return CustomResponse::response(CustomResponse::$success_array,'',false,[
           'products'=> $data
        ]);
    }
    public function createProduct(Request $request){
        $data = $this->productService->createProduct($request);
        if($data === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false);
        }else{
            return CustomResponse::response(CustomResponse::$error_array,'failed to create product',false);
        }
        return $data;
    }
    public function editProduct(Request $request){
        $data = $this->productService->editProduct($request);
        if($data === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false);
        }else{
            return CustomResponse::response(CustomResponse::$error_array,'failed to edit product',false);
        }
        return $data;
    }
    public function deleteProduct(Request $request){
        $data = $this->productService->deleteProduct($request);
        if($data === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false);
        }
        return CustomResponse::response(CustomResponse::$error_array,'failed to delete product',false);
    }
    public function checkName(Request $request){
        $check = $this->productService->checkName($request);
        if($check === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false,[$check]);
        }
        return CustomResponse::response(CustomResponse::$error_array,'',false,[$check]);
    }
}
