<div class="form-group">
    <label for="description">Descripción</label>
    <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror"
        placeholder="Descripción" required>
    @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>


<div class="form-group">
    <label for="amount">Monto</label>
        <input class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" data-inputmask="'alias': 'currency'" />
    @error('amount')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group">
    <label for="date">Fecha</label>
    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" />
    @error('date')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
