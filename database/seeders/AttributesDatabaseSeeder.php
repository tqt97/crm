<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AttributesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $type = [
            'text' => 0,
            'selectbox' => 1,
        ];

        $categoryAttributes = [
            [
                'name' => 'Ram',
                'type' => $type['selectbox'],
                'values' => ['4GB', '8GB', '16GB', '32GB', '64GB'],
            ],
            [
                'name' => 'Ổ cứng',
                'type' => $type['selectbox'],
                'values' => [
                    '60GB', '120GB', '128GB', '240GB', '250GB', '256GB', '480GB',
                    '500GB', '512GB', '1TB', '2TB', '4TB'
                ],
            ],
            [
                'name' => 'Loại ổ cứng',
                'type' => $type['selectbox'],
                'values' => ['HDD', 'SSD'],
            ],
            [
                'name' => 'CPU',
                'type' => $type['selectbox'],
                'values' => [
                    'Intel Celeron', 'Intel Pentium', 'Intel Core i3', 'Intel Core i5', 'Intel Core i7',
                    'Intel Core i9', 'Intel Xeon', 'AMD Athlon', 'AMD A Series', 'AMD Ryzen 3',
                    'AMD Ryzen 5', 'AMD Ryzen 7', 'AMD Ryzen 9', 'AMD Threadripper', 'Apple A12 Bionic',
                    'Apple A13 Bionic', 'Apple A14 Bionic', 'Apple A15 Bionic', 'Apple A16 Bionic', 'Apple M1',
                    'Apple M1 Pro', 'Apple M1 Max', 'Apple M1 Ultra', 'Apple M2', 'Apple M2 Pro',
                    'Apple M2 Max', 'Apple M2 Ultra', 'Apple M3', 'Apple M3 Pro', 'Apple M3 Max', 'Apple M3 Ultra'
                ],
            ],
            [
                'name' => 'Kích thước màn hình',
                'type' => $type['selectbox'],
                'values' => [
                    '11 inch', '12 inch', '13 inch', '13.3 inch', '14 inch', '15 inch', '15.6 inch',
                    '16 inch', '17 inch', '17.3 inch', '18 inch', '19 inch', '20 inch', '21 inch',
                    '21.5 inch', '22 inch', '23 inch', '23.6 inch', '23.8 inch', '24 inch', '24.5 inch',
                    '25 inch', '27 inch', '28 inch', '29 inch', '30 inch', '31.5 inch', '32 inch', '34 inch'
                ],
            ],
            [
                'name' => 'Độ phân giải màn hình',
                'type' => $type['selectbox'],
                'values' => [
                    'HD', 'HD+', 'Full HD', 'UWHD', 'WUXGA', 'UHD', '4K', 'QHD', 'WQHD', 'DualQHD',
                    'Retina', 'Retina 4K', 'Retina 5K', 'Retina 6K', 'Liquid Retina XDR', 'Liquid Retina XDR 16'
                ],
            ],
            [
                'name' => 'Kích thước',
                'type' => $type['text'],
                'values' => ['14cm'],
            ],
            [
                'name' => 'Đầu ra',
                'type' => $type['text'],
                'values' => ['15cm'],
            ]
        ];
        // Insert categories
        $categoryID = DB::table('categories')->insertGetId([
            'name' => 'Laptop',
            'code' => $faker->unique()->numerify('LAPTOP-###'),
            'description' => 'Laptop faker description !!!',
            'created_by' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        // category Attributes
        foreach ($categoryAttributes as $group) {
            // Insert category attribute and get its ID
            $categoryAttributeID = DB::table('category_attributes')->insertGetId([
                'category_id' => $categoryID,
                'name' => $group['name'],
                'data_type' => $group['type'],
                'created_by' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            // If the group has values, prepare them for batch insertion
            if (isset($group['values'])) {
                foreach ($group['values'] as $value) {
                    // Insert attribute option
                    DB::table('attribute_options')->insert([
                        'category_attribute_id' => $categoryAttributeID,
                        'value' => $value,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

    }
}
