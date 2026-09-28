<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Personal Finance', 'Investing', 'Banking', 'Credit', 'Business', 'Saving', 'Retirement', 'Insurance', 'Taxes', 'Fintech', 'Loans'] as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}
