@extends('admin.layoutAdmin.main')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-header card-header-border-bottom">
          <h2>Products</h2>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success" role="alert">
              {{ session('success') }}
            </div>
          @endif
          <table class="table table-bordered table-stripped">
            <thead>
                <th>#</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stok</th>
                <th style="width:15%">Action</th>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>    
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        {{-- <td>{{ $product->price }}</td> --}}
                        <td>{{ number_format($product->price) }}</td>
                        <td>{{ $product->productInventory ? $product->productInventory->stok : '-' }}</td>
                        <td>
                            <a href="{{ url('products/'. $product->id .'/edit') }}" class="btn btn-warning btn-sm">edit</a>
                            <a href="{{ url('products/' . $product->id) }}" class="delete" style="display:inline-block"
                              onclick="event.preventDefault(); if (confirm('Are you sure you want to remove this item?')) { document.getElementById('delete-form-{{ $product->id }}').submit(); }">
                              <button type="button" class="btn btn-danger btn-sm">Remove</button>
                            </a>
                            <form id="delete-form-{{ $product->id }}" action="{{ url('products/' . $product->id) }}" method="POST" style="display: none;">
                                @method('DELETE')
                                @csrf
                            </form>                                                   
                          </td>
                    </tr>
                @endforeach
                @if ($products->isEmpty())
                  <tr>
                      <td colspan="6">No records found</td>
                      <td></td>
                  </tr>
                @endif
            </tbody>
          </table>
          {{ $products->links() }}
        </div>
        <div class="card-footer text-right">
          <a href="{{ url('products/create') }}" class="btn btn-primary">Add New</a>
        </div>
      </div>
    </div>
  </div>
@endsection
