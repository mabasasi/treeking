<div style="font-family: monospace; font-size: 20px;">
    @forelse($trees as $tree)
        tree: {{ $tree->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $tree->title }}<br>

        {{--先端から順番に葉を表示--}}
        @php($leaf = $tree->headLeaf)
        @while($leaf !== null)

            {{ (!$leaf->is_tail) ? '├' : '└' }}
            leaf: {{ $leaf->id }}<br>

            {{--全ての実を表示--}}
            @forelse($leaf->fruits as $fruit)

                {{ (!$leaf->is_tail) ? '│' : '&nbsp;&nbsp;' }}
                {{ $fruit->is_current ? '+ ' : '. ' }}fruit: {{ $fruit->id }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $fruit->title }}: {{ $fruit->content }}<br>
            @empty
                empty fruits!
            @endforelse

            @php($leaf = $leaf->parentLeaf)
        @endWhile

        <br><br>
    @empty
        empty trees!
    @endforelse
</div>