
{{--Heigit 最大化支援--}}
@php($doExpand = isset($expand) ? $expand : false)

<div class="card card-body margin{{ out_if_true($doExpand, ' expand-height') }}">
    {{ $slot }}
</div>
