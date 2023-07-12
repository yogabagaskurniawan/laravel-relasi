@extends('admin.layoutAdmin.main')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default">
        <div class="card-header card-header-border-bottom">
          <h3>Attributes</h3>
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
              <th>Code</th>
              <th>Name</th>
              <th>Action</th>
            </thead>
            <tbody>
              @forelse ($attributes as $attribute)
                <tr>    
                  <td>{{ $attribute->id }}</td>
                  <td>{{ $attribute->code }}</td>
                  <td>{{ $attribute->name }}</td>
                  <td>
                    <a href="{{ url('attributes/'. $attribute->id .'/edit') }}" class="btn btn-warning btn-sm">edit</a>
                    <a href="{{ url('attributes/'. $attribute->id .'/add-option') }}" class="btn btn-success btn-sm">options</a>
                    <a href="{{ url('attributes/'. $attribute->id) }}" class="delete" style="display:inline-block"
                      onclick="event.preventDefault(); if (confirm('Are you sure you want to remove this item?')) { document.getElementById('delete-form-{{ $attribute->id }}').submit(); }">
                      <button type="button" class="btn btn-danger btn-sm">Remove</button>
                    </a>
                    <form id="delete-form-{{ $attribute->id }}" action="{{ url('attributes/'. $attribute->id) }}" method="POST" style="display: none;">
                      @method('DELETE')
                      @csrf
                    </form>   
                  </td>                                        
                </tr>
              @empty
                <tr>
                  <td colspan="5">No records found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          {{ $attributes->links() }}
        </div>
        <div class="card-footer text-right">
          <a href="{{ url('/attributes/create') }}" class="btn btn-primary">Add New</a>
        </div>
      </div>
    </div>
  </div>
@endsection