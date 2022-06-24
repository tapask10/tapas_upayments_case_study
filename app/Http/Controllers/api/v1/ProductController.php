<?php


namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Repository\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function validateBase64($value){
        $explode = explode(',', $value);
        $allow = ['png', 'jpg', 'svg'];
        $format = str_replace(
            [
                'data:image/',
                ';',
                'base64',
            ],
            [
                '', '', '',
            ],
            $explode[0]
        );

        // check file format
        if (!in_array($format, $allow)) {
            return false;
        }

        // check base64 format
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
            return false;
        }
        return true;
    }

    public function save(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|bail',
                'price' => 'required|numeric|bail',
                'category' => 'required|bail',
                'description' => 'required|bail',
                'avatar' => 'required|bail',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return Response::Error(Response::ValidationFormater($error->toArray()), "Validation errors found");
            }

            if ($this->validateBase64($request->get('avatar'))) {
                $image = $request->get('avatar');
                $png_url = Str::random(10).".png";
                $uploadPath = public_path() . "/uploads/products/" . $png_url;
                $img = substr($image, strpos($image, ",")+1);
                $data = base64_decode($img);
                file_put_contents($uploadPath, $data);
                $image = $png_url;

                $product = new Product();
                $product->name = $request->get('name');
                $product->price = $request->get('price');
                $product->category = $request->get('category');
                $product->description = $request->get('description');
                $product->avatar = $image;
                $product->save();

                return Response::Success($product,'Product has been saved successfully');
            } else {
                return Response::Error([],'please use base64 image as a avatar of the product');
            }
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

    public function list($page=NULL){
        try {
            if($page===NULL){
                $records = Product::all();
                return Response::Success($records,'All Products');
            }else{
                $perPage = 10;
                if($page==1){
                    $skip = 0;
                }else{
                    $skip = ($page-1)*$perPage;
                }
                $records = Product::skip($skip)->take($perPage)->get();
                return Response::Success($records,'Product Lists With Page Number');
            }
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

    public function details($id){
        try {
            $product = Product::findOrFail($id);
            return Response::Success($product,'Product Details');
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

    public function delete($id){
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return Response::Success($product,'Product has been deleted successfully');
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

}
