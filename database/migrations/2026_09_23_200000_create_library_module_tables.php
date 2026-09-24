<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Catálogo de Libros, Revistas, Papers y Documentos
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->foreignId('study_program_id')->nullable()->constrained('study_programs')->nullOnDelete();
            $table->string('category', 50)->default('Libro'); // Libro, Revista, Paper, Tesis, Documento
            $table->text('description')->nullable();
            $table->string('publisher', 150)->nullable();
            $table->unsignedSmallInteger('publication_year')->nullable();
            $table->string('edition', 50)->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('language', 50)->default('Español');
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('external_url', 500)->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['study_program_id', 'is_active']);
            $table->index(['category', 'is_active']);
            $table->index('title');
            $table->index('author');
        });

        // 2. Libros Favoritos (Mi Biblioteca para Estudiantes y Docentes)
        Schema::create('book_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
        });

        // 3. Registro de Lecturas y Accesos (Dashboard de Lectores)
        Schema::create('library_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->string('access_type', 30)->default('view'); // view, download, external_link
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['book_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_access_logs');
        Schema::dropIfExists('book_favorites');
        Schema::dropIfExists('books');
    }
};
