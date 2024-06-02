<?php

namespace App\Http\Controllers;

use App\Helpers\CustomResponse;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public $categoryService;
    public function __construct() {
        $this->categoryService = new CategoryService();
    }
    public function getAll(Request $request){
        $data = $this->categoryService->getAll($request);
        return CustomResponse::response(CustomResponse::$success_array,'',false,[
           'categories'=> $data
        ]);
    }
    public function createCate(Request $request){
        $data = $this->categoryService->createCate($request);
        if($data === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false);
        }else{
            return CustomResponse::response(CustomResponse::$error_array,'failed to create category',false);
        }
        return $data;
    }
    public function editCate(Request $request){
        $data = $this->categoryService->editCate($request);
        if($data === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false);
        }else{
            return CustomResponse::response(CustomResponse::$error_array,'failed to edit category',false);
        }
        return $data;
    }
    public function deleteCate(Request $request){
        $data = $this->categoryService->deleteCate($request);
        if($data === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false);
        }
        return CustomResponse::response(CustomResponse::$error_array,'failed to create category',false);
    }
    public function checkName(Request $request){
        $check = $this->categoryService->checkName($request);
        if($check === true){
            return CustomResponse::response(CustomResponse::$success_array,'',false,[$check]);
        }
        return CustomResponse::response(CustomResponse::$error_array,'',false,[$check]);
    }
}
