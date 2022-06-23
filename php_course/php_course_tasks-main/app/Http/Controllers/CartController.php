<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrder;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): JsonResponse
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

        return response()->json([
            'message' => 'Carts selected.',
            'cartData' => $cartItems
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request): JsonResponse
    {
        $save = new Cart;
        $save->user_id = auth()->id();
        $save->product_id = $request->product_id;
        $save->count = $request->count;
        $save->save();

        return response()->json([
            'message' => 'Cart created.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): JsonResponse
    {
        Cart::find($id)->update($request->all());

        return response()->json([
            'message' => 'Cart updated.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        Cart::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Cart deleted.'
        ], 200);
    }

    public function checkout(): JsonResponse
    {
        dispatch(
            new ProcessOrder(auth()->user())
        )
            ->onConnection('redis')
            ->onQueue('test_queue')
            ->delay(now()->addMinutes(10));
        return response()->json([
            'status' => 202,
            'message' => 'Your request accepted , you will be notified on status'
        ], JsonResponse::HTTP_ACCEPTED);
    }
}
