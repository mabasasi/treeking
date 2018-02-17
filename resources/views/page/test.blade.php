@extends('layouts.treeking')
@section('title', 'テストページ')

@section('content')
    <div class="container-fluid">
        @component('parts.general-card-component')
            <div>
                <a class="btn btn-outline-primary" href="{{ route('test') }}">更新</a>
                <a class="btn btn-outline-warning" href="{{ route('debug.seed') }}">DB初期化</a>
                <a class="btn btn-danger"          href="{{ route('debug.fresh') }}">DB再構築</a>
            </div>
        @endcomponent

        <div class="row">
            <div class="col">
                @component('parts.general-card-component', ['expand' => true])

                    <div style="font-family: monospace; font-size: 16px;">
                        @foreach($branches as $branch)
                            <div>
                                {{ '◆' }}

                                branch: {{ $branch->id }} &lt;{{ $branch->name }}&gt;
                            </div>

                            {{--先頭から順番に枝を表示--}}
                            @php ($sprig = $branch->headSprig)
                            @while($sprig !== null and $sprig->is_join($branch))
                                <div>
                                    {!! out_if_true(!$sprig->is_tail, '┣', '┗') !!}

                                    sprig: {{ $branch->id }}-{{ $sprig->id }} &lt;{{ $sprig->name }}&gt;
                                    {!! out_if_true($sprig->is_head, '<span class="badge badge-primary">HEAD</span>') !!}
                                    {!! out_if_true($sprig->is_tail, '<span class="badge badge-warning">TAIL</span>') !!}
                                    {!! out_if_true($sprig->is_root, '<span class="badge badge-success">ROOT</span>') !!}

                                    @if($origin = ($sprig->originSprig))
                                        <span class="badge badge-secondary"><= ORIGIN {{ $origin->branch_id.'-'.$origin->id }}</span>
                                    @endif
                                    @foreach($sprig->insertSprigs as $insert)
                                        <span class="badge badge-secondary">=> INSERT {{ $insert->branch_id.'-'.$insert->id }}</span>
                                    @endforeach
                                </div>

                                {{--全ての葉を表示--}}
                                @if($sprig->is_join($branch))
                                    @forelse($sprig->leaves as $leaf)
                                        <div>
                                            {!! out_if_true(!$sprig->is_tail, '┃', '　') !!}
                                            {!! out_if_true($leaf->is_current, '>>', '　') !!}

                                            leaf: {{ $sprig->branch_id }}-{{ $sprig->id }}-{{ $leaf->id }} ({{ optional($leaf->type)->name ?? '-' }}) => {{ str_limit($leaf->content, 20, '...') }}<br>
                                        </div>
                                    @empty
                                        <div>
                                            {!! out_if_true(!$sprig->is_tail, '┃', '　') !!}

                                            <span class="badge badge-danger">EMPTY LEAF! PLEASE BEAR!</span>
                                        </div>
                                    @endforelse
                                @endif

                                {{--次の枝を取得--}}
                                @php($sprig = $sprig->parentSprig)
                            @endwhile


                            {{--枝がない場合の処理--}}
                            @if($branch->is_empty)
                                <div>
                                    {!! out_if_true($branch->head_sprig_id, '┃', '　') !!}
                                    <span class="badge badge-danger">EMPTY SPRIG! PLEASE GROW!</span>
                                </div>
                            @endif


                            {{--別の枝から生えた場合の処理--}}
                            @if($sprig = ($branch->tailSprig) and !$sprig->is_join($branch))
                                <div>
                                    {!! out_if_true(true, '┛') !!}
                                    <span class="badge badge-warning">TAIL</span>
                                </div>
                            @endif

                            ----------<br>
                        @endforeach

                    </div>

                @endcomponent
            </div>


            {{--操作パネル--}}
            <div class="col">

                @component('parts.general-card-component', ['expand' => true])

                    {{--タブバー--}}
                    <ul class="nav nav-tabs" id="tree-action-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="grow-tab" data-toggle="tab" href="#grow" role="tab" aria-controls="grow" aria-selected="true">
                                <span class="octicon octicon-git-commit"></span> GROW
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bear-tab" data-toggle="tab" href="#bear" role="tab" aria-controls="bear" aria-selected="true">
                                <span class="octicon octicon-sync"></span> BEAR
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="plant-tab" data-toggle="tab" href="#plant" role="tab" aria-controls="plant" aria-selected="true">
                                <span class="octicon octicon-plus"></span> PLANT
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ramify-tab" data-toggle="tab" href="#ramify" role="tab" aria-controls="ramify" aria-selected="true">
                                <span class="octicon octicon-git-branch"></span> RAMIFY
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="graft-tab" data-toggle="tab" href="#graft" role="tab" aria-controls="graft" aria-selected="true">
                                <span class="octicon octicon-git-pull-request"></span> GRAFT
                            </a>
                        </li>
                    </ul>

                    {{--タブコンテンツ--}}
                    <div class="tab-content" id="tree-action-tab-content">
                        <div class="tab-pane fade" id="grow" role="tabpanel" aria-labelledby="grow-tab">
                            <h4>GROW</h4>
                            <p>幹 の先端から 枝 を伸ばす.</p>
                            {{ Form::open(['method' => 'POST', 'url' => route('action.tree.grow')]) }}

                            @component('parts.inline-form-component',['name' => 'branch_id', 'label' => '対象の 幹'])
                                {{ Form::select('branch_id', \App\Models\Branch::selectPluck(), old('branch_id'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'name', 'label' => '葉 の名前'])
                                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                            @endcomponent

                            <div class="row justify-content-md-center">
                                <div class="col-sm-4">
                                    {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                        <div class="tab-pane fade" id="bear" role="tabpanel" aria-labelledby="bear-tab">
                            <h4>BEAR</h4>
                            <p>枝 に 葉 を付ける.</p>
                            {{ Form::open(['method' => 'POST', 'url' => route('action.tree.bear')]) }}

                            @component('parts.inline-form-component',['name' => 'sprig_id', 'label' => '対象の 葉'])
                                {{ Form::select('sprig_id', \App\Models\Sprig::selectPluck(), old('sprig_id'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'leaf_type_id', 'label' => '葉 の種類'])
                                {{ Form::select('leaf_type_id', \App\Models\LeafType::selectPluck(), old('leaf_type_id'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'revision', 'label' => 'リビジョン'])
                                {{ Form::text('revision', old('revision'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'content', 'label' => '内容'])
                                {{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3']) }}
                            @endcomponent

                            <div class="row justify-content-md-center">
                                <div class="col-sm-4">
                                    {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                        <div class="tab-pane fade" id="plant" role="tabpanel" aria-labelledby="plant-tab">
                            <h4>PLANT</h4>
                            <p>新たに 幹 を作る.</p>
                            {{ Form::open(['method' => 'POST', 'url' => route('action.tree.plant')]) }}

                            @component('parts.inline-form-component',['name' => 'name', 'label' => '幹 の名前'])
                                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                            @endcomponent

                            <div class="row justify-content-md-center">
                                <div class="col-sm-4">
                                    {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                        <div class="tab-pane fade" id="ramify" role="tabpanel" aria-labelledby="ramify-tab">
                            <h4>RAMIFY</h4>
                            <p>枝 を分岐させて新たな 幹 を作る.</p>
                            {{ Form::open(['method' => 'POST', 'url' => route('action.tree.ramify')]) }}

                            @component('parts.inline-form-component',['name' => 'sprig_id', 'label' => '対象の 枝'])
                                {{ Form::select('sprig_id', \App\Models\Sprig::selectPluck(), old('sprig_id'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'name', 'label' => '幹 の名前'])
                                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                            @endcomponent

                            <div class="row justify-content-md-center">
                                <div class="col-sm-4">
                                    {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                        <div class="tab-pane fade" id="graft" role="tabpanel" aria-labelledby="graft-tab">
                            <h4>GRAFT</h4>
                            <p>幹 をまとめて 別の幹へ枝を生やす.</p>
                            {{ Form::open(['method' => 'POST', 'url' => route('action.tree.graft')]) }}
                            @component('parts.inline-form-component',['name' => 'sprig_id', 'label' => '元の 枝'])
                                {{ Form::select('sprig_id', \App\Models\Sprig::selectPluck(), old('leaf_id'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'branch_id', 'label' => '対象の 幹'])
                                {{ Form::select('branch_id', \App\Models\Branch::selectPluck(), old('tree_id'), ['class' => 'form-control']) }}
                            @endcomponent

                            @component('parts.inline-form-component',['name' => 'name', 'label' => '新たな 枝 の名前'])
                                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                            @endcomponent

                            <div class="row justify-content-md-center">
                                <div class="col-sm-4">
                                    {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                @endcomponent
            </div>

        </div>
    </div>
@endsection