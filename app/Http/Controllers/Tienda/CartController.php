<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\Cart\CartCollection;
use App\Http\Resources\Ecommerce\Cart\CartResource;
use App\Models\Sale\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth('api')->user();
        $carts = Cart::where("user_id",$user->id)->get();

        return response()->json(["carts" => CartCollection::make($carts)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $is_exits_cart = Cart::where("user_id",$user->id)->where("course_id",$request->course_id)->first();
        if($is_exits_cart ){
            return response()->json(["message" => 403, "message_text" => "EL CURSO YA EXISTE EN LA LISTA"]);
        }

        $request->request->add(["user_id" => $user->id]);
        $cart = Cart::create($request->all());
        return response()->json(["cart" => CartResource::make($cart)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json(["message" => 200]);
    }
}
