@props(['name', 'label', 'placeholder', 'options', 'selected'])

<div class="mb-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-transgender-alt"></i></span>
        </div>
        <select class="form-control" name="{{ $name }}">
            <option value="">{{ $placeholder }}</option>
            @foreach ($options as $key => $value)
                @php
                    $isSelected = isset($selected) && $selected == $key;
                @endphp
                <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    @error($name)
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
