<?php


namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repository\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function requestHeaderUserID($request){
        if($request->hasHeader('authorization')){
            $token = $request->header('authorization');
            $token = trim(str_replace('Bearer','',$token));
            $user = User::select('id')->where('api_token',$token)->first();
            if(isset($user->id)){
                $userId = $user->id;
            }else{
                $userId = NULL;
            }
        }else{
            $userId = NULL;
        }
        return $userId;
    }

    public function save(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|bail',
                'product_id' => 'required|exists:products,id|bail',
                'qty' => 'required|integer|bail',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return Response::Error(Response::ValidationFormater($error->toArray()), "Validation errors found");
            }
            $userId = $this->requestHeaderUserID($request);

            if($userId !== NULL){
                $isExistProduct = Cart::where('session_id',$request->get('session_id'))->where('product_id',$request->get('product_id'))->where('user_id',$userId)->first();
            }else{
                $isExistProduct = Cart::where('session_id',$request->get('session_id'))->where('product_id',$request->get('product_id'))->first();
            }
            if(isset($isExistProduct->id)){
                $isExistProduct->qty = $isExistProduct->qty+$request->get('qty');
                $isExistProduct->save();

                return Response::Success($isExistProduct,'the cart has been updated');
            }else{
                $cart = new Cart();
                $cart->session_id = $request->get('session_id');
                $cart->user_id = $userId;
                $cart->product_id = $request->get('product_id');
                $cart->qty = $request->get('qty');
                $cart->save();

                return Response::Success($cart,'The product has been saved to cart successfully');
            }
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

    public function update(Request $request, $id=null){
        try {
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|bail',
                'product_id' => 'required|exists:products,id|bail',
                'qty' => 'required|integer|bail',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return Response::Error(Response::ValidationFormater($error->toArray()), "Validation errors found");
            }
            $userId = $this->requestHeaderUserID($request);

            if($userId !== NULL){
                $isExistProduct = Cart::where('id',$id)->where('session_id',$request->get('session_id'))->where('product_id',$request->get('product_id'))->where('user_id',$userId)->first();
            }else{
                $isExistProduct = Cart::where('id',$id)->where('session_id',$request->get('session_id'))->where('product_id',$request->get('product_id'))->first();
            }

            if(isset($isExistProduct->id)){
                $isExistProduct->qty = $isExistProduct->qty+$request->get('qty');
                $isExistProduct->save();

                return Response::Success($isExistProduct,'the cart has been updated');
            }else{
                return Response::Error([],'No product found with this session id');
            }
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

    public function delete(Request $request, $id=null){
        try {
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|bail',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                return Response::Error(Response::ValidationFormater($error->toArray()), "Validation errors found");
            }
            $userId = $this->requestHeaderUserID($request);

            if($userId !== NULL){
                $isExistProduct = Cart::where('id',$id)->where('session_id',$request->get('session_id'))->where('user_id',$userId)->first();
            }else{
                $isExistProduct = Cart::where('id',$id)->where('session_id',$request->get('session_id'))->first();
            }
            if(isset($isExistProduct->id)){
                $isExistProduct->delete();
                return Response::Success($isExistProduct,'the cart has been deleted');
            }else{
                return Response::Error([],'No product found with this session id');
            }
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }

    public function list(Request $request){
        try {
            $isDeleteCartItem = false;
            $data = [];
            $userId = $this->requestHeaderUserID($request);
            if($userId !== NULL){
                $carts = Cart::where('session_id',$request->get('session_id'))->where('user_id',$userId)->get();
            }else{
                $carts = Cart::where('session_id',$request->get('session_id'))->get();
            }
            foreach ($carts as $cart){
                $products = Product::find($cart->product_id);
                if(isset($products->id)){
                    $data[] = [
                        'id'=>$cart->id,
                        'session_id'=>$cart->session_id,
                        'user_id'=>$cart->user_id,
                        'product_id'=>$cart->product_id,
                        'product_name'=>$products->name,
                        'product_price'=>$products->price,
                        'product_avatar'=>$products->avatar,
                        'qty'=>$cart->qty,
                        'total_amount'=>$products->price*$cart->qty,
                    ];
                }else{
                    $isDeleteCartItem = true;
                    $cart->delete();
                }
            }
            $message = $isDeleteCartItem===true ? 'Some item has been deleted for not available' : 'Cart Lists';
            return Response::Error($data,$message);
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }
}
