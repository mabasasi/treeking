
{{--Laravel の Validation Error に最適化--}}
@php($hasError  = count($errors) > 0)
@php($thisError = $errors->has($name))

<div class="form-group row">
    <label for="{{ $name }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10{{ ($thisError) ? ' is-invalid' : (($hasError) ? ' is-valid' : '') }}">
         {{ $slot }}
        @if($thisError)
            <div class="invalid-feedback">
                @foreach($errors->get($name) as $message)
                    {{ $message }}
                @endforeach
            </div>
        @endif
    </div>
</div>
