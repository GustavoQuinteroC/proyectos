@props(['name', 'label', 'placeholder' => '', 'value' => '', 'icon' => ''])

<div class="mb-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="{{ $icon }}"></i></span>
        </div>
        <textarea class="form-control" name="{{ $name }}" placeholder="{{ $placeholder }}">{{ $value }}</textarea>
    </div>
    @error($name)
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
