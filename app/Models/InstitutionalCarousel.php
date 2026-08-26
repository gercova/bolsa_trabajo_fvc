<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class InstitutionalCarousel extends Model
{
    protected $table = 'institutional_carousels';

    protected $fillable = [
        'tag',
        'tag_icon',
        'tag_color',
        'title',
        'highlight_text',
        'description',
        'primary_button_text',
        'primary_button_url',
        'primary_button_icon',
        'secondary_button_text',
        'secondary_button_url',
        'secondary_button_icon',
        'indicator_label',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relación polimórfica: Imagen principal del slide
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Relación polimórfica: Imágenes asociadas
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Accesor para obtener la URL de la imagen del carrusel.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && $this->image->url) {
            return $this->image->url;
        }

        return asset('images/slider_admision.jpg');
    }

    /**
     * Resuelve el enlace del botón primario (ruta con nombre, url relativa o absoluta).
     */
    public function getPrimaryButtonLinkAttribute(): string
    {
        return $this->resolveLink($this->primary_button_url);
    }

    /**
     * Resuelve el enlace del botón secundario (ruta con nombre, url relativa o absoluta).
     */
    public function getSecondaryButtonLinkAttribute(): string
    {
        return $this->resolveLink($this->secondary_button_url);
    }

    /**
     * Resuelve un link a URL absoluta, relativa o ruta nombrada.
     */
    protected function resolveLink(?string $url): string
    {
        if (empty($url)) {
            return '#';
        }

        if (Str::startsWith($url, ['http://', 'https://', '#', 'mailto:', 'tel:'])) {
            return $url;
        }

        if (Route::has($url)) {
            return route($url);
        }

        if (Str::startsWith($url, '/')) {
            return url($url);
        }

        return url('/' . $url);
    }

    /**
     * Configuración de estilos visuales por color de acento
     */
    public function getThemeStylesAttribute(): array
    {
        $color = $this->tag_color ?? 'amber';

        return match ($color) {
            'sky' => [
                'badge_bg'        => 'bg-sky-500/20',
                'badge_border'    => 'border-sky-400/40',
                'badge_text'      => 'text-sky-300',
                'badge_icon'      => 'text-sky-400',
                'gradient_text'   => 'from-sky-300 via-cyan-300 to-indigo-300',
                'btn_primary'     => 'from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-sky-500/25',
                'btn_sec_icon'    => 'text-sky-300',
            ],
            'rose' => [
                'badge_bg'        => 'bg-rose-500/20',
                'badge_border'    => 'border-rose-400/40',
                'badge_text'      => 'text-rose-300',
                'badge_icon'      => 'text-rose-400',
                'gradient_text'   => 'from-rose-300 via-pink-300 to-amber-300',
                'btn_primary'     => 'from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white shadow-rose-500/25',
                'btn_sec_icon'    => 'text-rose-300',
            ],
            'emerald' => [
                'badge_bg'        => 'bg-emerald-500/20',
                'badge_border'    => 'border-emerald-400/40',
                'badge_text'      => 'text-emerald-300',
                'badge_icon'      => 'text-emerald-400',
                'gradient_text'   => 'from-emerald-300 via-teal-300 to-lime-300',
                'btn_primary'     => 'from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white shadow-emerald-500/25',
                'btn_sec_icon'    => 'text-emerald-300',
            ],
            'indigo' => [
                'badge_bg'        => 'bg-indigo-500/20',
                'badge_border'    => 'border-indigo-400/40',
                'badge_text'      => 'text-indigo-300',
                'badge_icon'      => 'text-indigo-400',
                'gradient_text'   => 'from-indigo-300 via-purple-300 to-sky-300',
                'btn_primary'     => 'from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white shadow-indigo-500/25',
                'btn_sec_icon'    => 'text-indigo-300',
            ],
            'purple' => [
                'badge_bg'        => 'bg-purple-500/20',
                'badge_border'    => 'border-purple-400/40',
                'badge_text'      => 'text-purple-300',
                'badge_icon'      => 'text-purple-400',
                'gradient_text'   => 'from-purple-300 via-pink-300 to-amber-300',
                'btn_primary'     => 'from-purple-500 to-indigo-600 hover:from-purple-400 hover:to-indigo-500 text-white shadow-purple-500/25',
                'btn_sec_icon'    => 'text-purple-300',
            ],
            default => [ // amber
                'badge_bg'        => 'bg-amber-500/20',
                'badge_border'    => 'border-amber-400/40',
                'badge_text'      => 'text-amber-300',
                'badge_icon'      => 'text-amber-400',
                'gradient_text'   => 'from-amber-300 via-sky-300 to-cyan-300',
                'btn_primary'     => 'from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-sky-500/25',
                'btn_sec_icon'    => 'text-sky-300',
            ],
        };
    }

    /**
     * Scope: Diapositivas activas
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ordenadas por el campo 'order' ascendente
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }
}
