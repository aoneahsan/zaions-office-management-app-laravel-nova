<?php

namespace Database\Seeders;

use App\Models\User;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', RolesEnum::superAdmin->name)->get();
        $adminRole = Role::where('name', RolesEnum::admin->name)->get();
        $simpleUserRole = Role::where('name', RolesEnum::simpleUser->name)->get();
        $brokerRole = Role::where('name', RolesEnum::broker->name)->get();
        $developerRole = Role::where('name', RolesEnum::developer->name)->get();
        $investorRole = Role::where('name', RolesEnum::investor->name)->get();

        // create superAdmin user
        $ahsanUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'ahsan',
            'slug' => 'ahsan',
            'email' => 'ahsan@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619706',
            'cnic' => '3520209598315',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);
        $superAdminUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'superAdmin',
            'slug' => 'superAdmin',
            'email' => 'superAdmin@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619705',
            'cnic' => '3520209598316',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);

        // create admin user
        $adminUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'admin',
            'slug' => 'admin',
            'email' => 'admin@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619707',
            'cnic' => '3520209598317',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);

        // create simpleUser user
        $simpleUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'user',
            'slug' => 'user',
            'email' => 'user@zaions.com',
            'password' => Hash::make("password"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619708',
            'cnic' => '3520209598318',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);

        // create broker user
        $brokerUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'broker',
            'slug' => 'broker',
            'email' => 'broker@zaions.com',
            'password' => Hash::make("password"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619709',
            'cnic' => '3520209598319',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);

        // create developer user
        $developerUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'developer',
            'slug' => 'developer',
            'email' => 'developer@zaions.com',
            'password' => Hash::make("password"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619710',
            'cnic' => '3520209598333',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);

        // create investor user
        $investorUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'investor',
            'slug' => 'investor',
            'email' => 'investor@zaions.com',
            'password' => Hash::make("password"),
            'email_verified_at' => Carbon::now(),
            'phoneNumber' => '03046619711',
            'cnic' => '3520209598310',
            'referralCode' => ZHelpers::getUniqueReferralCode()
        ]);

        // Assign Roles
        $ahsanUser->assignRole($superAdminRole);
        $superAdminUser->assignRole($superAdminRole);
        $adminUser->assignRole($adminRole);
        $simpleUser->assignRole($simpleUserRole);
        $brokerUser->assignRole($brokerRole);
        $developerUser->assignRole($developerRole);
        $investorUser->assignRole($investorRole);
    }
}
