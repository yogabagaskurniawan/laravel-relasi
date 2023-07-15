@extends('admin.layoutAdmin.main')

@section('content')
@php
    $formTitle = !empty($product) ? 'Update' : 'New'    
@endphp

<div class="row">
  <div class="col-lg-3">
    @include('admin.products.layout_product_menus.main')
  </div>
  <div class="col-lg-9">
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h2>{{ $formTitle }} Product</h2>
      </div>
      <div class="card-body">
        @if (!empty($product))
          <form method="POST" action="{{ url('products/' . $product->id) }}">
            @method('PUT')
            <input type="hidden" name="id" value="{{ $product->id }}">
        @else
          <form method="POST" action="{{ url('products') }}">
        @endif
          @csrf
          <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" placeholder="sku" value="{{ !empty($product) ? $product->sku : '' }}">
            @error('sku')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{ !empty($product) ? $product->name : '' }}">
            @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          @if (empty($product))
            <div class="configurable-attributes">
              <p class="text-primary mt-4">Configurable Attributes</p>
              <hr/>
              @foreach ($attribute as $attribute)
                  <div class="form-group">
                      <label for="{{ $attribute->code }}">{{ $attribute->name }}</label>
                      @foreach ($attribute->attributeOptions as $option)
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="{{ $attribute->code }}[]" value="{{ $option->id }}" id="{{ $attribute->code }}_{{ $option->id }}">
                              <label class="form-check-label" for="{{ $attribute->code }}_{{ $option->id }}">
                                  {{ $option->name }}
                              </label>
                          </div>
                      @endforeach
                  </div>
              @endforeach
            </div> 
          @endif                       
  
          @if ($product)
            {{-- @include('admin.products.configurable') --}}
            @if ($product->parent_id == NULL)
              @include('admin.products.configurable')
            @else
              @include('admin.products.simple')                            
            @endif
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" placeholder="description">{{ !empty($product) ? $product->description : '' }}</textarea>
            </div>
          @endif
          <div class="form-footer pt-5 border-top">
            <button type="submit" class="btn btn-primary btn-default">Save</button>
            <a href="{{ url('products') }}" class="btn btn-secondary btn-default">Back</a>
          </div>
        </form>
      </div>
    </div>  
  </div>
</div>
@endsection