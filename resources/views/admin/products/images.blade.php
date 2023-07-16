@extends('admin.layoutAdmin.main')

@section('content')
<div class="row">
  <div class="col-lg-3">
    @include('admin.products.layout_product_menus.main')
  </div>
  <div class="col-lg-9">
    @if (session('success'))
      <div class="alert alert-success" role="alert">
        {{ session('success') }}
      </div>
    @endif
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h2>Product Images</h2>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-stripped">
          <thead>
            <th>#</th>
            <th>Image</th>
            <th>Uploaded At</th>
            <th>Action</th>
          </thead>
          <tbody>
            @forelse ($productImages as $image)
              <tr>    
                <td>{{ $image->id }}</td>
                <td><img src="{{ asset('storage/'.$image->path) }}" style="width:150px"/></td>
                <td>{{ $image->created_at }}</td>
                <td>
                  <form action="{{ url('products/images/'. $image->id) }}" method="POST" class="delete" style="display:inline-block">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this image?')">Remove</button>
                  </form>
                </td>                                    
              </tr>
            @empty
              <tr>
                <td colspan="4">No records found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer text-right">
        <a href="{{ url('products/'.$productID.'/add-image') }}" class="btn btn-primary">Add New</a>
      </div>
    </div>  
  </div>
</div>
@endsection