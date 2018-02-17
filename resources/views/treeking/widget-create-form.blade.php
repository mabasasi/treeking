
<div class="row">
    <div class="col" style="background: lightgoldenrodyellow;">

        {{ Form::open(['method' => 'POST', 'url' => route('action.tree.growAndBear')]) }}

        @component('parts.general-card-component')
            @slot('header')
                めもる！
                <div class="pull-right">
                    <span id="branch-name" class="text-secondary">
                        {{ optional(\App\models\Branch::updateNewer()->first())->name ?? 'Nothing.' }}
                    </span>
                </div>
            @endslot

            {{--入力画面--}}
            @component('parts.group-form-component',['name' => 'content'])
                {{ Form::textarea('content', old('content'), ['class' => 'form-control', 'size' => '10x3',
                        'placeholder' => 'めもの入力...', 'autofocus']) }}
            @endcomponent

            {{--簡易ツールバー--}}
            @component('parts.group-form-component',['name' => 'content', 'class' => 'mb-0'])

                <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#detail-collapse" aria-expanded="false" aria-controls="detail-collapse">
                    <i class="fas fa-caret-down"></i> 詳細
                </button>

                <div class="pull-right">
                    {{ Form::submit('保存', ['class' => 'btn btn-success']) }}
                </div>
            @endcomponent

            {{--詳細ペイン--}}
            <div class="collapse" id="detail-collapse">
                <hr>

                @component('parts.inline-form-component',['name' => 'branch_id', 'label' => '対象の 幹'])
                    {{ Form::select('branch_id', \App\Models\Branch::selectPluck(), old('branch_id'), ['class' => 'form-control']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'revision', 'label' => 'リビジョン'])
                    {{ Form::text('revision', old('revision'), ['class' => 'form-control']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'leaf_type_id', 'label' => '種類'])
                    {{ Form::select('leaf_type_id', \App\Models\LeafType::selectPluck(), old('leaf_type_id'), ['class' => 'form-control']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'sprig_name', 'label' => 'タイトル'])
                    {{ Form::text('sprig_name', old('sprig_name'), ['class' => 'form-control']) }}
                @endcomponent
            </div>
        @endcomponent

        {{ Form::close() }}

    </div>
</div>

@push('scripts')
<script>
    $(document).on('change', '[name="branch_id"]', function() {
       var branch_name = $('[name=branch_id] option:selected').text();
        var dom = $('#branch-name');
        dom.fadeOut('fast', function() {
            dom.text(branch_name).fadeIn();
        });
    });
</script>
@endpush