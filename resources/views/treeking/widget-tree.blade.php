
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

                <div class="row">
                    <div class="col-1">
                        <div class="git-icon">
                            @if($sprig->originSprig)
                                {{--origin--}}
                                <span class="icon-node-top"></span>
                                <span class="icon-node-bottom"></span>
                                <span class="icon-node-graft"></span>
                                <span class="icon-circle"></span>

                            @elseif(optional($sprig->insertSprigs)->count())

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
                        asd
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

    </div>
</div>

@push('styles')
<style>




    .git-icon {
        position: relative;

        width: 32px;
        height: 32px;

        /*background: darkseagreen;*/
    }

    .git-content {
        line-height: 32px;
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