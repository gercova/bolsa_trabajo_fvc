<?php

namespace Database\Seeders;

use App\Models\UserRoleDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id'                => 1,
                'user_id'           => 25,
                'program_id'        => 2,
                'is_coordinator'    => 1,
                'specialty'         => 'Prof. Tec. en Enfermería',
                'is_active'         => 1,
                'created_at'        => '2026-07-29T17:05:54.000Z',
                'updated_at'        => '2026-07-30T00:26:04.000Z'
            ],
            [
                'id'                => 2,
                'user_id'           => 35,
                'program_id'        => 3,
                'is_coordinator'    => 0,
                'specialty'         => null,
                'is_active'         => 1,
                'created_at'        => '2026-07-29T17:06:11.000Z',
                'updated_at'        => '2026-07-29T17:06:11.000Z'
            ],
            [
                'id'                => 3,
                'user_id'           => 3,
                'program_id'        => 5,
                'is_coordinator'    => 0,
                'specialty'         => null,
                'is_active'         => 1,
                'created_at'        => '2026-07-29T17:06:38.000Z',
                'updated_at'        => '2026-07-29T17:06:38.000Z'
            ],
            [
                'id'                => 4,
                'user_id'           => 12,
                'program_id'        => 3,
                'is_coordinator'    => 0,
                'specialty'         => null,
                'is_active'         => 1,
                'created_at'        => '2026-07-29T17:06:55.000Z',
                'updated_at'        => '2026-07-29T17:06:55.000Z'
            ],
            [
                'id'                => 5,
                'user_id'           => 5,
                'program_id'        => 1,
                'is_coordinator'    => 1,
                'specialty'         => null,
                'is_active'         => 1,
                'created_at'        => '2026-07-29T17:07:11.000Z',
                'updated_at'        => '2026-07-29T17:07:11.000Z'
            ],
            [
                'id'                => 6,
                'user_id'           => 30,
                'program_id'        => 3,
                'is_coordinator'    => 0,
                'specialty'         => null,
                'is_active'         => 1,
                'created_at'        => '2026-07-29T17:07:43.000Z',
                'updated_at'        => '2026-07-29T17:09:12.000Z'
            ],
            [
                'id'                => 7,
                'user_id'           => 7,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:09:40.000Z',
                'updated_at' => '2026-07-29T17:09:40.000Z'
            ],
            [
                'id' => 8,
                'user_id' => 8,
                'program_id' => 1,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:09:57.000Z',
                'updated_at' => '2026-07-29T17:09:57.000Z'
            ],
            [
                'id' => 9,
                'user_id' => 6,
                'program_id' => 3,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:10:19.000Z',
                'updated_at' => '2026-07-29T17:10:19.000Z'
            ],
            [
                'id' => 10,
                'user_id' => 28,
                'program_id' => 2,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:10:36.000Z',
                'updated_at' => '2026-07-29T17:10:36.000Z'
            ],
            [
                'id' => 11,
                'user_id' => 33,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:11:48.000Z',
                'updated_at' => '2026-07-29T17:11:48.000Z'
            ],
            [
                'id' => 12,
                'user_id' => 31,
                'program_id' => 5,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:12:10.000Z',
                'updated_at' => '2026-07-29T17:12:10.000Z'
            ],
            [
                'id' => 13,
                'user_id' => 14,
                'program_id' => 3,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:12:19.000Z',
                'updated_at' => '2026-07-29T17:12:19.000Z'
            ],
            [
                'id' => 14,
                'user_id' => 4,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:12:36.000Z',
                'updated_at' => '2026-07-29T17:12:36.000Z'
            ],
            [
                'id' => 15,
                'user_id' => 21,
                'program_id' => 3,
                'is_coordinator' => 1,
                'specialty' => 'Mg. en Tecnologías de Información y Comunicación',
                'is_active' => 1,
                'created_at' => '2026-07-29T17:12:49.000Z',
                'updated_at' => '2026-07-30T00:25:13.000Z'
            ],
            [
                'id' => 16,
                'user_id' => 16,
                'program_id' => 3,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:13:03.000Z',
                'updated_at' => '2026-07-29T17:13:03.000Z'
            ],
            [
                'id' => 17,
                'user_id' => 9,
                'program_id' => 1,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:13:22.000Z',
                'updated_at' => '2026-07-29T17:13:22.000Z'
            ],
            [
                'id' => 18,
                'user_id' => 24,
                'program_id' => 1,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:13:38.000Z',
                'updated_at' => '2026-07-29T17:13:38.000Z'
            ],
            [
                'id' => 19,
                'user_id' => 23,
                'program_id' => 5,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:14:13.000Z',
                'updated_at' => '2026-07-29T17:14:13.000Z'
            ],
            [
                'id' => 20,
                'user_id' => 26,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:14:38.000Z',
                'updated_at' => '2026-07-29T17:14:38.000Z'
            ],
            [
                'id' => 21,
                'user_id' => 11,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:14:56.000Z',
                'updated_at' => '2026-07-29T17:14:56.000Z'
            ],
            [
                'id' => 22,
                'user_id' => 34,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:15:24.000Z',
                'updated_at' => '2026-07-29T17:15:24.000Z'
            ],
            [
                'id' => 23,
                'user_id' => 27,
                'program_id' => 1,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:15:45.000Z',
                'updated_at' => '2026-07-29T17:15:45.000Z'
            ],
            [
                'id' => 24,
                'user_id' => 13,
                'program_id' => 2,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:16:14.000Z',
                'updated_at' => '2026-07-29T17:16:14.000Z'
            ],
            [
                'id' => 25,
                'user_id' => 15,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:17:18.000Z',
                'updated_at' => '2026-07-29T17:20:10.000Z'
            ],
            [
                'id' => 26,
                'user_id' => 19,
                'program_id' => 5,
                'is_coordinator' => 1,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:48:09.000Z',
                'updated_at' => '2026-07-29T17:48:09.000Z'
            ],
            [
                'id' => 27,
                'user_id' => 22,
                'program_id' => 4,
                'is_coordinator' => 1,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:48:33.000Z',
                'updated_at' => '2026-07-29T17:48:33.000Z'
            ],
            [
                'id' => 28,
                'user_id' => 29,
                'program_id' => 2,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:50:28.000Z',
                'updated_at' => '2026-07-29T17:50:28.000Z'
            ],
            [
                'id' => 29,
                'user_id' => 17,
                'program_id' => 2,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-07-29T17:50:43.000Z',
                'updated_at' => '2026-07-29T17:50:43.000Z'
            ],
            [
                'id' => 30,
                'user_id' => 18,
                'program_id' => 5,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-08-01T15:23:25.000Z',
                'updated_at' => '2026-08-01T15:23:25.000Z'
            ],
            [
                'id' => 31,
                'user_id' => 20,
                'program_id' => 3,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-08-01T15:25:07.000Z',
                'updated_at' => '2026-08-01T15:25:07.000Z'
            ],
            [
                'id' => 32,
                'user_id' => 32,
                'program_id' => 4,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-08-01T15:25:28.000Z',
                'updated_at' => '2026-08-01T15:25:28.000Z'
            ],
            [
                'id' => 33,
                'user_id' => 10,
                'program_id' => 1,
                'is_coordinator' => 0,
                'specialty' => null,
                'is_active' => 1,
                'created_at' => '2026-08-01T15:26:38.000Z',
                'updated_at' => '2026-08-01T15:26:38.000Z'
            ]
        ];

        // Opción 1: Usando el modelo (respetará los casts y eventos)
        foreach ($data as $record) {
            UserRoleDetail::create($record);
        }

        // Opción 2: Si quieres insertar todos de una vez con el modelo
        // UserRoleDetail::insert($data);

        // Opción 3: Si prefieres usar DB directo (más rápido)
        // DB::table('user_roles_details')->insert($data);
    }
}