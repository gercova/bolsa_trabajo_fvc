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
        'google_maps_iframe',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getDefault() {
        $enterprise = self::first();
        
        if (!$enterprise) {
            $enterprise = new self();
            
            $enterprise->logo_path          = 'enterprise/logo-iestpfvc.png';
            $enterprise->favicon_path       = 'enterprise/favicon-iestpfvc.png';
            $enterprise->company_name       = 'IESTP Francisco Vigo Caballero';
            $enterprise->address            = 'Av. Principal 123';
            $enterprise->city               = 'Uchiza';
            $enterprise->phone_number_1     = '+51 123 456 789';
            $enterprise->email              = 'info@fvigo.edu';
            $enterprise->google_maps_iframe = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.886026543033!2d-76.46860822416807!3d-8.449056191591522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91af63dcf2a7d793%3A0x39afb5dd2aae7783!2sFRANCISCO%20VIGO%20CABALLERO!5e0!3m2!1ses!2spe!4v1740000000000!5m2!1ses!2spe" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        }
        
        return $enterprise;
    }

    /**
     * Safely get the Google Maps embed URL from iframe or URL string.
     */
    public function getMapEmbedSrc(): string
    {
        $iframe = $this->google_maps_iframe;
        if (empty($iframe)) {
            return 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.886026543033!2d-76.46860822416807!3d-8.449056191591522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91af63dcf2a7d793%3A0x39afb5dd2aae7783!2sFRANCISCO%20VIGO%20CABALLERO!5e0!3m2!1ses!2spe!4v1740000000000!5m2!1ses!2spe';
        }

        // If it's a full iframe tag, extract src
        if (preg_match('/src=["\'](.*?)["\']/i', $iframe, $matches)) {
            return $matches[1];
        }

        return $iframe;
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