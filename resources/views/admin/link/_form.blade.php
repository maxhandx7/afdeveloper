<div class="form-group">
    <label for="title">Titulo</label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
        placeholder="Titulo del enlace" required>
    @error('title')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>


<div class="form-group">
    <label for="link">Link</label>
    <input type="url" name="link" id="link" class="form-control @error('link') is-invalid @enderror"
        placeholder="https://web.com/" required>
    @error('link')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>



<div class="form-group">
    <label for="description">Descripcion corta</label>
    <textarea id="description" class="form-control @error('description') is-invalid @enderror"
        placeholder="Descripcion corta" name="description" rows="3"></textarea>
    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group">
    <label for="long_description">Descripción larga</label>
    <textarea class="form-control" name="long_description" id="summernoteExample"
        rows="10"></textarea>
</div>



<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Imágen</h4>
                <input type="file" name="image" class="dropify"  />
            </div>
        </div>
    </div>
</div>