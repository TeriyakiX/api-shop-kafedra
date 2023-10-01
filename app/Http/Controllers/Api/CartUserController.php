<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function viewCart()
    {
        $userId = auth()->id();


        $cartItems = Cart::where('user_id', $userId)->get();


        return CartResource::collection($cartItems);
    }


    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $userId = auth()->id();


        $existingCartItem = Cart::where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if ($existingCartItem) {

            $existingCartItem->quantity += $quantity;
            $existingCartItem->save();
        } else {

            $cartItem = new Cart([
                'product_id' => $productId,
                'user_id' => $userId,
                'quantity' => $quantity
            ]);

            $cartItem->save();
        }

        return response()->json(['message' => 'Продукт добавлен в корзину'], 200);
    }

    public function destroyFromCart(Request $request)
    {

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');


        $cartItem = Cart::where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Такого продукта нет в корзине'], 404);
        }


        if ($cartItem->quantity < $quantity) {
            return response()->json(['message' => 'Недостаточное количество продукта в корзине'], 400);
        }


        $cartItem->quantity -= $quantity;


        if ($cartItem->quantity === 0) {
            $cartItem->delete();
            return response()->json(['message' => 'Продукт удален из корзины'], 200);
        }


        $cartItem->save();


        return response()->json(['message' => 'Количество продукта уменьшено в корзине'], 200);
    }
}
