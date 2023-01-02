<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Store;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $customers = Customers::get();
        $units = Unit::get();
        $code = $this->getSerialNumber();
        $stores = Store::get();
        return view('order', compact('stores', 'customers', 'code', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'customer_id' => 'required',
            "quantity.*"  => "required",
            "price.*"  => "required",
        ]);

        DB::beginTransaction();
        try{
            $order = new Order();
            $order->code = $request->code;
            $order->store_id = $request->store_id;
            $order->customer_id = $request->customer_id;
            $order->total_price = $request->subTotal;
            $order->save();

            for ($i = 0; $i < count($request->product_id); $i++) {
                $orderItem = new OrderDetails();
                $orderItem->order_id = $order->id;
                $orderItem->unit_id = $request['unit'][$i];
                $orderItem->item_id = $request['product_id'][$i];
                $orderItem->quantity = $request['quantity'][$i];
                $orderItem->price = $request['price'][$i];
                $orderItem->save();
            }

            DB::commit();
        }catch (\Exception $e ){
            dd($e->getMessage());
            DB::rollBack();
        }
        return redirect()->back();

        dd($request->all());
    }

    public function getSerialNumber(): string
    {
        $latestOrder = Order::query()->orderByDesc('id')->first();
        return '#' . str_pad($latestOrder->id ? $latestOrder->id + 1 : 0 , 8, "0", STR_PAD_LEFT);
    }

    public function getItemStore(Request $request)
    {
        $items = Item::where('store_id', $request->id)->get();
        return response(view('ajax.item_ajax', compact('items'))->render());
    }

    public function checkQuantity(Request $request)
    {
        $item = Item::where('id', $request->product_id)->first()->quantity;

        if ($item){
            if ($request->quantity > $item){
                $msg = 'المنتج غير كافي';
                return response(view('ajax.error_ajax', compact('msg'))->render());
            }
        }
    }
}
