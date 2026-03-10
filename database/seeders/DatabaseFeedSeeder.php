<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contributor;
use App\Models\SharesTrans;
use App\Models\ShareTransLine;
use App\Models\SellShares;
use App\Models\Payment;
use App\Models\Profit;
use App\Models\UsersProfit;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseFeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('بدء إنشاء البيانات التجريبية...');

        // Create additional users
        $this->createUsers();
        
        // Create contributors
        $this->createContributors();
        
        // Create share transactions
        $this->createShareTransactions();
        
        // Create sell shares
        $this->createSellShares();
        
        // Create payments
        $this->createPayments();
        
        // Create user profits
        $this->createUsersProfits();
        
        // Create additional polls
        $this->createPolls();
        
        // Create poll answers
        $this->createPollAnswers();

        $this->command->info('تم إنشاء البيانات التجريبية بنجاح!');
    }

    private function createUsers()
    {
        $this->command->info('إنشاء مستخدمين إضافيين...');
        
        $users = [
            ['name' => 'أحمد محمد', 'email' => 'ahmed@board.com'],
            ['name' => 'فاطمة علي', 'email' => 'fatima@board.com'],
            ['name' => 'محمد حسن', 'email' => 'mohammed@board.com'],
            ['name' => 'عائشة أحمد', 'email' => 'aisha@board.com'],
            ['name' => 'علي محمود', 'email' => 'ali@board.com'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }

    private function createContributors()
    {
        $this->command->info('إنشاء مساهمين...');
        
        $users = User::where('email', '!=', 'admin@board.com')
                    ->where('email', '!=', 'user@board.com')
                    ->get();

        foreach ($users as $index => $user) {
            Contributor::create([
                'user_id' => $user->id,
                'contributor_name' => $user->name,
                'phone' => '050' . str_pad($index + 1000000, 7, '0', STR_PAD_LEFT),
                'email' => $user->email,
                'address' => 'العنوان ' . ($index + 1),
                'shares_count' => rand(100, 10000),
                'share_value' => rand(10, 100),
                'total_value' => rand(1000, 1000000),
                'registration_date' => now()->subDays(rand(1, 365)),
                'is_active' => true,
            ]);
        }
    }

    private function createShareTransactions()
    {
        $this->command->info('إنشاء معاملات أسهم...');
        
        $contributors = Contributor::all();
        
        for ($i = 0; $i < 20; $i++) {
            $contributor = $contributors->random();
            
            $transaction = SharesTrans::create([
                'date' => now()->subDays(rand(1, 90)),
                'trans_type' => rand(0, 1),
                'posted' => rand(0, 1),
                'notes' => 'ملاحظات المعاملة ' . ($i + 1),
            ]);

            // Create transaction lines
            $lineCount = rand(1, 3);
            for ($j = 0; $j < $lineCount; $j++) {
                ShareTransLine::create([
                    'contributor_id' => $contributor->id,
                    'trans_id' => $transaction->id,
                    'count_debit' => rand(10, 1000),
                    'count_credit' => rand(0, 500),
                    'amount_per_share' => rand(5, 50),
                    'posted' => rand(0, 1),
                    'line_notes' => 'ملاحظات السطر ' . ($j + 1),
                ]);
            }
        }
    }

    private function createSellShares()
    {
        $this->command->info('إنشاء بيع أسهم...');
        
        $contributors = Contributor::all();
        
        for ($i = 0; $i < 15; $i++) {
            $contributor = $contributors->random();
            
            SellShares::create([
                'user_id' => $contributor->id,
                'count' => rand(50, 500),
                'amount_per_share' => rand(10, 100),
                'end_date' => now()->addDays(rand(30, 90)),
                'insert_date' => now()->subDays(rand(1, 60)),
                'ad_status' => rand(0, 2),
                'notes' => 'ملاحظات البيع ' . ($i + 1),
            ]);
        }
    }

    private function createPayments()
    {
        $this->command->info('إنشاء مدفوعات...');
        
        // First create some SharesPO records
        $contributors = Contributor::all();
        $sellShares = \App\Models\SellShares::all();
        $sharesPOs = [];
        
        for ($i = 0; $i < 10; $i++) {
            $contributor = $contributors->random();
            $sellShare = $sellShares->random();
            $sharesPO = \App\Models\SharesPO::create([
                'user_id' => $contributor->id,
                'sale_number' => $sellShare->id,
                'count' => rand(10, 100),
                'amount_per_share' => rand(5, 50),
                'accept' => rand(0, 1),
                'insert_date' => now()->subDays(rand(1, 30)),
                'po_status' => rand(0, 2),
            ]);
            $sharesPOs[] = $sharesPO;
        }
        
        // Now create payments
        for ($i = 0; $i < 25; $i++) {
            $sharesPO = $sharesPOs[array_rand($sharesPOs)];
            
            Payment::create([
                'date' => now()->subDays(rand(1, 30)),
                'amount' => rand(100, 10000),
                'shares_po_number' => $sharesPO->id,
                'bank_info' => 'معلومات البنك ' . ($i + 1),
                'confirmed' => rand(0, 1),
                'transfer_document' => 'مستند التحويل ' . ($i + 1),
            ]);
        }
    }

    private function createUsersProfits()
    {
        $this->command->info('إنشاء أرباح المستخدمين...');
        
        $contributors = Contributor::all();
        $profits = Profit::all();
        
        foreach ($contributors as $contributor) {
            $profitCount = rand(1, 3);
            $userProfits = $profits->random($profitCount);
            
            foreach ($userProfits as $profit) {
                UsersProfit::create([
                    'contributor_id' => $contributor->id,
                    'profits_id' => $profit->id,
                    'amount' => rand(100, 5000),
                ]);
            }
        }
    }

    private function createPolls()
    {
        $this->command->info('إنشاء استطلاعات إضافية...');
        
        $admin = User::where('email', 'admin@board.com')->first();
        
        $polls = [
            [
                'question' => 'ما هو رأيك في أداء الشركة هذا العام؟',
                'options' => ['ممتاز', 'جيد جداً', 'جيد', 'مقبول', 'ضعيف']
            ],
            [
                'question' => 'هل تفضل توزيع أرباح سنوية أم ربع سنوية؟',
                'options' => ['سنوية', 'ربع سنوية', 'لا يهمني']
            ],
            [
                'question' => 'ما هي الخدمات التي تريد تحسينها؟',
                'options' => ['خدمة العملاء', 'الموقع الإلكتروني', 'التقارير', 'التواصل']
            ],
            [
                'question' => 'هل أنت راضي عن شفافية الشركة؟',
                'options' => ['نعم تماماً', 'نعم إلى حد ما', 'لا', 'لا أعرف']
            ],
        ];

        foreach ($polls as $pollData) {
            $poll = Poll::create([
                'question' => $pollData['question'],
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->addDays(rand(30, 90)),
                'is_active' => true,
                'created_date' => now()->subDays(rand(1, 30)),
                'created_by' => $admin->id,
            ]);

            foreach ($pollData['options'] as $optionText) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => $optionText,
                    'votes' => rand(0, 100),
                ]);
            }
        }
    }

    private function createPollAnswers()
    {
        $this->command->info('إنشاء إجابات الاستطلاعات...');
        
        $polls = Poll::all();
        $users = User::all();
        
        foreach ($polls as $poll) {
            $pollOptions = $poll->pollOptions;
            $answerCount = rand(5, 15);
            
            for ($i = 0; $i < $answerCount; $i++) {
                $user = $users->random();
                $option = $pollOptions->random();
                
                // Check if user already answered this poll
                $existingAnswer = PollAnswer::where('poll_id', $poll->id)
                                          ->where('user_id', $user->id)
                                          ->first();
                
                if (!$existingAnswer) {
                    PollAnswer::create([
                        'poll_id' => $poll->id,
                        'poll_option_id' => $option->id,
                        'user_id' => $user->id,
                        'answer_date' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }
    }
}
