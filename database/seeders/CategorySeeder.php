<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::factory()->createMany([
            [
                'name' => 'Technical Issues',
                'slug' => 'technical-issues',
                'description' => 'Problems related to technical systems, software, or hardware',
                'is_active' => true,
            ],
            [
                'name' => 'Service Complaints',
                'slug' => 'service-complaints',
                'description' => 'Issues with customer service quality or response time',
                'is_active' => true,
            ],
            [
                'name' => 'Billing Issues',
                'slug' => 'billing-issues',
                'description' => 'Problems with invoices, payments, or billing accuracy',
                'is_active' => true,
            ],
            [
                'name' => 'Product Quality',
                'slug' => 'product-quality',
                'description' => 'Concerns about product quality, defects, or specifications',
                'is_active' => true,
            ],
            [
                'name' => 'General Feedback',
                'slug' => 'general-feedback',
                'description' => 'General suggestions, comments, or feedback',
                'is_active' => true,
            ],
        ]);
    }
}
