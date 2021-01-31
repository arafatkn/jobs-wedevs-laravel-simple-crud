<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Resources\ProductResource;

class ProductController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $req)
    {
        $products = auth()->user()->products();
        if($req->search) {
            $products = $products->where('title', 'like', '%'.$req->search.'%');
        }
        $products = $products->orderBy('title','asc')->paginate($req->get('per_page',20));
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $user = auth()->user();
        if($user->id != $product->user_id)
            abort(403, "You are not owner of this product.");
        return new ProductResource($product);
    }

    public function store(Request $req)
    {
        $req->validate([
            'title' => 'bail|required|max:256',
            'price' => 'bail|required|numeric',
            'image' => 'bail|required|image',
        ]);

        $product = new Product();
        $product->fill( $req->only(['title','price','slug','description']) );
        $image = $req->file('image');
        $product->image = Product::uploadImage($image);
        $product->user_id = auth()->user()->id;

        if($product->save())
        {
            return response()->json([
                'message' => $req->title.' has been added successfully.',
                'product' => new ProductResource($product)
            ]);
        }
        
        return response()->json(['message' => 'Something is wrong here...'], 501);
    }

    public function update(Product $product, Request $req)
    {
        $user = auth()->user();
        if($user->id != $product->user_id)
            abort(403, "You are not owner of this product.");

        $req->validate([
            'title' => 'bail|required|max:256',
            'price' => 'bail|required|numeric',
            'image' => 'bail|nullable|image',
        ]);

        $product->fill( $req->only(['title','price','slug','description']) );
        if($req->image) {
            $image = $req->file('image');
            $product->image = Product::uploadImage($image);
        }

        if($product->save()) {
            return response()->json([
                'message' => $req->title.' has been updated successfully.',
                'data' => new ProductResource($product)
            ]);
        }
        
        return response()->json(['message' => 'Something is wrong here...'], 501);
    }

    public function destroy(Product $product)
    {
        if($product->delete())
            return response()->json([
                'message' => $product->title.' has been deleted successfully.',
                'data' => new ProductResource($product)
            ]);
        
        return response()->json(['message' => 'Something is wrong here...'], 501);
    }

}
