<?php

namespace App\Http\Controllers;

use App\models\Branch;
use App\models\Sprig;
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

}
