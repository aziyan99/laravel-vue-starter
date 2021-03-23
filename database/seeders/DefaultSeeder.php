<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'super admin',
            'guard_name' => 'web'
        ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole('super admin');

        Permission::insert(
            [
                [
                    'name' => 'dashbor.lihat',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'role.lihat',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'role.ubah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'role.tambah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'role.hapus',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'permission.lihat',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'permission.ubah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'permission.tambah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'permission.hapus',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'assignpermission.lihat',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'assignpermission.ubah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'pengguna.lihat',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'pengguna.ubah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'pengguna.tambah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'pengguna.hapus',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'pengaturan.lihat',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'name' => 'pengaturan.ubah',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]
        );

        Setting::create([
            'web_name' => 'LV STARTER',
            'logo' => 'default.png',
            'address' => 'JALAN MAWAR Gang Gagak 3 No.32',
            'email' => 'lvstarter@contact.com',
            'phone_number' => '(62) 823 4454 2232',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com'
        ]);
    }
}
