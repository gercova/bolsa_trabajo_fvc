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
     * Configuración de estilos visuales por color sólido correspondiente a cada programa de estudio.
     */
    public function getThemeStylesAttribute(): array
    {
        $color = $this->tag_color ?? 'amber';

        return match ($color) {
            'sky' => [
                'color_key'        => 'sky',
                'badge_bg'         => 'bg-slate-900/90',
                'badge_border'     => 'border-sky-400/80',
                'badge_text'       => 'text-sky-300',
                'badge_icon'       => 'text-sky-400',
                'solid_highlight'  => 'text-sky-400',
                'gradient_text'    => 'text-sky-400',
                'solid_accent_bar' => 'bg-sky-500',
                'btn_primary'      => 'bg-sky-600 hover:bg-sky-700 text-white shadow-md shadow-sky-900/30',
                'btn_sec_icon'     => 'text-sky-300',
                'pill_active'      => 'bg-sky-600 text-white',
                'dot_color'        => 'bg-sky-400',
            ],
            'rose' => [
                'color_key'        => 'rose',
                'badge_bg'         => 'bg-slate-900/90',
                'badge_border'     => 'border-rose-400/80',
                'badge_text'       => 'text-rose-300',
                'badge_icon'       => 'text-rose-400',
                'solid_highlight'  => 'text-rose-400',
                'gradient_text'    => 'text-rose-400',
                'solid_accent_bar' => 'bg-rose-500',
                'btn_primary'      => 'bg-rose-600 hover:bg-rose-700 text-white shadow-md shadow-rose-900/30',
                'btn_sec_icon'     => 'text-rose-300',
                'pill_active'      => 'bg-rose-600 text-white',
                'dot_color'        => 'bg-rose-400',
            ],
            'emerald' => [
                'color_key'        => 'emerald',
                'badge_bg'         => 'bg-slate-900/90',
                'badge_border'     => 'border-emerald-400/80',
                'badge_text'       => 'text-emerald-300',
                'badge_icon'       => 'text-emerald-400',
                'solid_highlight'  => 'text-emerald-400',
                'gradient_text'    => 'text-emerald-400',
                'solid_accent_bar' => 'bg-emerald-500',
                'btn_primary'      => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-900/30',
                'btn_sec_icon'     => 'text-emerald-300',
                'pill_active'      => 'bg-emerald-600 text-white',
                'dot_color'        => 'bg-emerald-400',
            ],
            'indigo', 'blue' => [
                'color_key'        => 'blue',
                'badge_bg'         => 'bg-slate-900/90',
                'badge_border'     => 'border-blue-400/80',
                'badge_text'       => 'text-blue-300',
                'badge_icon'       => 'text-blue-400',
                'solid_highlight'  => 'text-blue-400',
                'gradient_text'    => 'text-blue-400',
                'solid_accent_bar' => 'bg-blue-500',
                'btn_primary'      => 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-900/30',
                'btn_sec_icon'     => 'text-blue-300',
                'pill_active'      => 'bg-blue-600 text-white',
                'dot_color'        => 'bg-blue-400',
            ],
            'purple' => [
                'color_key'        => 'purple',
                'badge_bg'         => 'bg-slate-900/90',
                'badge_border'     => 'border-purple-400/80',
                'badge_text'       => 'text-purple-300',
                'badge_icon'       => 'text-purple-400',
                'solid_highlight'  => 'text-purple-400',
                'gradient_text'    => 'text-purple-400',
                'solid_accent_bar' => 'bg-purple-500',
                'btn_primary'      => 'bg-purple-600 hover:bg-purple-700 text-white shadow-md shadow-purple-900/30',
                'btn_sec_icon'     => 'text-purple-300',
                'pill_active'      => 'bg-purple-600 text-white',
                'dot_color'        => 'bg-purple-400',
            ],
            default => [ // amber
                'color_key'        => 'amber',
                'badge_bg'         => 'bg-slate-900/90',
                'badge_border'     => 'border-amber-400/80',
                'badge_text'       => 'text-amber-300',
                'badge_icon'       => 'text-amber-400',
                'solid_highlight'  => 'text-amber-400',
                'gradient_text'    => 'text-amber-400',
                'solid_accent_bar' => 'bg-amber-500',
                'btn_primary'      => 'bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold shadow-md shadow-amber-950/30',
                'btn_sec_icon'     => 'text-amber-300',
                'pill_active'      => 'bg-amber-500 text-slate-950',
                'dot_color'        => 'bg-amber-400',
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
