<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->foreignId('skpd_id')->constrained('skpd')->onDelete('cascade');
      $table->string('name');
      $table->string('username')->unique();
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('phone')->nullable();
      $table->string('address')->nullable();
      $table->string('photo')->nullable();
      $table->date('birth_date')->nullable();
      $table->enum('role', ['Administrator', 'SKPD']);
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('users');
  }
}
