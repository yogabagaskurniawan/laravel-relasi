@extends('admin.layoutAdmin.main')

@section('content')
@php
  use Illuminate\Support\Facades\Form;
  use Illuminate\Support\HtmlString;

  $formTitle = !empty($attribute) ? 'Update' : 'New';
  $disableInput = !empty($attribute) ? true : false;
@endphp

<div class="row">
  <div class="col-lg-6">
    <div class="card card-default">
      <div class="card-header card-header-border-bottom">
        <h2>{{ $formTitle }} Attribute</h2>
      </div>
      <div class="card-body">
        @if (!empty($attribute))
        <form method="POST" action="{{ url('attributes/', $attribute->id) }}">
          @method('PUT')
          @csrf
          <input type="hidden" name="id" value="{{ $attribute->id }}">
        @else
        <form method="POST" action="{{ url('/attributes') }}">
          @csrf
        @endif
          <fieldset class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <legend class="col-form-label pt-0">General</legend>
                <div class="form-group">
                  <label for="code">Code</label>
                  <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ !empty($attribute) ? $attribute->code : '' }}" >
                  @error('code')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ !empty($attribute) ? $attribute->name : '' }}">
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
            </div>
          </fieldset>
          <div class="form-footer pt-5 border-top">
            <button type="submit" class="btn btn-primary btn-default">Save</button>
            <a href="{{ url('/attributes') }}" class="btn btn-secondary btn-default">Back</a>
          </div>
        </form>
      </div>
    </div>  
  </div>
</div>
@endsection