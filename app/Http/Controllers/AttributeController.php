<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::orderBy('name', 'asc')->paginate(10);
        return view('admin.attributes.index', ['attributes' => $attributes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $attribute = null;
        return view('admin.attributes.form', compact('attribute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'code' => $request->code,
            'name' => $request->name,
        ];
    
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:attributes',
            'name' => 'required|unique:attributes',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        Attribute::create($data);
        return redirect('/attributes')->with('success','Data berhasil ditambahkan');
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
        $attribute = Attribute::findOrFail($id);
        return view('admin.attributes.form', compact('attribute'));
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
        $data = [
            'id' => $request->id,
            'code' => $request->code,
            'name' => $request->name,
        ];

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:attributes',
            'name' => 'required|unique:attributes',
        ]);
        
        Attribute::where('id', $data['id'])->update($validator->validated());
        
        return redirect('/attributes')->with('success','Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Attribute::where('id',$id)->delete();

        return back()->with('success','Data berhasil dihapus');
    }

    // ============= Mengatasi jika user mengatasi mengeklik tombol option ==============

    public function add_option($attributeID)
    {
        if(empty($attributeID)) {
            return redirect('/attributes');
        }

        $attribute = Attribute::findOrFail($attributeID);
        $data = null;

        return view('admin.attributes.options', compact('attribute', 'data'));
    }

    public function store_option(Request $request, $attributeID)
    {
        if(empty($attributeID)){
            return redirect('/attributes');
        }

        $data = [
            'name' => $request->name,
            'attribute_id' => $attributeID,
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:attribute_options',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        AttributeOption::create($data);
        return redirect('attributes/'.$attributeID.'/add-option')->with('success','Option has been saved');
    }

    public function edit_option($optionID)
    {
        $attributeOption = AttributeOption::findOrFail($optionID);
        // mengambil data dari model attribute
        $attribute = $attributeOption->attribute;
        $data = 1;

        return view('admin.attributes.options', compact('attributeOption', 'attribute', 'data'));
    }

    public function update_option(Request $request, $optionID)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:attribute_options',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $option = AttributeOption::findOrFail($optionID);
        $params = $request->except('_token');
        $option->update($params);

        return redirect('attributes/'.$option->attribute->id.'/add-option')->with('success','Data berhasil diubah');
    }

    public function remove_option($optionID)
    {
        if(empty($optionID)){
            return redirect('/attributes');
        }

        AttributeOption::where('id',$optionID)->delete();
        return back()->with('success','Data berhasil dihapus');
    }
}
