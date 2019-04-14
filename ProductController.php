<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Models\Product;
use App\Models\Category;
use DB;
class ProductController extends Controller
{
    //
    public function getProduct(){
        $data['productlist'] = DB::table('vp_products')->join('vp_categories','vp_products.prod_cate','=','vp_categories.cate_id')->orderBy('prod_id')->paginate(6);
    	return view('backend.product',$data);
    }

    public function getAddProduct(){
        $data['catelist'] = Category::all();
    	return view('backend.addproduct',$data);
    }

    public function postAddProduct(AddProductRequest $request){
    	$filename = $request->img->getClientOriginalName();
        $product = new Product;
        $product->prod_name = $request->name;
        $product->prod_slug = str_slug($request->name);
        $product->prod_img = $filename;
        $product->prod_accessories = $request->accessories;
        $product->prod_price = $request->price;
        $product->prod_warranty = $request->warranty;
        $product->prod_promotion = $request->promotion;
        $product->prod_condition = $request->condition;
        $product->prod_status = $request->status;
        $product->prod_description = $request->description;
        $product->prod_cate = $request->cate;
        $product->prod_featured = $request->featured;
        $product->save();
        $request->img->storeAs('avatar',$filename);
        return back();
    }

    public function getEditProduct($id){
        $data['product'] = Product::find($id);
        $data['listcate'] = Category::all();
    	return view('backend.editproduct',$data);
    }

    public function postEditProduct(Request $requets,$id){
    	$product = new Product;
        $arr['prod_name'] = $requets->name;
        $arr['prod_slug'] = str_slug($requets->name);
        $arr['prod_accessories'] = $requets->accessories;
        $arr['prod_price'] = $requets->price;
        $arr['prod_warranty'] = $requets->warranty;
        $arr['prod_promotion'] = $requets->promotion;
        $arr['prod_condition'] = $requets->condition;
        $arr['prod_status'] = $requets->status;
        $arr['prod_description'] = $requets->description;
        $arr['prod_cate'] = $requets->cate;
        $arr['prod_featured'] = $requets->featured;
        if($requets->hasFile('img')){
            $img = $request->img->getClientOriginalName();
            $arr['prod_img'] = $img;
            $requets->img->storeAs('avatar'.$img);
        }
        $product::where('prod_id',$id)->update($arr);
        return redirect('admin/product');
    }

    public function getDeleteProduct($id){
    	Product::destroy($id);
        return back();
    }
}
