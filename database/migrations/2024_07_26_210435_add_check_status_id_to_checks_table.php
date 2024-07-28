<?php

use App\Models\CheckStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->unsignedBigInteger('check_status_id')
                ->after('user_id')
                ->default(CheckStatus::PENDING);

            $table->foreign('check_status_id')
                ->references('id')
                ->on('check_statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropForeign(['check_status_id']);
            $table->dropColumn(['check_status_id']);
        });
    }
};
