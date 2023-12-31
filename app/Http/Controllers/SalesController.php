<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Invoice;
use PDF;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::where('status', 1)->get();
        $taskStatusColors = [
            'Pending' => '#FFC0C0',
            'Paid' => '#C0FFC0',
        ];
        return view('sales.index', compact('sales','taskStatusColors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sales = Sale::all();
        // $products = Product::pluck('name', 'id');
        $products = Product::pluck('name', 'id')->map(function ($productName, $productId) {
            $product = Product::find($productId);
            $productName .= ' (Stock: ' . $product->stockquantity . ')';
            return $productName;
        });

        return view('sales.create',compact('sales', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
         $username = Auth::user()->name;
     
         $product_ids = $request->input('product_id');
         $quantities = $request->input('quantity');
         $prices = $request->input('price');
         $isValid = true;
     
         // Validate quantity against stock quantity for each product
         foreach ($product_ids as $key => $product_id) {
             $product = Product::findOrFail($product_id);
     
             if ($product) {
                 $soldQuantity = $quantities[$key];
     
                 if ($product->stockquantity < $soldQuantity) {
                     // If quantity is higher than stock quantity, set isValid to false
                     $isValid = false;
                     $errorProduct = $product->name;
                     break; // Exit the loop on the first invalid product
                 }
             }
         }
     
         if (!$isValid) {
             // If any product has invalid quantity, display an error message
             return redirect()
                 ->route('sales.create')
                 ->with('error', "Quantity for product '$errorProduct' is higher than available stock.");
         }
     
         // If all quantities are valid, proceed to create the sale
         $sale = new Sale();
         $sale->customername = $request->input('customername');
         $sale->paymentmode = $request->input('paymentmode');
         $sale->paymentstatus = $request->input('paymentstatus');
         $sale->date = $request->input('date');
         $sale->status = 1;
         $sale->createdby = $username;
         $sale->updatedby = "";
         $sale->deletedby = "";
         $sale->save();
     
         foreach ($product_ids as $key => $product_id) {
             $product = Product::findOrFail($product_id);
     
             if ($product) {
                 $soldQuantity = $quantities[$key];
     
                 // Attach the product to the sale with quantity and price
                 $sale->products()->attach([$product_id], [
                     'quantity' => $soldQuantity,
                     'price' => $prices[$key]
                 ]);
     
                 // Update the stock quantity of the product
                 $product->stockquantity -= $soldQuantity;
                 $product->save();
             }
         }
     
         // Calculate and set the total price for the sale
         $totalSalePrice = $sale->products->sum(function ($product) {
             return $product->pivot->quantity * $product->pivot->price;
         });
         $sale->totalprice = $totalSalePrice;
         $sale->save();
     
         // Generate an invoice for the sale (similar to your previous code)
         $invoice = new Invoice();
         $invoice->sale_id = $sale->id;
         $invoice->invoicenumber = 'INV-' . date('Ymd') . '-' . $sale->id;
         $invoice->totalprice = $totalSalePrice;
         $invoice->date = $request->input('date');
         $invoice->status = 1;
         $invoice->createdby = $username;
         $invoice->updatedby = "";
         $invoice->deletedby = "";
         $invoice->save();
     
         Session::flash('successcode', 'success');
         return redirect()->route('sales.index')->with('success', 'Sales added successfully with a single invoice.');
     }
     
          
      



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::findOrFail($id);
       
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $username = Auth::user()->name;

        $sale = Sale::findOrFail($id);
        $sale->deletedby = $username;
        $sale->save();

        $sale->delete();

        Session::flash('successcode','warning');
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function updatePaymentStatus(Sale $sale)
    {
        try {
            if ($sale->paymentstatus == 'Pending') {
                $sale->paymentstatus = 'Paid';
            } else {
                $sale->paymentstatus = 'Pending';
            }
            $sale->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function downloadSalesPDF($saleId)
    {
        $sale = Sale::findOrFail($saleId);

        // Generate the PDF using the view and data
        $pdf = PDF::loadView('sales/sales_pdf', compact('sale'));

        // Download the PDF with a custom name
        return $pdf->download('sale_' . $sale->id . '.pdf');
}



    
}
