<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function createProducts(Request $request)

    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->save();

        $productResource = new ProductResource($product);

        return response()->json(['message' => 'Продукт успешно добавлен', 'product' => $productResource]);
    }




    public function updateProducts(Request $request)
    {
        // Валидация данных
        $this->validate($request, [
            'id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $product = Product::find($request->input('id'));

        if (!$product) {
            return response()->json(['message' => 'Продукт не найден.'], 404);
        }

        $product->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        $productResource = new ProductResource($product);

        return response()->json(['message' => 'Продукт успешно обновлен', 'product' => $productResource]);
    }

    public function destroyProducts(Request $request)
    {

        $productIds = $request->input('product_ids');

        if ($productIds !== null) {
            try {
                Product::whereIn('id', $productIds)->delete();

                return response()->json(['message' => 'Продукты успешно удалены']);
            } catch (\Exception $e) {

                return response()->json(['message' => 'Произошла ошибка при удалении продуктов'], 500);
            }
        } else {

            return response()->json(['message' => 'Не указаны ID продуктов для удаления'], 400);
        }
    }

}
