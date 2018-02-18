<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeafBearRequest;
use App\Http\Requests\LeafBranchRequest;
use App\Http\Requests\TreeBearRequest;
use App\Http\Requests\TreeGraftRequest;
use App\Http\Requests\TreeGrowAndBearRequest;
use App\Http\Requests\TreeGrowRequest;
use App\Http\Requests\TreePlantRequest;
use App\Http\Requests\TreeRamifyRequest;
use App\models\Branch;
use App\Models\Fruit;
use App\Models\Leaf;
use App\models\Sprig;
use App\Models\Tree;
use Illuminate\Http\Request;

class TreeActionController extends Controller {


    public function __construct() {
        $this->middleware('auth');
    }

    public function grow(TreeGrowRequest $request) {
        \DB::transaction(function() use ($request) {
            $branch = Branch::findOrFail($request->get('branch_id'));
            $branch->growMethod($request->get('name'));
        });

        return back();
    }

    public function bear(TreeBearRequest $request) {
        \DB::transaction(function() use ($request) {
            $sprig = Sprig::findOrFail($request->get('sprig_id'));
            $sprig->bearMethod([
                'leaf_type_id' => $request->get('leaf_type_id'),
                'revision'     => $request->get('revision'),
                'content'      => $request->get('content'),
            ]);
        });

        return back();
    }

    public function growAndBear(TreeGrowAndBearRequest $request) {
        \DB::transaction(function() use ($request) {
            $branch = Branch::findOrFail($request->get('branch_id'));
            $sprig = $branch->growAndBearMethod($request->get('sprig_name'), [
                'leaf_type_id' => $request->get('leaf_type_id'),
                'revision'     => $request->get('revision'),
                'content'      => $request->get('content'),
            ]);
        });

        return back();
    }

    public function plant(TreePlantRequest $request) {
        \DB::transaction(function() use ($request) {
            $branch = Branch::create(['name' => $request['name']]);
        });

        return back();
    }

    public function ramify(TreeRamifyRequest $request) {
        \DB::transaction(function() use ($request) {
            $sprig = Sprig::findOrFail($request->get('sprig_id'));
            $sprig->ramifyMethod($request->get('name'));
        });

        return back();
    }

    public function graft(TreeGraftRequest $request) {
        \DB::transaction(function() use ($request) {
            $sprig = Sprig::findOrFail($request->get('sprig_id'));

            $branch = Branch::findOrFail($request->get('branch_id'));
            $branch->graftMethod($request->get('name'), $sprig);
        });

        return back();
    }

}
