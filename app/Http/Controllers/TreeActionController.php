<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeafBearRequest;
use App\Http\Requests\TreeGrowRequest;
use App\Http\Requests\TreePlantRequest;
use App\Models\Fruit;
use App\Models\Leaf;
use App\Models\Tree;
use Illuminate\Http\Request;

class TreeActionController extends Controller {

    public function growTree(TreeGrowRequest $request) {
        \DB::transaction(function() use ($request){

            $tree = Tree::findOrFail($request['tree_id']);

            $leaf  = Leaf::create();
            $fruit = Fruit::create([
                'fruit_type_id' => $request['fruit_type_id'],
                'title'         => $request['title'],
                'content'       => $request['content'],
            ]);

            $leaf->bearMethod($fruit);
            $tree->growMethod($leaf);
        });

        return back();
    }

    public function bearLeaf(LeafBearRequest $request) {
        \DB::transaction(function() use ($request){

            $leaf  = Leaf::findOrFail($request['leaf_id']);
            $fruit = Fruit::create([
                'fruit_type_id' => $request['fruit_type_id'],
                'title'         => $request['title'],
                'content'       => $request['content'],
            ]);

            $leaf->bearMethod($fruit);
        });

        return back();
    }

    public function plantTree(TreePlantRequest $request) {
        \DB::transaction(function() use ($request){

            $tree = Tree::create(['name' => $request['name']]);
        });

        return back();
    }

}
