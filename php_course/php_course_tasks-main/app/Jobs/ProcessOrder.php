<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dd($this->user);

        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        if (!count($cartItems)) {
//            return response()->json([
//                'status' => 200,
//                'message' => 'You have not any products on your cart.'
//            ]);
        }

        $failed = $cartItems->map(function ($item) {
            if ($item->product->count < $item->count) {
                $prodCount = $item->product->count;
                $prodId = $item->product->id;
                return "Product by id=$prodId available count for product item $prodCount, but you want to buy $item->count.";
            }
        });

        if ($failed[0] !== null) {
//            return response()->json([
//                'status' => 200,
//                'failed' => $failed
//            ]);
        }

        $subtotal = $cartItems->map(function ($item) {
            return $item->product->price * $item->count;
        })->sum();

        try {
            DB::beginTransaction();

            $products = $cartItems->map(function ($item) {
                $item->product->update([
                    'count' => $item->product->count - $item->count
                ]);
                $item->delete();
                return [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'count' => $item->count,
                    'price' => $item->product->price
                ];
            });

            Order::create([
                'user_id' => auth()->id(),
                'products' => $products,
                'subtotal' => $subtotal
            ]);

            DB::commit();
//            return response()->json([
//                'status' => 200,
//                'CheckOut' => 'Thank you for shopping'
//            ]);

        } catch (\Exception $e) {
            DB::rollBack();
//            return response()->json([
//                'status' => 400,
//                'message' => 'Something wrong.'
//            ]);
        }
    }
}
