<?php

use App\User;
use App\Role;
use App\Models\Status;
use App\Models\Cabinet;
use App\Models\Country;
use App\Models\Package;
use App\Services\Sib\WalletService;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'                  => 'SIB',
                'last_name'             => 'Company',
                'login'                 => 'info@sib.company',
                'email'                 => 'info@sib.company',
                'password'              => bcrypt(env('TEST_PASSWORD', '#zrfs-0dfa-1Ggop-@vCpr')),
                'phone'                 => '+77780086070',
                'city'                  => 'Astana',
                'photo'                 => '/img/avatars/sib.png',
                'birthday'              => date('Y-m-d', strtotime('01.07.2017')),
                'is_active'             => true,
                'is_qualified'          => false,
                'is_oss_ever'           => true,
                'has_activity_sib'      => true,
                'has_activity_oss'      => true,
                'activated_at'          => date('Y-m-d', strtotime('01.07.2017')),
                'oss_activated_at'      => date('Y-m-d', strtotime('01.07.2017')),
                'created_at'            => date('Y-m-d', strtotime('01.07.2017')),
                'oss_registered_at'     => date('Y-m-d', strtotime('01.07.2017')),
                'sib_registered_at'     => date('Y-m-d', strtotime('01.07.2017')),
                'is_wizard_activated'   => true,
                'tree_node_id'          => 1,
            ],
        ]);

        $cabinet = Cabinet::where('code', 'sib')->first();
        $status = Status::where('code', 'pk')->first();
        $package = Package::where('code', 'vip')->first();
        $country = Country::where('name', 'Казахстан')->first();
        $roles = Role::whereIn('slug', ['admin', 'finance-manager', 'partner'])->get();

        $user = User::find(1);

        $user->roles()->sync($roles);
        $user->country()->associate($country);
        $user->cabinet()->associate($cabinet);
        $user->status()->associate($status);
        $user->package()->associate($package);

        (new WalletService())->generateWallet($user);

        $user->save();
    }
}
