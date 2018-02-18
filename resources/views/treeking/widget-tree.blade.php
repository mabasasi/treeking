
<div class="row">
    <div class="col">

        <div id="node-graph" data-branch-id="{{ $branch->id ?? 0 }}" data-count="100"></div>

        <div id="gitGraph" class="git-graph">
            @if(false)
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
                                origin
                                <span class="icon-node-top"></span>
                                <span class="icon-node-bottom"></span>
                                <span class="icon-node-graft"></span>
                                <span class="icon-circle"></span>

                            @elseif($sprig->has_insert)

                                insert
                                <span class="icon-node-top"></span>
                                <span class="icon-node-bottom"></span>
                                <span class="icon-node-ramify"></span>
                                <span class="icon-circle"></span>
                            @elseif($sprig->is_tail)

                                tail
                                <span class="icon-node-top"></span>
                                <span class="icon-circle"></span>
                            @else

                                default
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
            @endif
        </div>

    </div>
</div>

@push('styles')
<style>




    #node-graph .area {
        background: lightcyan;
    }

    #node-graph .area.hide {
        display:none;
    }






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
<script>
    $(function() {
        var TREEKING_API_URL = "{!! route('treeking.get') !!}";
        var graph = $('#node-graph');
        var wait = 0;




        var last_node_id = null;
        var is_reach_last = false;
        var buffer_nodes = [];

        $(window).ready(function() {
            load_next_node();
        });


        var load_next_node = function() {
            // データ取得
            var meta = {
                branch_id:    graph.data('branch-id'),
                count:        graph.data('count'),
                last_node_id: last_node_id,
            };

            // ajax
            $.getJSON(TREEKING_API_URL, meta, function(data) {
                // とりま buffer に突っ込む
                for(var key in data) {
                    buffer_nodes.push(data[key]);
                }

                // デーモン起動
                run_node_daemon();
            });
        };





        // デーモン
        var run_daemon = false;
        var run_node_daemon = function() {
            // デーモンが実行していないならば、実行する
            if (!run_daemon) {
                console.log('[run]put daemon.');
                run_daemon = true;
                put_node_daemon();
            }
        };
        var put_node_daemon = function() {
            // デーモン起動許可がない場合は終了する
            if (!run_daemon) {
                console.log('[warning]interrupt.');
                return;
            }

            // ノード取得
            var node = buffer_nodes.shift();

            // ノードがない場合はデーモンを終了させる
            if (node == null) {
                console.log('[stop]put deamon. empty nodes.');
                run_daemon = false;
                return;
            }

            // ノードを追加する
            console.log('[exec]put node...');
            console.log(node);
            create_node_area(node);
        };



        ////////////////////////////////////////////////////////////
        // put method
        ////////////////////////////////////////////////////////////




        var create_node_area = function(node) {
            var doms = [];

            // ノード作成
            doms.push(create_node(node));

            // エッジ作成
            if (node['outer_nodes'].length > 0) {
                for (var key in node['outer_nodes']) {
                    doms.push(create_edge(node, key));
                }
            } else {
                doms.push(create_edge(node, null));
            }



            // append and fade in
            put_node_area(doms);
        };


        var put_node_area = function(doms) {
            // ノード取得
            var dom_string = doms.shift();

            // 無ければ終了
            if (dom_string == null) {
                // TODO メソッドの切り分け!!
                // 待機して再追加
                console.log('next '+wait+'ms.')
                setTimeout(function() {
                    put_node_daemon()
                }, wait);

                return;
            }

            // append and fade in
            var dom = $(dom_string);
            graph.append(dom);
            dom.ready(function() {

                dom.fadeIn(function() {
                    dom.removeClass('hide');
                    setTimeout(function() {
                        put_node_area(doms);
                    }, wait);
                });

            });
        };






        ////////////////////////////////////////////////////////////
        // draw method
        ////////////////////////////////////////////////////////////


        var create_edge = function(node, outerkey) {
            // origin, insert, tail, plane
            var mode = (outerkey) ? node['outer_nodes'][outerkey]['node_type']
                : (node['node_is_tail'] ? 'tail' : 'plane');

            var dom = '';
            dom += '<div class="area edge-area hide">';
                dom += '<div class="row mb-2">';

                    dom += '<div class="col-1">';
                        dom += '<div class="git-icon">';
                            switch (mode) {
                                case 'origin' :
                                    dom += '<span class="icon-node-top"></span>';
                                    dom += '<span class="icon-node-bottom"></span>';
                                    dom += '<span class="icon-node-graft"></span>';
                                    dom += '<span class="icon-circle"></span>';
                                    break;
                                case 'insert' :
                                    dom += '<span class="icon-node-top"></span>';
                                    dom += '<span class="icon-node-bottom"></span>';
                                    dom += '<span class="icon-node-ramify"></span>';
                                    dom += '<span class="icon-circle"></span>';
                                    break;
                                case 'tail' :
                                    dom += '<span class="icon-node-top"></span>';
                                    dom += '<span class="icon-circle"></span>';
                                    break;
                                default :
                                    dom += '<span class="icon-node-top"></span>';
                                    dom += '<span class="icon-node-bottom"></span>';
                                    break;
                            }
                        dom += '</div>';
                    dom += '</div>';

                    dom += '<div class="col">';
                        dom += '<div class="col">';
                            dom += mode;
                        dom +='</div>';
                    dom +='</div>';

                dom +='</div>';
            dom +='</div>';

            return dom;
        };


        var create_node = function(node) {
            var dom = '';
            dom += '<div class="area node-area hide">';
                dom += '<div class="card margin">';

                    dom += '<div class="card-header">';
                        dom += node['node_title'] || '<span class="text-secondary">NO TITLE</span>';
                    dom += '</div>';

                    dom += '<div class="card-body">';
                        dom += node['content'];
                    dom += '</div>';

                dom += '</div>';
            dom +='</div>';

            return dom;
        };






    });


</script>
@endpush