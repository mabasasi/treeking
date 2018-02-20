<?php

namespace App\Http\Controllers;

use App\models\Branch;
use App\models\Sprig;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TreekingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $branch = null;

        // ブランチのID を取得
        $request_branch_id      = request('branch_id');
        $user_current_branch_id = optional(\Auth::user())->current_branch_id;

        // リクエストがあるならば user を更新してそれを取得
        if ($request_branch_id) {
            $br = Branch::find($request_branch_id);
            if ($br) {
                \Auth::user()->fill(['current_branch_id' => $request_branch_id])->save();
                $branch = $br;
            }
        }

        // それ以外で見つかっていないなら、カレントを探す
        if (!$branch and $user_current_branch_id) {
            $br = Branch::find($user_current_branch_id);
            if ($br) {
                $branch = $br;
            }
        }

        // TODO それでも見つからない場合を考える
        // TODO 自身のブランチが存在しない場合...

        return view('treeking.index')->with(compact('branch'));
    }


    public function getSprigs(Request $request) {
        \Validator::make($request->all(), [
            'branch_id'    => 'required|exists:branches,id',
            'last_node_id' => 'nullable|exists_or_null:sprigs,id', // これを基準にデータを取得する
            'count'        => 'required|integer|min:1',
            'split'        => 'nullable|boolean',
        ])->validate();

        // ブランチ取得
        $branch = Branch::find($request->get('branch_id'));

        // 基準の 小枝 を取得する
        $sprig = Sprig::where([
            ['id',        $request->get('last_node_id')],
            ['branch_id', $request->get('branch_id')],
        ])->first();

        // 取れてたら一つ親へずらす
        if ($sprig) {
            $sprig = $sprig->parentSprig;
        }

        // 取れなかったら、branch の head を取りに行く
        if (!$sprig) {
            if ($branch) {
                $sprig = $branch->headSprig;
            }
        }

        // それでもとれなかったら abort
        if (!$sprig) {
            abort('404', 'Not found sprig.');
        }


        // データ構築を行う
        $array = new Collection();
        $count = $request->get('count');
        for ($i=0; $i<$count; $i++) {
            $leaf = optional($sprig->currentLeave);

            // 自身のノードを追加する
            $node = [
                'type' => 'node',

                'branch_id'         => $branch->id,
                'branch_name'       => $branch->name,
                'branch_created_at' => optional($branch->created_at)->toDateTimeString(),

                'node_id'   => $sprig->id,
                'node_name' => $sprig->name,

                'current_content_id' => $leaf->id,
                'content'            => $leaf->content,
                'content_type_id'    => $leaf->content_type_id,
                'content_type'       => optional($leaf->leaf_type_id)->name,

                'node_is_head'    => $sprig->is_head,
                'node_is_tail'    => $sprig->is_tail,
                'node_has_origin' => $sprig->has_origin,
                'node_has_insert' => $sprig->has_insert,
            ];


            // 自身の関連ノード取得
            $refs = [];


            // origin 取ってくる
            if (($origin = ($sprig->originSprig))) {
                $originBranch =  optional($origin->branch);
                $item = [
                    'type' => 'origin',

                    'branch_id'         => $originBranch->id,
                    'branch_name'       => $originBranch->name,
                    'branch_created_at' => optional($originBranch->created_at)->toDateTimeString(),

                    'node_id'     => $origin->id,
                    'node_name'   => $origin->name,
                ];
                $refs[] = $item;
            }

            // inserts 取ってくる
            foreach ($sprig->insertSprigs as $insert) {
                $insertBranch =  optional($insert->branch);
                $item = [
                    'type' => 'insert',

                    'branch_id'         => $insertBranch->id,
                    'branch_name'       => $insertBranch->name,
                    'branch_created_at' => optional($insertBranch->created_at)->toDateTimeString(),

                    'node_id'     => $insert->id,
                    'node_name'   => $insert->name,
                ];
                $refs[] = $item;
            }

            // 何もなければ ダミーを作成
            $dummy = null;
            if (count($refs) === 0) {
                if ($sprig->is_tail) {
                    $bbr = $sprig->branch;
                    $item = [
                        'type' => 'tail',

                        'branch_id'         => $bbr->id,
                        'branch_name'       => $bbr->name,
                        'branch_created_at' => optional($bbr->created_at)->toDateTimeString(),
                    ];
                }  else {
                    $item = [
                        'type' => 'none',
                    ];
                }
                $dummy = $item;
            }


            ////////////////////////////////////////

            // 追加していくぅ
            if ($request->get('split')) {
                $array->push($node);
                foreach ($refs as $ref) {
                    $array->push($ref);
                }
                if ($dummy) {
                    $array->push($dummy);
                }
            } else {
                $node['refs'] = $refs;
                $array->push($node);
            }

            // 次を取ってくる
            $sprig = $sprig->parentSprig;
            if (!$sprig)    break;
        }

        return $array;
    }

}
