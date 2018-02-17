@extends('layouts.treeking')
@section('title', 'テストページ')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="card card-body margin">

                    <div>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#grow-collapse" aria-expanded="false" aria-controls="grow-collapse">
                            <span class="octicon octicon-git-commit"></span> GROW
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#bear-collapse" aria-expanded="false" aria-controls="bear-collapse">
                            <span class="octicon octicon-sync"></span> BEAR
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#plant-collapse" aria-expanded="false" aria-controls="plant-collapse">
                            <span class="octicon octicon-plus"></span> PLANT
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#branch-collapse" aria-expanded="false" aria-controls="branch-collapse">
                            <span class="octicon octicon-git-branch"></span> BRANCH
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#graft-collapse" aria-expanded="false" aria-controls="graft-collapse">
                            <span class="octicon octicon-git-pull-request"></span> GRAFT
                        </button>

                        <div class="float-right">
                            <a class="btn btn-outline-primary" href="{{ route('test') }}">更新</a>
                            <a class="btn btn-outline-danger"  href="{{ route('debug.seed') }}">DB初期化</a>
                            <a class="btn btn-danger"          href="{{ route('debug.fresh') }}">DB再構築</a>
                        </div>
                    </div>

                    {{--<div class="collapse" id="grow-collapse">--}}
                        {{--<hr>--}}

                        {{--<h4>木の成長[grow]</h4>--}}
                        {{--<p>木の先端に リーフ と フルーツ を生やします.</p>--}}
                        {{--{{ Form::open(['method' => 'POST', 'url' => route('action.tree.grow')]) }}--}}

                        {{--@component('parts.inline-form-component',['name' => 'title', 'label' => 'タイトル'])--}}
                            {{--{{ Form::text('title', old('title'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'content', 'label' => '内容'])--}}
                            {{--{{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'fruit_type_id', 'label' => '種類'])--}}
                            {{--{{ Form::select('fruit_type_id', \App\Models\FruitType::selectPluck(), old('fruit_type_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'tree_id', 'label' => '対象の木'])--}}
                            {{--{{ Form::select('tree_id', \App\Models\Tree::selectPluck(), old('tree_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--<div class="row justify-content-md-center">--}}
                            {{--<div class="col-sm-4">--}}
                                {{--{{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--{{ Form::close() }}--}}
                    {{--</div>--}}

                    {{--<div class="collapse" id="bear-collapse">--}}
                        {{--<hr>--}}

                        {{--<h4>実を付ける[bear]</h4>--}}
                        {{--<p>葉に新たな実をつける.</p>--}}
                        {{--{{ Form::open(['method' => 'POST', 'url' => route('action.leaf.bear')]) }}--}}

                        {{--@component('parts.inline-form-component',['name' => 'title', 'label' => 'タイトル'])--}}
                            {{--{{ Form::text('title', old('title'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'content', 'label' => '内容'])--}}
                            {{--{{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'fruit_type_id', 'label' => '種類'])--}}
                            {{--{{ Form::select('fruit_type_id', \App\Models\FruitType::selectPluck(), old('fruit_type_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'leaf_id', 'label' => '対象の葉'])--}}
                            {{--{{ Form::select('leaf_id', \App\Models\Leaf::selectPluck(), old('leaf_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--<div class="row justify-content-md-center">--}}
                            {{--<div class="col-sm-4">--}}
                                {{--{{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--{{ Form::close() }}--}}
                    {{--</div>--}}

                    {{--<div class="collapse" id="plant-collapse">--}}
                        {{--<hr>--}}

                        {{--<h4>木を植える[PLANT]</h4>--}}
                        {{--<p>新たな木を作る.</p>--}}
                        {{--{{ Form::open(['method' => 'POST', 'url' => route('action.tree.plant')]) }}--}}

                        {{--@component('parts.inline-form-component',['name' => 'name', 'label' => '木の名前'])--}}
                            {{--{{ Form::text('name', old('name'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--<div class="row justify-content-md-center">--}}
                            {{--<div class="col-sm-4">--}}
                                {{--{{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--{{ Form::close() }}--}}
                    {{--</div>--}}

                    {{--<div class="collapse" id="branch-collapse">--}}
                        {{--<hr>--}}

                        {{--<h4>葉を移植する[BRANCH]</h4>--}}
                        {{--<p>リーフを別の木へ接続する.</p>--}}
                        {{--{{ Form::open(['method' => 'POST', 'url' => route('action.leaf.branch')]) }}--}}

                        {{--@component('parts.inline-form-component',['name' => 'tree_name', 'label' => '木の名前'])--}}
                            {{--{{ Form::text('tree_name', old('tree_name'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'leaf_id', 'label' => '対象の葉'])--}}
                            {{--{{ Form::select('leaf_id', \App\Models\Leaf::selectPluck(), old('leaf_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--<div class="row justify-content-md-center">--}}
                            {{--<div class="col-sm-4">--}}
                                {{--{{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--{{ Form::close() }}--}}
                    {{--</div>--}}

                    {{--<div class="collapse" id="graft-collapse">--}}
                        {{--<hr>--}}

                        {{--<h4>(葉をまとめて)別の木へ刺す[GRAFT]</h4>--}}
                        {{--<p>リーフを別の木へ接続する.</p>--}}
                        {{--{{ Form::open(['method' => 'POST', 'url' => route('action.leaf.graft')]) }}--}}

                        {{--@component('parts.inline-form-component',['name' => 'title', 'label' => 'タイトル'])--}}
                            {{--{{ Form::text('title', old('title'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'content', 'label' => '内容'])--}}
                            {{--{{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'fruit_type_id', 'label' => '種類'])--}}
                            {{--{{ Form::select('fruit_type_id', \App\Models\FruitType::selectPluck(), old('fruit_type_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'leaf_id', 'label' => '元の葉'])--}}
                            {{--{{ Form::select('leaf_id', \App\Models\Leaf::selectPluck(), old('leaf_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--@component('parts.inline-form-component',['name' => 'tree_id', 'label' => '対象の木'])--}}
                            {{--{{ Form::select('tree_id', \App\Models\Tree::selectPluck(), old('tree_id'), ['class' => 'form-control']) }}--}}
                        {{--@endcomponent--}}

                        {{--<div class="row justify-content-md-center">--}}
                            {{--<div class="col-sm-4">--}}
                                {{--{{ Form::submit('作成', ['class' => 'btn btn-block btn-primary']) }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--{{ Form::close() }}--}}
                    {{--</div>--}}

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">

                <div style="font-family: monospace; font-size: 16px;">
                    @foreach($branches as $branch)
                        <div>
                            {{ '◆' }}

                            branch: {{ $branch->id }} &lt;{{ $branch->name }}&gt;
                        </div>

                        {{--先頭から順番に枝を表示--}}
                        @php ($sprig = $branch->headSprig)
                        @while($sprig !== null and $sprig->branch_id === $branch->id)
                            <div>
                                {!! out_if_true(!$sprig->is_tail, '┣', '┗') !!}

                                sprig: {{ $branch->id }}-{{ $sprig->id }} &lt;{{ $sprig->name }}&gt;
                                {!! out_if_true($sprig->is_head, '<span class="badge badge-secondary">HEAD</span>') !!}
                                {!! out_if_true($sprig->is_tail, '<span class="badge badge-secondary">TAIL</span>') !!}
                                {!! out_if_true($sprig->is_root, '<span class="badge badge-secondary">ROOT</span>') !!}
                            </div>

                            {{--全ての葉を表示--}}
                            @foreach($sprig->leaves as $leaf)
                                <div>
                                    {!! out_if_true(!$sprig->is_tail, '┃', '　') !!}
                                    {!! out_if_true($leaf->is_current, '>>', '　') !!}

                                    leaf: {{ $branch->id }}-{{ $sprig->id }}-{{ $leaf->id }} ({{ optional($leaf->type)->name ?? '-' }}) => {{ str_limit($leaf->content, 20, '...') }}<br>
                                </div>
                            @endforeach

                            @php($sprig = $sprig->parentSprig)
                        @endwhile

                        ----------<br>
                    @endforeach



                    {{--@forelse($trees as $tree)--}}
                        {{--tree: {{ $tree->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $tree->name }}<br>--}}

                        {{--先端から順番に葉を表示--}}
                        {{--@php($leaf = $tree->headLeaf)--}}
                        {{--@while($leaf !== null and $leaf->tree_id === $tree->id)--}}

                            {{--葉 leaf の表示--}}
                            {{--{{ (!$leaf->is_tail) ? '├' : '└' }}--}}
                            {{--leaf: {{ $leaf->id }}&nbsp;&nbsp;--}}
                            {{--@if($leaf->is_root)--}}
                                {{--[ROOT]--}}
                            {{--@endif--}}
                            {{--@if($leaf->is_head)--}}
                                {{--[HEAD]--}}
                            {{--@endif--}}
                            {{--@if($leaf->is_tail)--}}
                                {{--[TAIL]--}}
                            {{--@endif--}}
                            {{--@isset($leaf->originLeaf)--}}
                                {{--[<= origin: {{ $leaf->originLeaf->tree_id.'-'.$leaf->originLeaf->id }}]--}}
                            {{--@endisset--}}
                            {{--@foreach($leaf->insertLeaves as $insert)--}}
                                {{--[=> insert: {{ $insert->tree_id.'-'.$insert->id }}]--}}
                            {{--@endforeach--}}
                            {{--<br>--}}

                            {{--実 fruit の表示--}}
                            {{--@forelse($leaf->fruits as $fruit)--}}

                                {{--{{ (!$leaf->is_tail) ? '│' : '&nbsp;&nbsp;' }}--}}
                                {{--{{ $fruit->is_current ? '+ ' : '. ' }}fruit: {{ $fruit->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $fruit->title }}: {{ $fruit->content }}<br>--}}

                            {{--@empty--}}

                                {{--{{ (!$leaf->is_tail) ? '│' : '&nbsp;&nbsp;' }}<br>--}}

                            {{--@endforelse--}}

                            {{--@php($leaf = $leaf->parentLeaf)--}}
                        {{--@endWhile--}}

                        {{--<br><br>--}}
                    {{--@empty--}}
                        {{--empty trees!--}}
                    {{--@endforelse--}}

                </div>

            </div>
        </div>
    </div>
@endsection