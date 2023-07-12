@php
  $data = !empty($data) ? 'Edit' : 'Add';
@endphp

<div class="card card-default">
  <div class="card-header card-header-border-bottom">
    <h3>{{ $data }} Option</h3>
  </div>
  <div class="card-body">
    @if (!empty($attributeOption))
      <form method="POST" action="{{ url('/attributes/options', $attributeOption->id) }}">
      @method('PUT')
      <input type="hidden" name="id">
    @else
      <form method="POST" action="{{ url('/attributes/options', $attribute->id) }}" enctype="multipart/form-data">
    @endif
    @csrf
    <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" value="{{ $attributeOption->name ?? '' }}">
      @error('name')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-footer pt-5 border-top">
      <button type="submit" class="btn btn-primary btn-default">Save</button>
      <a href="{{ url('attributes/') }}" class="btn btn-secondary btn-default">Back</a>
    </div>
    </form>
  </div>
</div> 