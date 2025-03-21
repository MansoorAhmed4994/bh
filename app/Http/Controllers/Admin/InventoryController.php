<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Riders;
use App\Models\Client\Customers;
use App\Models\Inventory;
use App\Models\remaining_inventories; 
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


//Traits
use App\Traits\MNPTraits;
use App\Traits\TraxTraits;
use App\Traits\ManualOrderTraits;
use App\Traits\InventoryTraits;

use App\Models\Category;
use App\Models\Products;
use App\Models\Order_details;

class InventoryController extends Controller
{
    
    public function __construct(Request $request)
    { 
        
        // dd('no admin');
        // $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ManualOrderTraits;
    use MNPTraits;
    use TraxTraits;
    use InventoryTraits;
    public function OrderFieldList()
    {
        return array('manual_orders.consignment_id','manual_orders.id','manual_orders.customers_id','manual_orders.description','manual_orders.receiver_number','customers.first_name','manual_orders.reciever_address','customers.last_name','customers.number','customers.address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at','manual_orders.status_reason'); 

    }
    
    public function index(Request $request,$status='')
    {
        $categories = Category::all();
        $query = Inventory::query();
        $query = $query->leftJoin('products', 'inventories.products_id', '=', 'products.id');
        $query = $query->leftJoin('categories', 'categories.id', '=', 'products.category_id');
        $query = $query->select(
            'inventories.id as iid',
            'products.id as pid',
            'products.sku as sku',
            'categories.name as category',
            'products.name as pname',
            'products.sale_price as psale',
            'products.weight as weight',
            'inventories.reference_id as reference_id',
            'inventories.stock_type as type',
            'inventories.qty as qty',
            'inventories.stock_status as status',
            'inventories.cost as icost',
            'inventories.sale as isale'
            );
        if($status != '')
        {
            $query = $query->where('stock_status', $status);
            // dd($status);
        }
        if(isset($request->search_product))
        {
            $search_test = $request->search_product;
            $query = $query->where(function ($query) use ($search_test) {
            $query
                ->where('products.name','like',$search_test.'%')
                ->orWhere('products.name','like','%'.$search_test.'%')
                ->orWhere('products.name','like','%'.$search_test)
                ->orWhere('products.sku','like',$search_test.'%')
                ->orWhere('products.sku','like','%'.$search_test.'%')
                ->orWhere('products.sku','like','%'.$search_test);
            });
        }
        
            $query = $query->orderBy('iid', 'ASC');
            $inventories = $query->paginate(20);
        // dd($inventories);
        return view('admin.inventory.manage_inventory')->with(['inventories'=>$inventories,'categories'=> $categories]);     
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_product($request)
    {
        $products = Products::create([
            'sku' => $request->sku,
            'category_id'=>$request->category_id,
            'name' => $request->name, 
            'weight_type' =>'mg', 
            'sale_price' => $request->sale, 
            'status' => 'active' 
            ]); 
            return $products;
    }
    
    public function create_inventory_products($sku='0',$stock_status='',$reference_id='0',$qty='0',$cost=0,$stock_type,$products=0,$sale=0,$warehouse_id=1)
    {
        
        $inventory = $products->inventory()->create([
        'sku' => $sku,
        'warehouse_id' => $warehouse_id, 
        'customer_id' => 1,
        'stock_status' => $stock_status,
        'qty' => $qty,
        'reference_id' => $reference_id,
        'stock_type' => $stock_type,
        'cost' => $cost,
        'sale' => $sale,
        'discount' => 0, 
        'status' => 'active'
        // 'products_id','warehouse_id','customer_id','stock_status','qty','reference_id','stock_type','cost','discount',
        ]);
        return $inventory;
    }
     
    public function store(Request $request)
    {
        $products = Products::where('sku',$request->sku)->first();
        $error="0";
        // dd($request->qty);
        if($products == null)
        {
            // dd($products);
            if($request->stock_status == 'in')
            {
                $products = $this->create_product($request);
                
                if($products)
                {
                    $remaining_inventory = $products->remaining_inventories()->create([ 
                        'qty' => $request->qty, 
                        'cost' => $request->cost, 
                        ]);
                          
                        
                    if($remaining_inventory)
                    {
                        $inventory =  $this->create_inventory_products($request->sku,$request->stock_status,'1',$request->qty,$request->cost,'StockAdjestment',$products,$products->sale_price,$remaining_inventory->id);
                        if($inventory != null)
                        { 
                            
                            
                            return response()->json(['messege' => 'Inventory Added Successfully','error'=>$error, 'status' => $remaining_inventory]);
                        } 
                        else
                        {
                            return response()->json(['messege' => 'Inventory not added','error'=>$error, 'status' => $inventory]);
                        }
                    }
                    else
                    {
                        return response()->json(['messege' => 'Remaining Inventory not added','error'=>$error, 'status' => $remaining_inventory]);
                    }
                    // dd($products->id);
                    
                    
                    
                }
                else
                {
                        return response()->json(['messege' => 'Product not added','error'=>$error, 'status' => $products]);
                }
            }
            else
            {
                return response()->json(['messege' => 'Product not added due to new product out','error'=>$error, 'status' => $products]);
            }
        
        }
        else
        {
            // dd('available');
            $products->sale_price = $request->sale;
            $products->save();
            
                    
                $remaining_inventory = $products->remaining_inventories()->create([ 
                'qty' => $request->qty, 
                'cost' => $request->cost, 
                ]);
                // dd($remaining_inventory);
                if($remaining_inventory)
                {
                    $inventory =  $this->create_inventory_products($request->sku,$request->stock_status,'1',$request->qty,$request->cost,'StockAdjestment',$products,$products->sale_price,$remaining_inventory->id);
            
            
                    if($inventory)
                    {
                        
                        return response()->json(['messege' => 'Inventory Added Successfully','error'=>$error, 'status' => $remaining_inventory]);
                    } 
                    else
                    {
                        // dd($inventory);
                        return response()->json(['messege' => 'Inventory not added','error'=>$error, 'status' => $inventory]);
                    }
                }
                else
                {
                    return response()->json(['messege' => 'Remaining Inventory not added','error'=>$error, 'status' => $remaining_inventory]);
                }
                
        } 
    } 
    
    public function getproduct(Request $request)
    {
        // dd($request);
        // dd($request->sku_number);
        $error=0;
        $products = Products::where('sku',$request->sku_number)->first();
        // dd($products);
        if($products)
        {
            // $remaining_inventory = $products->remaining_inventories->where('qty','>','0')->orderBy('remaining_inventories.id','asc')->first(); 
            $remaining_inventory = $products->remaining_inventories()->where('qty','>','0')->orderBy('id','ASC')->first();
            // dd($remaining_inventory);
            if($remaining_inventory)
            {
                // dd($products->remaining_inventories->where('qty','>','0'));
                $inventory = $this->create_inventory_products($request->sku_number,'out',$request->order_id,1,$remaining_inventory->cost,'Order',$products,$products->sale_price,$remaining_inventory->id);
                $remaining_inventory->qty = $remaining_inventory->qty-1;
                $remaining_inventory->save();
                
                // $inventory = Inventory::where(['reference_id'=>$request->order_id,'stock_status' => 'out'])->get();
                $inventory = Inventory::leftJoin('products', 'inventories.products_id', '=', 'products.id')
                ->select('inventories.id as id','products.sku as sku','products.name as name','inventories.sale as sale')
                ->where(['inventories.reference_id'=>$request->order_id,'inventories.stock_status' => 'out'])->get();
                
                // dd($inventory);
                // dd('w');
                $price = $this->updateorderprice($request->order_id);
                return response()->json(['messege' => 'Product added', 'error'=>$error,'inventory' => $inventory,'price'=>$price]);
            }
            else
            {
                $error=1;
                return response()->json(['messege' => 'item quantity Not available','error'=>$error]);
            }
        } 
        else
        {
            $error=1;
            return response()->json(['messege' => 'Product Not found','error'=>$error]);
        }
         
        // dd($products);
    }
    
    public function deletcustomerproduct($inventory_id)
    {
        // dd($inventory_id);
        
        $error=0;
        $inventory = Inventory::find($inventory_id);
        // dd(ManualOders::find($inventory->reference_id);
        $ManualOrders = ManualOrders::find($inventory->reference_id)->first();
        // dd($check_duplicate_permission->status);
        $check_duplicate_permission = true;
        if($ManualOrders->status == 'dipatched')
        {
            if(Auth::guard('admin')->check())
            { 
                $check_duplicate_permission = true;
            }
            else
            {
                $check_duplicate_permission = false;
                return response()->json(['messege' => 'Only Admin can delete this product cause you dont have access','error'=>$error,'price'=>$price]);
            }
            
        }
        if($check_duplicate_permission == true)
        {
            
        
            // dd($inventory);
            $product_id = $inventory->products_id;
            $ds = $inventory->destroy($inventory_id);
            
            // $remaining_inventory = $inventory->products->first()->remaining_inventories()->where('qty','>=','0')->orderBy('id','ASC')->first();
                // dd($remaining_inventory);
                // if($remaining_inventory)
                // {
                    // dd($remaining_inventory);
                    //create inventory in
                    // $inventory_create = $this->create_inventory_products($inventory->Products->first()->sku,'in',$inventory->reference_id,1,$inventory->cost,'Order',$inventory->products->first(),$inventory->products->first()->sale_price,$remaining_inventory->id);
                    
                    //update remaining inventory
                    $remaining_inventory = remaining_inventories::where('products_id',$product_id)->where('id','=',$inventory->warehouse_id)->first();
                    $remaining_inventory->qty = $remaining_inventory->qty+1;
                    $remaining_inventory->save(); 
                    
                    //Get updated price
                    $price = $this->updateorderprice($inventory->reference_id);
                    return response()->json(['messege' => 'Product delete successfully','success'=>$error,'price'=>$price]);
                // }
            // dd($ds); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    { 
        // dd($inventory);
        $categories = Category::all();
        return view('admin.inventory.edit')->with(['inventory'=>$inventory,'categories'=> $categories]);
        
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        // dd($inventory->warehouse_id);
        $old_qty = 0;
        $old_qty = ($request->qty)-($inventory->qty);
        // if($inventory->qty > $request->qty)
        // {
        //     $old_qty = ($inventory->qty)-($request->qty);
        // }
        // else
        // {
        //     $old_qty = ($request->qty)-($inventory->qty);
        // }
        // dd($old_qty);
        $remaining_inventory = remaining_inventories::where('id','=',$inventory->warehouse_id)->first();
        // dd($remaining_inventory);
        $remaining_inventory->qty = $remaining_inventory->qty+$old_qty;
        $remaining_inventory->save(); 
        $products_update = $inventory->products->update([
            'sku' => $request->sku,
            'slug'=>$request->slug,
            'category_id'=>$request->category_id,
            'name' => $request->product_name, 
            'weight' => $request->weight, 
            'weight_type' =>$request->weight_type, 
            'sale_price' => $request->sale_price, 
            'discount_price' => $request->discount_price,
            'status' => 'active'
        ]); 
            // dd($inventory->products()->first()->id);
            // $products_update = $inventory->products()->first(); 
            // $products_update->sku = $request->sku;
            // $products_update->slug= $request->slug;
            // $products_update->category_id= $request->category_id;
            // $products_update->name = $request->product_name; 
            // $products_update->weight = $request->weight;
            // $products_update->weight_type =$request->weight_type; 
            // $products_update->sale_price = $request->sale_price;
            // $products_update->discount_price = $request->discount_price;
            // $products_update->updated_by = '1';
            // $products_update->save();
        
        $inventory_update = $inventory->update([
        'sku' => $request->sku,
        'warehouse_id' => '1', 
        'customer_id' => $request->customer_id,
        'reference_id' => $request->reference_id,
        'stock_type' => $request->stock_type,
        'cost' => $request->inventory_cost,
        'sale' => $request->inventory_sale,
        'discount' => $request->discount_price,
        'qty' => $request->qty,
        'stock_status' => $request->stock_status,
        'status' => 'active'
        // 'products_id','warehouse_id','customer_id','stock_status','qty','reference_id','stock_type','cost','discount',
        ]);
        
        if($products_update == true)
        {
        
            if($inventory_update == true)
            {
                return redirect()->route('inventory.edit',$inventory)->with('success', 'Product updated successfully');
            }
            else
            {
                return redirect()->route('inventory.edit',$inventory)->with('error', 'Inventory not updated please contact administrator');
            }
            
        }
        else
        {
            return redirect()->route('inventory.edit',$inventory)->with('error', 'Product not update please contact administrator');
        }
        
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
        //
    }

    public function pos()
    {
        return view('admin.inventory.return_inventory');
    }
}
