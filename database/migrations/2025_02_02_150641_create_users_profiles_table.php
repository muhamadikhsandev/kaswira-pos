<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('users_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi dengan tabel users
            $table->string('profile_photo_path')->nullable(); // Kolom untuk foto profil
            $table->string('name')->nullable(); // Kolom untuk nama
            $table->string('phone')->nullable(); // Kolom untuk nomor telepon
            $table->text('address')->nullable(); // Kolom untuk alamat
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_profiles');
    }
}
