<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class Categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            [
                'id'=>1,
                'parent_id'=>NULL,
                'category_name'=>'Electronics',
            ],
            [
                'id'=>2,
                'parent_id'=>1,
                'category_name'=>'Television',
            ],
            [
                'id'=>3,
                'parent_id'=>1,
                'category_name'=>'Refrigerator',
            ],
            [
                'id'=>4,
                'parent_id'=>1,
                'category_name'=>'Mobile',
            ],
            [
                'id'=>5,
                'parent_id'=>NULL,
                'category_name'=>'Fashion',
            ],
            [
                'id'=>6,
                'parent_id'=>5,
                'category_name'=>'T-Shirt',
            ],
            [
                'id'=>7,
                'parent_id'=>5,
                'category_name'=>'Jeans',
            ],
            [
                'id'=>8,
                'parent_id'=>5,
                'category_name'=>'Saree',
            ]
        ];


        Category::insert($data);
    }
}
