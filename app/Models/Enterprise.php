<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Enterprise extends Model
{
    protected $table        = 'enterprise';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'ruc',
        'company_name',
        'trade_name',
        'legal_representative_dni',
        'legal_representative',
        'address',
        'city',
        'business_sector',
        'phrase',
        'description',
        'vision',
        'mission',
        'phone_number_1',
        'phone_number_2',
        'email',
        'facebook_link',
        'linkedin_link',
        'twitter_link',
        'instagram_link',
        'whatsapp_link',
        'principles',
        'values',
        'color',
        'logo_path',
        'favicon_path',
        'complaints_book_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getDefault() {
        $enterprise = self::first();
        
        if (!$enterprise) {
            $enterprise = new self();
            
            $enterprise->logo_path      = 'enterprise/logo-iestpfvc.png';
            $enterprise->favicon_path   = 'enterprise/favicon-iestpfvc.png';
            $enterprise->company_name   = 'IESTP Francisco Vigo Caballero';
            $enterprise->address        = 'Av. Principal 123';
            $enterprise->city           = 'Uchiza';
            $enterprise->phone_number_1 = '+51 123 456 789';
            $enterprise->email          = 'info@fvigo.edu';
        }
        
        return $enterprise;
    }

    // Accessors para cada campo de imagen o archivo
    protected function logoPath(): Attribute {
        return $this->resolveImageUrl($this->attributes['logo_path'] ?? 'enterprise/favicons/logo-iestpfvc.png');
    }

    protected function faviconPath(): Attribute {
        return $this->resolveImageUrl($this->attributes['favicon_path'] ?? 'enterprise/favicons/logo-iestpfvc.png');
    }

    protected function complaintsBookPath(): Attribute {
        return Attribute::make(
            get: fn (?string $value) => match (true) {
                empty($value) => null,
                Str::startsWith($value, ['http://', 'https://']) => $value,
                default => Storage::url($value),
            }
        );
    }

    // Método reutilizable para la lógica de imágenes
    private function resolveImageUrl(?string $value) {
        return Attribute::make(
            get: fn () => match (true) {
                empty($value) => Storage::url('enterprise/favicons/logo-iestpfvc.png'),
                Str::startsWith($value, ['http://', 'https://']) => $value,
                default => Storage::url($value),
            }
        );
    }
}