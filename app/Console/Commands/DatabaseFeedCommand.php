<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DatabaseFeedSeeder;

class DatabaseFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:feed {--fresh : Clear existing data before feeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Feed the database with sample data for testing and development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 بدء تغذية قاعدة البيانات بالبيانات التجريبية...');
        
        if ($this->option('fresh')) {
            $this->warn('⚠️  سيتم حذف البيانات الموجودة أولاً...');
            if ($this->confirm('هل أنت متأكد من حذف البيانات الموجودة؟')) {
                $this->call('migrate:fresh', ['--seed' => true]);
                $this->info('✅ تم إعادة إنشاء قاعدة البيانات والبيانات الأساسية');
            } else {
                $this->info('❌ تم إلغاء العملية');
                return;
            }
        }

        $this->info('📊 إنشاء البيانات التجريبية...');
        
        $seeder = new DatabaseFeedSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('🎉 تم تغذية قاعدة البيانات بنجاح!');
        $this->info('📈 يمكنك الآن اختبار جميع الميزات بالبيانات التجريبية');
        
        // Show summary
        $this->showSummary();
    }

    private function showSummary()
    {
        $this->info('');
        $this->info('📋 ملخص البيانات المُنشأة:');
        $this->table(
            ['النوع', 'العدد'],
            [
                ['المستخدمين', \App\Models\User::count()],
                ['المساهمين', \App\Models\Contributor::count()],
                ['معاملات الأسهم', \App\Models\SharesTrans::count()],
                ['تفاصيل المعاملات', \App\Models\ShareTransLine::count()],
                ['بيع الأسهم', \App\Models\SellShares::count()],
                ['المدفوعات', \App\Models\Payment::count()],
                ['الأرباح', \App\Models\Profit::count()],
                ['أرباح المستخدمين', \App\Models\UsersProfit::count()],
                ['الاستطلاعات', \App\Models\Poll::count()],
                ['خيارات الاستطلاعات', \App\Models\PollOption::count()],
                ['إجابات الاستطلاعات', \App\Models\PollAnswer::count()],
            ]
        );
        
        $this->info('');
        $this->info('🔑 بيانات الدخول:');
        $this->info('   المدير: admin@board.com / password');
        $this->info('   المستخدم: user@board.com / password');
        $this->info('');
        $this->info('🌐 لتشغيل التطبيق: php artisan serve');
    }
}
