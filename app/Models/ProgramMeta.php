<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramMeta extends Model
{
    protected $table = 'program_metas';
    protected $fillable = [
        'study_program_id',
        'icon',
        'accent',
        'bg_badge',
        'tag',
        'color_bar',
        'glow_class',
        'badge_class',
        'accent_text',
        'bullet_class',
        'icon_bg_class',
        'border_hover_class',
        'badge_module_class',
        'sidebar_icon_class',
        'cta_bg_class',
        'bar_color_class',
    ];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }
}