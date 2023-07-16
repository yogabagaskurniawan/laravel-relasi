@extends('admin.layoutAdmin.main')

@section('content')
<div class="row">
  <div class="col-lg-3">
    @include('admin.products.layout_product_menus.main')
  </div>
  <div class="col-lg-9">
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h2>Upload Images</h2>
      </div>
      <div class="card-body">
        <form action="{{ url('products/images/'.$product->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" name="path" id="image" class="form-control-file @error('image') is-invalid @enderror" placeholder="product image">
            @error('image')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>        
          <div class="form-footer pt-5 border-top">
            <button type="submit" class="btn btn-primary btn-default">Save</button>
            <a href="{{ url('products/'.$productID.'/images') }}" class="btn btn-secondary btn-default">Back</a>
          </div>
        </form>
      </div>                
    </div>  
  </div>
</div>
@endsection