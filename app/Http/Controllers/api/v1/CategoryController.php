<?php


namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repository\Response;

class CategoryController extends Controller
{
    public function list(){
        try {
            $data = [];
            $records = Category::whereNull('parent_id')->get();
            foreach ($records as $record){
                $childLists = [];
                $childs = Category::where('parent_id',$record->id)->get();
                foreach ($childs as $child){
                    $childLists[] = [
                        'id'=>$child->id,
                        'name'=>$child->category_name,
                        'tree_ids'=>$record->id.','.$child->id
                    ];
                }
                $data[] = [
                    'id'=>$record->id,
                    'name'=>$record->category_name,
                    'childs'=>$childLists,
                ];
            }
            return Response::Success($data,'Category Lists');
        } catch (\Exception $e) {
            return Response::Error([],$e->getMessage());
        }
    }
}
