<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'student_name_en',
        'student_name_bn',
        'birth_date',
        'gender',
        'religion',
        'class_applied',
        'father_name',
        'mother_name',
        'guardian_name',
        'father_occupation',
        'mother_occupation',
        'contact_phone',
        'contact_email',
        'address',
        'student_photo',
        'birth_certificate',
        'guardian_nid',
        'status',
        'test_date',
        'test_venue',
        'admin_notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'test_date' => 'datetime'
    ];

    public static function generateApplicationId()
    {
        $year = date('Y');
        $lastApp = self::whereYear('created_at', $year)
                      ->orderBy('id', 'desc')
                      ->first();
        
        $number = $lastApp ? intval(substr($lastApp->application_id, -4)) + 1 : 1;
        return 'ADM' . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger'
        ];
        
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function scopeByClass($query, $class)
    {
        return $query->where('class_applied', $class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('student_name_en', 'like', "%{$search}%")
              ->orWhere('application_id', 'like', "%{$search}%")
              ->orWhere('contact_phone', 'like', "%{$search}%");
        });
    }
}
