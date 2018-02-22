
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

        var TREEKING_INDEX_URL = "{!! route('treeking.index') !!}";
        var TREEKING_API_URL = "{!! route('treeking.get') !!}";
        var graph = $('#node-graph');
        var WAIT_TIME = 200;
        var FADE_MODE = 'slow';




        var last_node_id = null;
        var buffer_nodes = [];

        $(window).ready(function() {
            load_treeking_graph();
        });





        // ブランチ名更新
        $(document).on('change', '[name="branch_id"]', function() {
            // ブランチ ID をセット
            var branch_id = $('[name="branch_id"]').val();
            graph.data('branch-id', branch_id);

            reload_treeking_graph();
        });






        var reload_treeking_graph = function() {
            graph.fadeOut(function() {
                graph.empty();
                graph.fadeIn();

                // 初期化
                buffer_nodes = [];
                last_node_id = null;
                load_treeking_graph();
            });
        };




        // メモツリー取得
        var load_treeking_graph = function() {
            // データが存在する場合は終了
            if (buffer_nodes.length > 0) {
                console.log('[error] data is not empty.');
                return;
            }

            // データ取得
            var meta = {
                branch_id:    graph.data('branch-id'),
                count:        graph.data('count'),
                last_node_id: last_node_id,          // 初期 null
                split:        1,
            };

            // ajax
            console.log('[ajax] get json.');
            $.getJSON(TREEKING_API_URL, meta, function(data) {
                // if error
                if ('errors' in data) {
                    window.alert(JSON.stringify(data));
                    return;
                }

                // とりま buffer に突っ込む
                for(var key in data) {
                    buffer_nodes.push(data[key]);
                }

                // デーモン実行
                start_treeking_graph();
            });
        };



        //////////////////////////////////////////////////////////////////////
        /// デーモン 本体
        //////////////////////////////////////////////////////////////////////

        // デーモン実行
        var run_treeking_graph = false;
        var start_treeking_graph = function() {
            // 既に実行されているなら終了
            if (run_treeking_graph) {
                console.log('[error] daemon already running.');
                return;
            }

            // デーモン実行
            console.log('[start] daemon run.');
            run_treeking_graph = true;
            treeking_graph_daemon();
        };



        // デーモン本体
        var treeking_graph_daemon = function() {
            // デーモン起動許可がない場合は終了する
            if (!run_treeking_graph) {
                console.log('[error] daemon was interrupted.');
                return;
            }

            // ノード取得
            var node = buffer_nodes.shift();

            // ノードがない場合はデーモンを終了させる
            if (node == null) {
                console.log('[stop] daemon stop. success.');
                run_treeking_graph = false;
                return;
            }

            // ノードを追加する
            console.log('[exec]draw html...');
            var dom = draw_treeking_graph(node);

            // 完成待機からの自身を実行
            dom.ready(function() {
                // アニメーション実行
                dom.fadeIn(FADE_MODE, function() {
                    dom.removeClass('hide');
                });

                // 次を裏で作っておく
                setTimeout(function() {
                    treeking_graph_daemon();
                }, WAIT_TIME);
            });
        };



        //////////////////////////////////////////////////////////////////////
        /// デーモンの処理関数
        //////////////////////////////////////////////////////////////////////

        // メモツリーをひとつ描画する
        // @return dom entity
        var draw_treeking_graph = function(node) {
            // テキスト取得
            var text = create_html_switcher(node);

            // dom　作成 -> dom 返却
            var dom = $(text);
            graph.append(dom);
            return dom;
        };

        //////////////////////////////////////////////////////////////////////
        /// 描画関数
        //////////////////////////////////////////////////////////////////////

        var create_html_switcher = function(node) {
            switch(node['type']) {
                case 'node': return create_node_html(node);
                case 'origin':
                case 'insert':
                case 'none':
                case 'tail':
                    return create_edge_html(node, node['type']);

                default:     return create_default_html(node);
            }
        };



        var create_default_html = function(node) {
            var text = '';
            text += '<div class="area node-area hide">';
            text += 'default';
            text += '</div>';
            return text;
        };

        var create_node_html = function(node) {
            var text = '';
            text += '<div class="area node-area hide">';
                text += '<div class="card margin">';

                    text += '<div class="card-header">';
                        text += node['node_title'] || '<span class="text-secondary">NO TITLE</span>';
                    text += '</div>';

                    text += '<div class="card-body">';
                        text += node['content'];
                    text += '</div>';

                text += '</div>';
            text +='</div>';
            return text;
        };

        var create_edge_html = function(node, mode) {
            var meta = crate_edge_text(node, mode);

            var text = '';
            text += '<div class="area edge-area hide">';
                text += '<div class="row mb-2">';

                    text += '<div class="col-1">';
                        text += '<div class="git-icon">';
                            text += meta['icon'];
                        text += '</div>';

                    text +='</div>';

                    text += '<div class="col">';
                        text += meta['content'];
                    text +='</div>';

                text +='</div>';
            text +='</div>';
            return text;
        };






        var crate_edge_text = function(node, mode) {
            var meta = [];

            // アイコン作成
            var text = '';
            switch (mode) {
                case 'origin' :
                    text += '<span class="icon-node-top"></span>';
                    text += '<span class="icon-node-bottom"></span>';
                    text += '<span class="icon-node-graft"></span>';
                    text += '<span class="icon-circle"></span>';
                    break;
                case 'insert' :
                    text += '<span class="icon-node-top"></span>';
                    text += '<span class="icon-node-bottom"></span>';
                    text += '<span class="icon-node-ramify"></span>';
                    text += '<span class="icon-circle"></span>';
                    break;
                case 'tail' :
                    text += '<span class="icon-node-top"></span>';
                    text += '<span class="icon-circle"></span>';
                    break;
                case 'no-tail':
                    text += '<span class="icon-circle"></span>';
                    break;
                default :
                    text += '<span class="icon-node-top"></span>';
                    text += '<span class="icon-node-bottom"></span>';
                    break;
            }
            meta['icon'] = text;


            // 内容作成
            var text = '';
            switch (mode) {
                case 'origin' :
                    var href = TREEKING_INDEX_URL + '?branch_id=' + node['branch_id'] + '&sprig_id=' + node['sprig_id'];
                    text += '<a class="btn btn-under-animation mr-1" href=' + href + '>';
                        text += '<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;' + node['branch_name'] + 'から統合';
                    text += '</a>';

                    text += '<small>';
                        text += node['node_name'];
                    text += '</small>';
                    break;
                case 'insert' :
                    var href = TREEKING_INDEX_URL + '?branch_id=' + node['branch_id'] + '&sprig_id=' + node['sprig_id'];
                    text += '<a class="btn btn-under-animation mr-1" href=' + href + '>';
                    text += '<i class="fas fa-angle-double-left"></i>&nbsp;&nbsp;' + node['branch_name'] + 'へ分岐';
                    text += '</a>';

                    text += '<small>';
                    text += node['node_name'];
                    text += '</small>';
                    break;
                case 'no-tail':
                    var href = TREEKING_INDEX_URL + '?branch_id=' + node['branch_id'] + '&sprig_id=' + node['sprig_id'];
                    text += '<a class="btn btn-under-animation mr-1" href=' + href + '>';
                    text += '<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;' + node['branch_name'] + 'から作成';
                    text += '</a>';

                    text += '<small>';
                    text += node['node_name'];
                    text += '</small>';
                    break;
                case 'tail' :
                    text += '<small class="btn">';
                    text += '<i class="fas fa-plus"></i>&nbsp;&nbsp;' + node['branch_created_at'] + '&nbsp;&nbsp;に作成';
                    text += '</small>';
                    break;
                default :
                    text += '';
                    break;
            }
            meta['content'] = text;

            return meta;
        };

    });


</script>
@endpush