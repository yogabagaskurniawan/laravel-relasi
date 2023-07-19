<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\Attribute;
use App\ProductImage;
use App\AttributeOption;
use App\Category;
use App\ProductInventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ProductAttributeValue;
use App\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $categories = Category::all();
        return view('admin.products.form',compact('product', 'productID', 'attribute', 'categories'));
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
            'slug' => Str::slug($request->name),
            'selected_category' => $request->selected_category
        ];
        
        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:products',
            'name' => 'required|unique:products',
            'selected_category' => 'required'
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

                // Category
                $productCategory = new ProductCategory();
                $productCategory->product_id = $newProductVariant->id;
                $productCategory->category_id = $request->selected_category;
                $productCategory->save();

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

        // Update data produk utama
        $product->fill($request->all());
        $product->slug = Str::slug($request->name);
        $product->save();

        // Update data stok produk utama
        $product->productInventory()->updateOrCreate(
            ['product_id' => $product->id],
            ['stok' => $request->stok]
        );

        if ($request->variants) {
            foreach ($request->variants as $variantParams) {
                $variantProduct = Product::find($variantParams['id']);
                $variantProduct->fill($variantParams);
                $variantProduct->save();

                $stok = $variantParams['stok'];

                $variantProduct->productInventory()->updateOrCreate(
                    ['product_id' => $variantProduct->id],
                    ['stok' => $stok]
                );
            }
        }

        return redirect('products')->with('success','Data berhasil diupdate');
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

    // ===================== Image Product ===============================
    public function images($id)
    {
        if (empty($id)){
            return redirect('products/create');
        }
        
        $product = Product::findOrFail($id);
        $productID = $product->id;
        $productImages = $product->productImages;
        
        return view('admin.products.images', compact('product', 'productID', 'productImages'));
    }

    public function add_image($id)
    {   
        if (empty($id)){
            return redirect('products');
        }
        
        $product = Product::findOrFail($id);
        $productID = $product->id;
        
        return view('admin.products.images_form', compact('product', 'productID'));
    }

    public function upload_image(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Product::findOrFail($id);

        if ($request->hasFile('path')) {
            $image = $request->file('path');
            $name = Str::slug($product->name) . '_' . time();
            $filename = $name . '.' . $image->getClientOriginalExtension();

            $folder = '/uploads/images';
            $filePath = $image->storeAs($folder, $filename, 'public');

            $data = [
                'product_id' => $product->id,
                'path' => $filePath
            ];

            $productImage = ProductImage::create($data);
            return redirect('products/' . $id . '/images')->with('success', 'Gambar berhasil ditambahkan');
        }
    }

    public function remove_image($id)
    {
        $image = ProductImage::findOrFail($id);

        // Menghapus gambar dari folder penyimpanan
        Storage::disk('public')->delete($image->path);

        $image->delete();

        return redirect('products/'.$image->product->id.'/images')->with('success','Data berhasil dihapus');
    }
    
    // show user add product
    public function show_user()
    {
        $dataUser = User::get();

        return view('admin.show', compact('dataUser'));
    }
}
