<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Product;
use App\Seller;
use App\Traits\ApiResponser;
use App\Transaction;
use App\Transformers\TransactionTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, Seller $seller, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id)
        {
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }

        if (!$buyer->isVerified())
        {
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        if (!$product->seller->isVerified())
        {
            return $this->errorResponse('The seller must be a verified user', 409);
        }

        if (!$product->isAvailable())
        {
            return $this->errorResponse('The product is not available', 409);
        }

        if ($product->quantity < $request->quantity)
        {
            return $this->errorResponse('The does not have enough units for this transaction', 409);
        }

        return DB::transaction(function() use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);

            return $this->showOne($transaction, 201);

        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
