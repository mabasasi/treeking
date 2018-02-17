
<div class="row">
    <div class="col">

        <canvas id="gitGraph"></canvas>

        <div id="girGraphContent">
            <div id="detail" class="gitgraph-detail">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint, ducimus,
                qui fuga corporis veritatis doloribus iure nulla optio dolores maiores dolorum
                ullam alias cum libero obcaecati cupiditate sit illo aperiam possimus voluptatum
                similique neque explicabo quibusdam aspernatur dolorem. Quod, corrupti magni
                explicabo nam sequi nesciunt accusamus aliquam dolore! Cumque, quam fugiat ab
                veritatis. Quia, maxime quas perferendis cupiditate explicabo at atque iusto
                accusamus. Nesciunt veniam quidem nemo doloribus! Dolore, cupiditate, adipisci,
                voluptate quam nihil ipsa placeat dolor possimus minus quas nostrum eaque in dicta
                autem eligendi rerum facilis nesciunt sunt doloremque suscipit enim iure vitae eius
                voluptates tempora tenetur hic.
            </div>
        </div>

    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gitgraph.js/1.11.4/gitgraph.min.css"/>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gitgraph.js/1.11.4/gitgraph.min.js"></script>

<script>
    // TODO GIT GRAPH は逆向きに作る
    var sprigs = {!! $sprigs !!};
    console.log(sprigs);

    var branches = {};



    var myTemplateConfig = {
        colors: ["#F00", "#0F0", "#00F"], // branches colors, 1 per column
        branch: {
            lineWidth: 8,
            // Dash segments, see:
            // https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/setLineDash
            // lineDash: [5, 3],
            spacingX: 50
        },
        commit: {
            spacingY: -80,
            dot: {
                size: 12,
                // lineDash: [4]
            },
            message: {
                displayAuthor: false,
                displayBranch: false,
                displayHash: false,
                font: "normal 12pt Arial"
            },
            shouldDisplayTooltipsInCompactMode: false, // default = true
            tooltipHTMLFormatter: function (commit) {
                return "<b>" + commit.sha1 + "</b>" + ": " + commit.message;
            }
        }
    };
    var myTemplate = new GitGraph.Template(myTemplateConfig);

    var gitgraph = new GitGraph({
        template: myTemplate, // blackarrow, metro
        reverseArrow: true,
        orientation: "vertical",
        mode: 'extend',
    });


    var append_sprig = function(sprig) {
        // branch チェック
        checkout_or_create_branch(sprig.branch_name);

        // detail 作成
        var detail = '<div id="detail-'+sprig.id+'" class="gitgraph-detail">'+sprig.leaves[0].content+'</div>';
        $('#girGraphContent').append(detail);

        gitgraph.commit({
            message: sprig.name,
            detailId: 'detail-'+sprig.id,
        });
    };

    // ブランチを 取得 or 作成する
    var checkout_or_create_branch = function(branch_name) {
        var branch = branches[branch_name];
        if (branch) {
            branch.checkout();
            return branch;
        } else {
            gitgraph.branch(branch_name);
        }
    };



    append_sprig(sprigs[0]);





    // var master = gitgraph.branch('master');
    //
    // gitgraph.commit('initial commit');
    //
    // gitgraph.commit({
    //     message: '2nd commit',
    //     author: 'Anonymous <anonymous@example.com>',
    //     tag: 'v0.0.1',
    //     dotColor: 'white',
    //     dotSize: 10,
    //     dotStrokeWidth: 10
    // });
    //
    // var feature = gitgraph.branch("feature-of-death");
    //
    // gitgraph.commit({
    //     message: '3rd commit',
    //     detailId: 'detail',
    // });
    //
    // master.checkout();
    // gitgraph.commit('4th commit');

</script>
@endpush