<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->unique('username');
        });

        DB::table('users')
            ->select(['id', 'email', 'name'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $id = (int) $row->id;
                    $email = (string) ($row->email ?? '');
                    $name = (string) ($row->name ?? '');

                    $base = '';
                    if ($email !== '' && str_contains($email, '@')) {
                        $base = explode('@', $email, 2)[0] ?? '';
                    }
                    if ($base === '') {
                        $base = $name;
                    }

                    $base = strtolower(trim($base));
                    $base = preg_replace('/\s+/', '', $base) ?? '';
                    $base = preg_replace('/[^a-z0-9_.]/', '', $base) ?? '';
                    if ($base === '') {
                        $base = 'user'.$id;
                    }

                    $base = substr($base, 0, 30);
                    if ($base === '') {
                        $base = 'user'.$id;
                    }

                    $candidate = $base;
                    $suffix = 0;
                    while (
                        DB::table('users')
                            ->where('username', $candidate)
                            ->where('id', '!=', $id)
                            ->exists()
                    ) {
                        $suffix++;
                        $suffixStr = (string) $suffix;
                        $candidate = substr($base, 0, max(0, 30 - (1 + strlen($suffixStr)))).'_'.$suffixStr;
                        if ($candidate === '') {
                            $candidate = 'user'.$id.'_'.$suffixStr;
                        }
                    }

                    DB::table('users')->where('id', $id)->update(['username' => $candidate]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};

