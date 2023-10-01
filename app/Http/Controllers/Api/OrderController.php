<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function viewOrders()
    {
        $userId = auth()->id();

        $orders = Order::where('user_id', $userId)->get();

        return OrderResource::collection($orders);
    }

    public function createOrder(Request $request)
    {

        $cart_id = $request->input('cart_id');

        $cart = Cart::find($cart_id);

        if (!$cart) {
            return response()->json(['message' => 'Корзина не найдена'], 404);
        }

        $user = Auth::user();

        $order = new Order([
            'cart_id' => $cart_id,
            'user_id' => $user->id,

        ]);

        $order->save();

        return new OrderResource($order);
    }


}
