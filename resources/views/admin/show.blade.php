@extends('admin.layoutAdmin.main')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-default ">
      <div class="card-header card-header-border-bottom mb-3">
        <h2>Show user add product</h2>
      </div>
      <div class="row">
        @foreach ($dataUser as $user)
        <div class="col-md-6 mb-1">
          <div class="card">
            <div class="text-center">
              <h5 class="card-title">{{ $user->name }}</h5>
              <h6 class="card-subtitle mb-2 text-body-secondary">{{ $user->email }}</h6>
            </div>
            <hr>
            @php
              $no = 1
            @endphp 
            <div class="card-body">
              <h5 class="text-center mb-2">Detail product</h5>
              @forelse ($user->product as $product)
                @if ($product->parent_id != NULL)
                  <div class="">
                    <h5 style="text-decoration: underline;">Products {{ $no++ }}</h5>
                    <h6>Nama = {{ $product->name }}</h6>
                    <h6>Slug = {{ $product->slug }}</h6>
                    <h6>Sku = {{ $product->sku }}</h6>
                    <h6>Harga = {{ $product->price }}</h6>
                    <h6>Berat = {{ $product->weight }} kg</h6>
                    <h6>Deskripsi = {{ $product->description }}</h6>
                    <h6>Stok = {{$product->productInventory->stok}}</h6>
                    <h6 class="font-italic" style=" margin-top: 10px"><span style="text-decoration: underline;">Category</span> = {{ $product->productCategory->category->name }}</h6>
                    <h6 class="font-italic" style="text-decoration: underline; margin-top: 10px">Attribute Product</h6>
                    @foreach ($product->productAttributeValues as $attributeValue)
                      @if ($product->parent_id != NULL)
                      <h6>- {{ $attributeValue->attribute->name }} = {{ $attributeValue->text_value }}</h6>
                      @endif
                    @endforeach
                    <h6 class="font-italic" style="text-decoration: underline; margin-top: 10px">Gambar Product</h6>
                    <div class="row"> 
                      @foreach ($product->productImages as $img)
                        <div class="col-md-4">
                          <img src="{{ asset('storage/'.$img->path) }}" class="card-img-top" alt="..." style="width:150px">
                        </div>
                      @endforeach                  
                    </div>
                  </div>
                  <hr>
                @endif
              @empty
                <h6>Not record</h6>
              @endforelse
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection