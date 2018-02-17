
{{--名前がない場合を処理--}}
@php($name = isset($name) ? $name : null)

{{--Laravel の Validation Error に最適化--}}
@php($hasError  = count($errors) > 0)
@php($thisError = (!$name) ? false : $errors->has($name))

<div class="form-group{{ isset($class) ? ' '.$class : '' }}">
    @isset($label)
        <label for="{{ $name }}" class="col-form-label">{{ $label }}</label>
    @endif
    <div class="{{ ($thisError) ? ' is-invalid' : (($hasError) ? ' is-valid' : '') }}">
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
