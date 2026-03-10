<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AppGroup;
use App\Models\AppRole;
use App\Models\AppGroupRole;
use App\Models\MainMenu;
use App\Models\Profit;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@board.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create test user
        $user = User::create([
            'name' => 'مستخدم تجريبي',
            'email' => 'user@board.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create groups
        $adminGroup = AppGroup::create([
            'name' => 'مديرين النظام',
        ]);

        $userGroup = AppGroup::create([
            'name' => 'مستخدمين عاديين',
        ]);

        // Create main menus first
        $menus = [
            ['name' => 'الرئيسية', 'name_en' => 'Home', 'rec_type' => 1, 'sort' => 1],
            ['name' => 'المساهمين', 'name_en' => 'Contributors', 'rec_type' => 1, 'sort' => 2],
            ['name' => 'معاملات الأسهم', 'name_en' => 'Shares Transactions', 'rec_type' => 1, 'sort' => 3],
            ['name' => 'بيع الأسهم', 'name_en' => 'Sell Shares', 'rec_type' => 1, 'sort' => 4],
            ['name' => 'المدفوعات', 'name_en' => 'Payments', 'rec_type' => 1, 'sort' => 5],
            ['name' => 'الاستطلاعات', 'name_en' => 'Polls', 'rec_type' => 1, 'sort' => 6],
            ['name' => 'الأرباح', 'name_en' => 'Profits', 'rec_type' => 1, 'sort' => 7],
            ['name' => 'أرباح المستخدمين', 'name_en' => 'Users Profits', 'rec_type' => 1, 'sort' => 8],
        ];

        $createdMenus = [];
        foreach ($menus as $menuData) {
            $createdMenus[] = MainMenu::create($menuData);
        }

        // Create roles
        $roles = [
            ['name' => 'إدارة المستخدمين', 'name_en' => 'User Management', 'controller_name' => 'Contributors', 'action_name' => 'index', 'main_id' => $createdMenus[1]->id],
            ['name' => 'إدارة المساهمين', 'name_en' => 'Contributors Management', 'controller_name' => 'Contributors', 'action_name' => 'index', 'main_id' => $createdMenus[1]->id],
            ['name' => 'إدارة الأسهم', 'name_en' => 'Shares Management', 'controller_name' => 'SharesTrans', 'action_name' => 'index', 'main_id' => $createdMenus[2]->id],
            ['name' => 'إدارة المدفوعات', 'name_en' => 'Payments Management', 'controller_name' => 'Payments', 'action_name' => 'index', 'main_id' => $createdMenus[4]->id],
            ['name' => 'إدارة الاستطلاعات', 'name_en' => 'Polls Management', 'controller_name' => 'Polls', 'action_name' => 'index', 'main_id' => $createdMenus[5]->id],
            ['name' => 'عرض التقارير', 'name_en' => 'View Reports', 'controller_name' => 'Home', 'action_name' => 'index', 'main_id' => $createdMenus[0]->id],
        ];

        foreach ($roles as $roleData) {
            AppRole::create($roleData);
        }

        // Assign all roles to admin group
        $allRoles = AppRole::all();
        foreach ($allRoles as $role) {
            AppGroupRole::create([
                'group_id' => $adminGroup->id,
                'role_id' => $role->id,
                'group_permission' => true,
            ]);
        }

        // Assign basic roles to user group
        $basicRoles = AppRole::whereIn('name', ['عرض التقارير'])->get();
        foreach ($basicRoles as $role) {
            AppGroupRole::create([
                'group_id' => $userGroup->id,
                'role_id' => $role->id,
                'group_permission' => true,
            ]);
        }


        // Create profit records
        $profitRecords = [
            ['date' => now(), 'end_date' => now()->addYear(), 'amount' => 100000.00, 'confirmed' => true],
            ['date' => now()->subMonths(3), 'end_date' => now()->addMonths(9), 'amount' => 50000.00, 'confirmed' => true],
            ['date' => now()->subMonth(), 'end_date' => now()->addMonths(11), 'amount' => 25000.00, 'confirmed' => false],
        ];

        foreach ($profitRecords as $profitData) {
            Profit::create($profitData);
        }

        // Create sample poll
        $poll = Poll::create([
            'question' => 'ما رأيك في خدمات الشركة؟',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_active' => true,
            'created_date' => now(),
            'created_by' => $admin->id,
        ]);

        // Create poll options
        $pollOptions = [
            'ممتاز',
            'جيد جداً',
            'جيد',
            'مقبول',
            'ضعيف',
        ];

        foreach ($pollOptions as $optionText) {
            PollOption::create([
                'poll_id' => $poll->id,
                'option_text' => $optionText,
                'votes' => rand(0, 50),
            ]);
        }

        // Assign users to groups
        $admin->groups()->attach($adminGroup->id);
        $user->groups()->attach($userGroup->id);

        $this->command->info('تم إنشاء البيانات الأساسية بنجاح!');
        $this->command->info('المدير: admin@board.com / password');
        $this->command->info('المستخدم: user@board.com / password');
    }
}
