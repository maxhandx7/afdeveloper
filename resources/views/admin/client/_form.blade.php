<div class="form-group">
    <label for="name">Nombre</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
        placeholder="Nombre del enlace" required>
    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>






<div class="form-group">
    <label for="description">Descripcion</label>
    <textarea id="description" class="form-control @error('description') is-invalid @enderror"
        placeholder="Descripcion" name="description" rows="3"></textarea>
    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-name">Imágen</h4>
                <input type="file" name="image" class="dropify"  />
            </div>
        </div>
    </div>
</div>