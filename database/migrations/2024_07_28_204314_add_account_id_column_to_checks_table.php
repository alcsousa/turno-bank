<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('account_id')->after('id');

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            $table->dropForeign('checks_account_id_foreign');
            $table->dropColumn('account_id');
            $table->unsignedBigInteger('user_id');
        });
    }
};
