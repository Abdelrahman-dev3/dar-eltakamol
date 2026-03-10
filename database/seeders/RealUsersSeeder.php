<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RealUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Importing real users from SQL Server data...');

        $realUsers = [
            [
                'name' => 'محمد عمارة',
                'id_number' => null,
                'email' => 'mohmdemara@gmail.com',
                'phone' => '966543330848',
                'password' => Hash::make('password'), // Reset password for Laravel
            ],
            [
                'name' => 'هيلة الخضيري',
                'id_number' => '1016056390',
                'email' => 'Meshal1993m@gmail.com',
                'phone' => '966554210064',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'خلود إبراهيم العضيبي',
                'id_number' => '1234567916',
                'email' => 'kenf3344@gmail.com',
                'phone' => '966507298888',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'عبد الرحمن إبراهيم العضيبي',
                'id_number' => '1016056423',
                'email' => 'aaso2004@gmail.com',
                'phone' => '966555185825',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'محمد إبرهيم العضيبي',
                'id_number' => '1234567892',
                'email' => 'abueight@yahoo.com',
                'phone' => '966505822410',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'عفراء إبراهيم العضيبي',
                'id_number' => '1234567903',
                'email' => 'afraa14191@gmail.com',
                'phone' => '966506106106',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'هيلة إبراهيم العضيبي',
                'id_number' => '1234567907',
                'email' => 'helah7979@gmail.com',
                'phone' => '966506148432',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'لولوة إبراهيم العضيبي',
                'id_number' => '1016056333',
                'email' => 'loolo7890@gmail.com',
                'phone' => '966543388387',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'أسماء إبراهيم العضيبي',
                'id_number' => '1016056465',
                'email' => 'Asma10100@gmail.com',
                'phone' => '966554256600',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'مشعل إبراهيم العضيبي',
                'id_number' => '1074720374',
                'email' => 'meshal0533@gmail.com',
                'phone' => '966533133525',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'فاطمة إبراهيم العضيبي',
                'id_number' => '1234567902',
                'email' => 'Fatimahlodaiby91@gmail.com',
                'phone' => '966551220088',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'محمد السيد',
                'id_number' => null,
                'email' => 'financemanager@fath.com.sa',
                'phone' => '966565035434',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'العنود إبراهيم العضيبي',
                'id_number' => '1068261435',
                'email' => 'alonoud988@gmail.com',
                'phone' => '966590066683',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'عبد العزيز إبراهيم العضيبي',
                'id_number' => '1234567893',
                'email' => 'abdulazizzzzz@hotmail.com',
                'phone' => '966505459552',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'نورة إبراهيم العضيبي',
                'id_number' => '1234567904',
                'email' => 'Omaboody36@gmail.com',
                'phone' => '966500565588',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'منال إبراهيم العضيبي',
                'id_number' => '1234567909',
                'email' => 'mano33j@gmail.com',
                'phone' => '966552402601',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Azzam',
                'id_number' => '1068261435',
                'email' => 'It@dar-altakamol.com',
                'phone' => '966535833444',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'ماجد إبراهيم العضيبي',
                'id_number' => '1016056374',
                'email' => 'majed@live.cn',
                'phone' => '966551559992',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'منيرة إبراهيم العضيبي',
                'id_number' => '1234567912',
                'email' => 'mnoorr3344@gmail.com',
                'phone' => '966533393632',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'هند إبراهيم العضيبي',
                'id_number' => '1234567917',
                'email' => 'alhind40@gmail.com',
                'phone' => '966538341111',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'فيصل إبراهيم العضيبي',
                'id_number' => '1016056416',
                'email' => 'faisal22244@gmail.com',
                'phone' => '966533293355',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'فاطمة إبراهيم العضيبي',
                'id_number' => '1234567902',
                'email' => 'Fatimahalodaiby91@gmail.com',
                'phone' => '966551220088',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'مقرن إبراهيم العضيبي',
                'id_number' => null,
                'email' => 'alodaiby@gmail.com',
                'phone' => '966500089333',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'حنان إبراهيم العضيبي',
                'id_number' => '1234567910',
                'email' => 'h.t.88@hotmail.com',
                'phone' => '966531355776',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'الهنوف إبراهيم العضيبي',
                'id_number' => '1234567915',
                'email' => 'a7745@hotmail.com',
                'phone' => '966533297777',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($realUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'id_number' => $userData['id_number'],
                    'phone' => $userData['phone'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Successfully imported ' . count($realUsers) . ' real users!');
        $this->command->info('All users have password reset to: password');
    }
}
