<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        return response()->json([
            'success'=>true,
            'message'=>'All products get successfully!',
            'data'=> $products

        ],Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            if($validator->fails()){
                return response()->json([
                    'success'=>false,
                    'message'=>'Validation faild',
                    'error'=>$validator->errors()

                ]);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($validator->validated());
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = Product::find($id);
        if(!$products){
            return response()->json([

            'success'=>false,
            'message'=>'Product id is not found'
            ],Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Successfully',
            'data'=>$products

        ],Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            if($validator->fails()){
                return response()->json([
                    'success'=>false,
                    'message'=>'Validation faild',
                    'error'=>$validator->errors()

                ]);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }
            $product= Product::find($id);

            $product->update($validator->validated());
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $products = Product::find($id);
        $products->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Product id has been deleted!'

        ],Response::HTTP_OK);
    }
}
