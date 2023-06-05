<?php

namespace Database\Seeders\FPI;

use App\Models\User;
use App\Models\FPI\Project;
use App\Zaions\Enums\FPI\Projects\ProjectMeasuringUnitTypeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'ahsan@zaions.com')->first();

        if ($user) {
            Project::create([
                'userId' => optional($user)->id,
                'uniqueId' => uniqid(),
                'title' => 'demo project',
                'description' => 'demo project description',
                'perUnitPrice' => 10,
                'unitMeasuredIn' => ProjectMeasuringUnitTypeEnum::squareFeet->name,
                'whyInvest' => 'whyInvest',
                'location' => 'lahore',
                'type' => 'house',
                'rebatePercentage' => 14,
                'totalUnits' => 1000,
                'remainingUnits' => 1000,
                'sortOrderNo' => 1
            ]);
        }
    }
}
