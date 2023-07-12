@extends('admin.layoutAdmin.main')

@section('content')
<div class="row">
  <div class="col-lg-5">
    @include('admin.attributes.option_form')
  </div>
  <div class="col-lg-7">
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h3>Options for : {{ $attribute->name }}</h3>
      </div>
      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success" role="alert">
            {{ session('success') }}
          </div>
        @endif
        <table class="table table-bordered table-stripped">
          <thead>
            <th style="width:10%">#</th>
            <th>Name</th>
            <th style="width:30%">Action</th>
          </thead>
          <tbody>
            @forelse ($attribute->attributeOptions as $option)
              <tr>    
                <td>{{ $option->id }}</td>
                <td>{{ $option->name }}</td>
                <td>
                  <a href="{{ url('attributes/options/'. $option->id .'/edit') }}" class="btn btn-warning btn-sm">edit</a>
                  <form action="{{ url('attributes/options/'. $option->id) }}" method="POST" class="delete" style="display:inline-block">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus opsi ini?')">remove</button>
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
      </div>
    </div>
  </div>
</div>
@endsection