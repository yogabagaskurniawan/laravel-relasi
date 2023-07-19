@extends('admin.layoutAdmin.main')

@section('content')
@php
  $formTitle = !empty($category) ? 'Update' : 'New'
@endphp

<div class="row">
  <div class="col-lg-8">
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h2>{{ $formTitle }} Category</h2>
      </div>
      <div class="card-body">
        @if (!empty($category))
          <form action="{{ route('categories.update', $category->id) }}" method="POST">
          @method('PUT')
          @csrf
          <input type="hidden" name="id" value="{{ $category->id }}">
        @else
          <form action="{{ route('categories.store') }}" method="POST">
          @csrf
        @endif
        @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="category name" value="{{ $category->name ?? '' }}">
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-footer pt-5 border-top">
          <button type="submit" class="btn btn-primary btn-default">Save</button>
          <a href="{{ url('categories') }}" class="btn btn-secondary">back</a>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection