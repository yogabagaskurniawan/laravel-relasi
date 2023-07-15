<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" class="form-control" placeholder="price" value="{{ !empty($product) ? $product->price : '' }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="weight">Weight</label>
            <input type="text" name="weight" class="form-control" placeholder="weight" value="{{ !empty($product) ? $product->weight : '' }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="text" name="stok" class="form-control" placeholder="stok" value="{{ !empty($product->productInventory) ? $product->productInventory->stok : '' }}">
        </div>
    </div>
</div>
