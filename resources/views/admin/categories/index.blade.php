@extends('admin.layoutAdmin.main')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h2>Categories</h2>
      </div>
      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success" role="alert">
            {{ session('success') }}
          </div>
        @endif
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Slug</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1
            @endphp
            @forelse ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                  <a href="{{ url('categories/' . $category->id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                  <a href="{{ url('categories/' . $category->id) }}" class="delete" style="display:inline-block"
                    onclick="event.preventDefault(); if (confirm('Are you sure you want to remove this item?')) { document.getElementById('delete-form-{{ $category->id }}').submit(); }">
                    <button type="button" class="btn btn-danger btn-sm">Remove</button>
                  </a>
                  <form id="delete-form-{{ $category->id }}" action="{{ url('categories/' . $category->id) }}" method="POST" style="display: none;">
                      @method('DELETE')
                      @csrf
                  </form> 
                </td>
            </tr>
            @empty
              <tr>
                <td colspan="4">No records found</td>
                <td></td>
              </tr>
            @endforelse
          </tbody>
        </table>
        {{ $categories->links() }}
      </div>
      <div class="card-footer text-right"> 
        <a href="{{ url('categories/create') }}" class="btn btn-primary">Add new</a>
      </div>
    </div>
  </div>
</div>
@endsection