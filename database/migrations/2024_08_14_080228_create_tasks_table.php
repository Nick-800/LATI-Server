<?php

use App\Models\Server;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Server::class);
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('before_date')->nullable();
            $table->integer('priority');
            $table->enum('status', ['Not-Started', 'In-Progress', 'Completed', 'Cancelled'])->default('Not-Started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
