<?php

namespace App\Http\Controllers;

use App\Product;
use App\Attribute;
use App\AttributeOption;
use App\ProductInventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ProductAttributeValue;
use App\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\Attributes\Node\Attributes;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('name', 'asc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = null;
        $productID = 0;
        $attribute = Attribute::all();
        // $categoryIDs = [];
        return view('admin.products.form',compact('product', 'productID', 'attribute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function convertVariantAsName($variant)
    {
        $variantName = '';
        foreach (array_keys($variant) as $key => $code) {
            $attributeOptionID = $variant[$code];
            $attributeOption = AttributeOption::find($attributeOptionID);
            if($attributeOption){
                $variantName .='-'.$attributeOption->name;
            }
        }
        return $variantName;
    }
    
    private function generateAttributeCombinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property=>$property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    // table product attribute value
    private function saveProductAttributeValues($product, $variant)
    {
        foreach (array_values($variant) as $attributeOptionID) {
            $attributeOption = AttributeOption::find($attributeOptionID);
            $attributeValueParams = [
                'product_id' => $product->id,
                'attribute_id' => $attributeOption->attribute_id,
                'text_value' => $attributeOption->name,
            ];

            ProductAttributeValue::create($attributeValueParams);
        }
    }
    
    public function store(Request $request)
    {
        $data = [
            'sku' => $request->sku,
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'slug' => Str::slug($request->name)
        ];
        
        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:products',
            'name' => 'required|unique:products',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Product::create($data);
        
        // mengambil semua combinasi dari multiple array
        $attributes = Attribute::get();
        $variantAttributes = [];
        foreach ($attributes as $attribute) {
            $checkboxValue = $request->input($attribute->code);

            if ($checkboxValue !== null) {
                $variantAttributes[$attribute->code] = $checkboxValue;
            }
        }
        // dd($variantAttributes);
        $variants = $this->generateAttributeCombinations($variantAttributes);
        // dd($variants);
        if ($variants) {
            foreach ($variants as $variant) {
                $variantParams = [
                    'parent_id' => $product->id,
                    'user_id' => auth()->user()->id,
                    'sku' => $product->sku . '-' . implode('-', array_values($variant)),
                    'name' => $product->name . $this->convertVariantAsName($variant),
                ];
                // dd($variantParams);
                $newProductVariant = Product::create($variantParams);
                // Setelah menciptakan produk variasi baru, perbarui slug-nya
                $newProductVariant->slug = Str::slug($newProductVariant->name);
                $newProductVariant->save();
                $this->saveProductAttributeValues($newProductVariant, $variant);
            }
        }
        return redirect('products/'.$product->id.'/edit');
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
    public function edit($id)
    {
        if(empty($id)){
            return redirect('/products/create');
        }

        $product = Product::findOrFail($id);
        $productID = $product->id;

        // dd($this->data);
        return view('admin.products.form', compact('product', 'productID'));
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
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:products,sku,' . $id . ',id',
            'name' => 'required|unique:products,name,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            // 'slug' => Str::slug($request->name)
        ];

        $product->fill($request->all());
        // Ambil nama baru dari permintaan
        $newName = $request->name;
        // Hasilkan slug baru dari nama baru
        $newSlug = Str::slug($newName);
        // Perbarui nilai slug pada model produk
        $product->slug = $newSlug;
        $product->save();

        // dd($request->variants);
        if ($request->variants) {
            foreach ($request->variants as $productParams) {
                $product = Product::find($productParams['id']);
                $product->fill($productParams);
                $product->save();

                // Mengambil nilai stok dari input pengguna
                $stok = $productParams['stok'];

                // Memperbarui atau membuat data di tabel product_inventories
                $variantParams = [
                    'product_id' => $product->id,
                    'stok' => $stok, // Menggunakan nilai stok dari input pengguna
                    'updated_at' => now(),
                    'created_at' => now(),
                ];
                ProductInventory::updateOrCreate(['product_id' => $product->id], $variantParams);
            }
        }

        $product->update($data);

        return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where('id',$id)->delete();
        ProductInventory::where('product_id',$id)->delete();
        ProductAttributeValue::where('product_id',$id)->delete();
        ProductImage::where('product_id',$id)->delete();

        return back()->with('success','Data berhasil dihapus');
    }
}
