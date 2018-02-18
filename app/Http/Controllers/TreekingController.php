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
        $array =[];
        $count = $request->get('count');
        for ($i=0; $i<$count; $i++) {
            $leaf = optional($sprig->currentLeave);

            // origins 作成
            $outerItems = [];
            if ($sprig->has_origin) {
                $origin = $sprig->originSprig;
                $originBranch =  optional($origin->branch);

                $item = [
                    'node_type' => 'origin',

                    'branch_id'   => $originBranch->id,
                    'branch_name' => $originBranch->name,
                    'node_id'     => $origin->id,
                    'node_name'   => $origin->name,

                    // TODO 何なら leaf まで
                ];
                $outerItems[] = $item;
            }

            // inserts 作成
            $insertItems = [];
            if ($sprig->has_insert) {
                foreach ($sprig->insertSprigs as $insert) {
                    $insertBranch = optional($insert->branch);

                    $item = [
                        'node_type' => 'insert',

                        'branch_id'   => $insertBranch->id,
                        'branch_name' => $insertBranch->name,
                        'sprig_id'    => $insert->id,
                        'sprig_name'  => $insert->name,

                        // TODO 何なら leaf まで
                    ];
                    $outerItems[] = $item;
                }
            }


            $item = [
                'branch_id'   => $branch->id,
                'branch_name' => $branch->name,

                'node_id'     => $sprig->id,
                'node_title'  => $sprig->name,

                'current_content_id' => $leaf->id,
                'content'            => $leaf->content,
                'content_type'       => optional($leaf->leaf_type_id)->name,
                'content_type_id'    => $leaf->leaf_type_id,

                'node_is_head' => $sprig->is_head,
                'node_is_tail' => $sprig->is_tail,
                'node_has_origin' => $sprig->has_origin,
                'node_has_insert' => $sprig->has_insert,
                'node_is_plane' => !($sprig->has_origin or $sprig->has_insert or $sprig->has_tail),   // 描画用 util

                'outer_nodes' => $outerItems,

                'branch_created_at' => $branch->created_at,
            ];
            $array[] = $item;

            // 次を取ってくる
            $sprig = $sprig->parentSprig;
            if (!$sprig)    break;
        }

        return $array;
    }

}
