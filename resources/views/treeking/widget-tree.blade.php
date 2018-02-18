
<div class="row">
    <div class="col">

        <div id="gitGraph" class="git-graph">
            @foreach($sprigs as $sprig)

                @component('parts.general-card-component', ['class' => 'git-node'])
                    @slot('header')
                        {{ $sprig->name ?? 'no' }}
                    @endSlot
                        {{ $sprig->currentLeave->content }}
                @endcomponent

                <div class="row mb-2">
                    <div class="col-1">
                        <div class="git-icon">
                            @if($sprig->has_origin)
                                {{--origin--}}
                                <span class="icon-node-top"></span>
                                <span class="icon-node-bottom"></span>
                                <span class="icon-node-graft"></span>
                                <span class="icon-circle"></span>

                            @elseif($sprig->has_insert)

                                {{--insert--}}
                                <span class="icon-node-top"></span>
                                <span class="icon-node-bottom"></span>
                                <span class="icon-node-ramify"></span>
                                <span class="icon-circle"></span>
                            @elseif($sprig->is_tail)

                                {{--tail--}}
                                <span class="icon-node-top"></span>
                                <span class="icon-circle"></span>
                            @else

                                {{--default--}}
                                <span class="icon-node-top"></span>
                                <span class="icon-node-bottom"></span>
                            @endIf
                        </div>
                    </div>
                    <div class="col">
                        <div class="git-content">
                            @if($sprig->originSprig)
                                @php ($origin = $sprig->originSprig)
                                <a class="btn btn-under-animation mr-1" href="{{ route('treeking.index', ['branch_id' => $origin->branch_id, 'sprig_id' => $sprig->id]) }}">
                                    <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;{{ optional($origin->branch)->name ?? 'unknown' }} から統合
                                </a>
                                <small>
                                    {{ $origin->name }}
                                </small>
                            @elseif(optional($sprig->insertSprigs)->count())
                                @php ($insert = $sprig->insertSprigs->first())
                                <a class="btn btn-under-animation mr-1" href="{{ route('treeking.index', ['branch_id' => $insert->branch_id, 'sprig_id' => $insert->id]) }}">
                                    <i class="fas fa-angle-double-left"></i>&nbsp;&nbsp;{{ optional($insert->branch)->name ?? 'unknown' }} へ分岐
                                </a>
                                <small>
                                    {{ $insert->name }}
                                </small>
                            @elseif($sprig->is_tail)
                                <small class="btn">
                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;{{ optional($sprig->branch)->created_at }} に作成
                                </small>
                            @endIf
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

    </div>
</div>

@push('styles')
<style>


    .btn-under-animation {
        position: relative;
        display: inline-block;

        color: black;
        text-decoration: none;
    }

    .btn-under-animation:before {
        position: absolute;
        top: 28px;
        left: 5%;
        content: "";
        display: inline-block;
        width: 0;
        height: 2px;
        background: #2D89EF;
        transition: 0.2s;
    }

    .btn-under-animation:hover:before {
        width: 90%;
        transition: 0.5s;
    }







    /******************************************/
    /* git tree のアイコンたち.*/
    /******************************************/
    .git-icon {
        position: relative;

        width: 32px;
        height: 32px;

        /*background: darkseagreen;*/
    }

    .git-content {
        width: 100%;
        line-height: 32px;

        background-color: #F8F9FA;
    }


    .icon-circle {
        position: absolute;

        top: 12px;
        left: 12px;
        width: 10px;
        height: 10px;

        border-radius: 50%;
        background: #d1e5fb;
        box-shadow: 0 0 0 2px #2d89ef;
    }

    .icon-node-top {
        position: absolute;

        top: -8px;
        left: 16px;
        width: 4px;
        height: 24px;

        background-color: #2d89ef;
    }

    .icon-node-bottom {
        position: absolute;

        top: 16px;
        left: 16px;
        width: 4px;
        height: 30px;

        background-color: #2d89ef;
    }

    .icon-node-ramify {
        position: absolute;

        top: 4px;
        left: -18px;
        width: 32px;
        height: 16px;

        border-radius: 50px;
        background: rgba(0, 0, 0, 0);
        box-shadow: 0 0 0 4px #2d89ef;
        clip: rect(-4px, 36px, 7px, 18px);
    }

    .icon-node-graft {
        position: absolute;

        top: 20px;
        left: 5px;
        width: 16px;
        height: 32px;

        border-radius: 50px;
        background: rgba(0, 0, 0, 0);
        box-shadow: 0 0 0 4px #2d89ef;
        clip: rect(-33px, 8px, 13px, -5px);
    }


</style>
@endpush

@push('scripts')
@endpush