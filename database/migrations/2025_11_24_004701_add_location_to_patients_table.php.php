<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('house_id')->nullable()->constrained()->onDelete('set null');
            $table->string('specific_address')->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['house_id']);
            $table->dropColumn(['house_id', 'specific_address']);
        });
    }
};