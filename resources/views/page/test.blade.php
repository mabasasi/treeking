<a href="{{ route('test') }}">更新</a>
<a href="{{ route('test', ['seed' => 'true']) }}">データごと更新</a>
<hr>

<div style="font-family: monospace; font-size: 20px;">
    @forelse($trees as $tree)
        tree: {{ $tree->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $tree->name }}<br>

        {{--先端から順番に葉を表示--}}
        @php($leaf = $tree->headLeaf)
        @while($leaf !== null)

            {{--葉 leaf の表示--}}
            {{ (!$leaf->is_tail) ? '├' : '└' }}
            leaf: {{ $leaf->id }}
            @isset($leaf->originLeaf)
                &nbsp;&nbsp;[<= origin: {{ $leaf->originLeaf->tree_id.'-'.$leaf->originLeaf->id }}]
            @endisset
            @foreach($leaf->insertLeaves as $insert)
                &nbsp;&nbsp;[=> insert: {{ $insert->tree_id.'-'.$insert->id }}]
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