<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika berbeda dari nama model (jika tidak mengikuti konvensi plural)
    protected $table = 'users_profiles'; // Pastikan sesuai dengan nama tabel yang Anda buat

    // Kolom yang dapat diisi
    protected $fillable = ['user_id', 'name', 'phone', 'address', 'profile_photo_path'];

    /**
     * Define the inverse relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // Relasi terbalik (belongsTo) ke User
    }
}


