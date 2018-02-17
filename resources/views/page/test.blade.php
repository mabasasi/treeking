@extends('layouts.treeking')
@section('title', 'テストページ')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="card card-body margin">

                    <div>
                        <a class="btn btn-outline-primary" href="{{ route('test') }}">更新</a>
                        <a class="btn btn-outline-danger" href="{{ route('test', ['seed' => 'true']) }}">データごと更新</a>

                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#grow-collapse" aria-expanded="false" aria-controls="grow-collapse">
                            GROW <i class="fas fa-caret-down"></i>
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#bear-collapse" aria-expanded="false" aria-controls="bear-collapse">
                            BEAR <i class="fas fa-caret-down"></i>
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#plant-collapse" aria-expanded="false" aria-controls="plant-collapse">
                            PLANT <i class="fas fa-caret-down"></i>
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#branch-collapse" aria-expanded="false" aria-controls="branch-collapse">
                            BRANCH <i class="fas fa-caret-down"></i>
                        </button>
                    </div>

                    <div class="collapse" id="grow-collapse">
                        <hr>

                        <h4>木の成長[grow]</h4>
                        <p>木の先端に リーフ と フルーツ を生やします.</p>
                        {{ Form::open(['method' => 'POST', 'url' => route('action.tree.grow')]) }}

                        @component('parts.inline-form-component',['name' => 'title', 'label' => 'タイトル'])
                            {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'content', 'label' => '内容'])
                            {{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'fruit_type_id', 'label' => '種類'])
                            {{ Form::select('fruit_type_id', \App\Models\FruitType::selectPluck(), old('fruit_type_id'), ['class' => 'form-control']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'tree_id', 'label' => '対象の木'])
                            {{ Form::select('tree_id', \App\Models\Tree::selectPluck(), old('tree_id'), ['class' => 'form-control']) }}
                        @endcomponent

                        <div class="row justify-content-md-center">
                            <div class="col-sm-4">
                                {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="collapse" id="bear-collapse">
                        <hr>

                        <h4>実を付ける[bear]</h4>
                        <p>葉に新たな実をつける.</p>
                        {{ Form::open(['method' => 'POST', 'url' => route('action.leaf.bear')]) }}

                        @component('parts.inline-form-component',['name' => 'title', 'label' => 'タイトル'])
                            {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'content', 'label' => '内容'])
                            {{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'fruit_type_id', 'label' => '種類'])
                            {{ Form::select('fruit_type_id', \App\Models\FruitType::selectPluck(), old('fruit_type_id'), ['class' => 'form-control']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'leaf_id', 'label' => '対象の葉'])
                            {{ Form::select('leaf_id', \App\Models\Leaf::selectPluck(), old('leaf_id'), ['class' => 'form-control']) }}
                        @endcomponent

                        <div class="row justify-content-md-center">
                            <div class="col-sm-4">
                                {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="collapse" id="plant-collapse">
                        <hr>

                        <h4>木を植える[PLANT]</h4>
                        <p>新たな木を作る.</p>
                        {{ Form::open(['method' => 'POST', 'url' => route('action.tree.plant')]) }}

                        @component('parts.inline-form-component',['name' => 'name', 'label' => '木の名前'])
                            {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                        @endcomponent

                        <div class="row justify-content-md-center">
                            <div class="col-sm-4">
                                {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="collapse" id="branch-collapse">
                        <hr>

                        <h4>葉を移植する[BRANCH]</h4>
                        <p>リーフを別の木へも接続する.</p>
                        {{ Form::open(['method' => 'POST', 'url' => route('action.leaf.branch')]) }}

                        @component('parts.inline-form-component',['name' => 'tree_name', 'label' => '木の名前'])
                            {{ Form::text('tree_name', old('tree_name'), ['class' => 'form-control']) }}
                        @endcomponent

                        @component('parts.inline-form-component',['name' => 'leaf_id', 'label' => '対象の葉'])
                            {{ Form::select('leaf_id', \App\Models\Leaf::selectPluck(), old('leaf_id'), ['class' => 'form-control']) }}
                        @endcomponent

                        <div class="row justify-content-md-center">
                            <div class="col-sm-4">
                                {{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">

                <div style="font-family: monospace; font-size: 16px;">
                    @forelse($trees as $tree)
                        tree: {{ $tree->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $tree->name }}<br>

                        {{--先端から順番に葉を表示--}}
                        @php($leaf = $tree->headLeaf)
                        @while($leaf !== null and $leaf->tree_id === $tree->id)

                            {{--葉 leaf の表示--}}
                            {{ (!$leaf->is_tail) ? '├' : '└' }}
                            leaf: {{ $leaf->id }}&nbsp;&nbsp;
                            @if($leaf->is_root)
                                [ROOT]
                            @endif
                            @if($leaf->is_head)
                                [HEAD]
                            @endif
                            @if($leaf->is_tail)
                                [TAIL]
                            @endif
                            @isset($leaf->originLeaf)
                                [<= origin: {{ $leaf->originLeaf->tree_id.'-'.$leaf->originLeaf->id }}]
                            @endisset
                            @foreach($leaf->insertLeaves as $insert)
                                [=> insert: {{ $insert->tree_id.'-'.$insert->id }}]
                            @endforeach
                            <br>

                            {{--実 fruit の表示--}}
                            @forelse($leaf->fruits as $fruit)

                                {{ (!$leaf->is_tail) ? '│' : '&nbsp;&nbsp;' }}
                                {{ $fruit->is_current ? '+ ' : '. ' }}fruit: {{ $fruit->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $fruit->title }}: {{ $fruit->content }}<br>

                            @empty

                                {{ (!$leaf->is_tail) ? '│' : '&nbsp;&nbsp;' }}<br>

                            @endforelse

                            @php($leaf = $leaf->parentLeaf)
                        @endWhile

                        <br><br>
                    @empty
                        empty trees!
                    @endforelse

                </div>

            </div>
        </div>
    </div>
@endsection