<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->date('date');
            $table->tinyInteger('status')->default(1)->comment('1: in-progress, 2: needs fix, 3: approved');
            $table->text('message')->nullable();
            $table->boolean('use_transportation_expense')->default(false)->comment('0: No,1: Yes');
            $table->tinyInteger('transportation_expense_type')->default(0)->comment('0: None, 1: Use Default, 2: Other');
            $table->unsignedInteger('transportation_expense')->default(0)->nullable()->comment('Default: 0, max: 99999');
            $table->boolean('use_shift')->default(false);
            $table->smallInteger('shift_start_time')->nullable();
            $table->smallInteger('shift_end_time')->nullable();
            $table->smallInteger('shift_break_time')->nullable();
            $table->boolean('use_off_shift')->default(false);
            $table->smallInteger('off_shift_qa_time')->nullable();
            $table->smallInteger('off_shift_training_time')->nullable();
            $table->smallInteger('off_shift_absent_time')->nullable();
            $table->smallInteger('off_shift_additional_time')->nullable();
            $table->boolean('off_shift_break_time_type')->nullable()->comment('0 : 追加事務では休憩時間をとっていない, 1: 追加事務には休憩時間を除いた時間を入力した');
            $table->text('off_shift_blog_url')->nullable();
            $table->text('off_shift_notes')->nullable();
            $table->boolean('use_night_work')->nullable()->default(false);
            $table->smallInteger('night_time')->nullable()->comment('10:30 → 1030');
            $table->boolean('training_type')->default(false)->nullable()->comment('"type of 特訓時間: 0: 本日の特訓はなし, 1 : 時間数を入れる"');
            $table->smallInteger('training_time')->nullable()->comment('10:30 → 1030');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_times');
    }
};
