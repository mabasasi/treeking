
{{--Heigit 最大化支援--}}
@php($doExpand = isset($expand) ? $expand : false)

<div class="card margin{{ out_if_true($doExpand, ' expand-height') }}">
    @isset($header)
        <div class="card-header">
            {{ $header }}
        </div>
    @endisset

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
