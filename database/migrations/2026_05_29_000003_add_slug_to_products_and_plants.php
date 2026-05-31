<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_products', function (Blueprint $table) {
            $table->string('slug', 150)->nullable()->unique()->after('name');
        });

        Schema::table('dairy_plants', function (Blueprint $table) {
            $table->string('slug', 150)->nullable()->unique()->after('name');
        });

        $this->backfillSlugs('dairy_products');
        $this->backfillSlugs('dairy_plants');
    }

    public function down(): void
    {
        Schema::table('dairy_products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('dairy_plants', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function backfillSlugs(string $table): void
    {
        $used = [];
        foreach (DB::table($table)->select('id', 'name')->get() as $row) {
            $base = Str::slug($row->name) ?: (string) $row->id;
            $slug = $base;
            $suffix = 2;
            while (in_array($slug, $used, true)) {
                $slug = $base . '-' . $suffix++;
            }
            $used[] = $slug;
            DB::table($table)->where('id', $row->id)->update(['slug' => $slug]);
        }
    }
};
