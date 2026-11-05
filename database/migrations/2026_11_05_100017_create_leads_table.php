<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The prospect business');
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('source')->default('referral')->comment('Allowed values: referral, walk_in, campaign, website');
            $table->string('stage')->default('new')->comment('Allowed values: new, contacted, qualified, proposal, won, lost');
            $table->decimal('estimated_value', 12, 2)->default(0);
            $table->foreignId('route_id')->nullable()->constrained('routes')->nullOnDelete();
            $table->string('owner')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
