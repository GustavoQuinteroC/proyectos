@props(['name', 'type' => 'text', 'label', 'value' => '', 'icon' => ''])

<div class="mb-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="{{ $icon }}"></i></span>
        </div>
        <input type="{{ $type }}" class="form-control" name="{{ $name }}" placeholder="{{ $label }}" value="{{ $value }}">
    </div>
    @error($name)
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
