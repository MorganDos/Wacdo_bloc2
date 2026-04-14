<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lance la migration.
     */
    public function up(): void
    {
        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'category')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->string('category')->nullable()->after('description');
            });
        }

        if (Schema::hasTable('orders')) {
            $addedTicketNumber = ! Schema::hasColumn('orders', 'ticket_number');
            $addedDeliveryAt = ! Schema::hasColumn('orders', 'delivery_at');

            Schema::table('orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('orders', 'ticket_number')) {
                    $table->string('ticket_number')->nullable()->after('id');
                }

                if (! Schema::hasColumn('orders', 'delivery_at')) {
                    $table->timestamp('delivery_at')->nullable()->after('status');
                }
            });

            if (! Schema::hasColumn('orders', 'ticket_number')) {
                return;
            }

            DB::table('orders')
                ->whereNull('ticket_number')
                ->orderBy('id')
                ->get()
                ->each(function (object $order): void {
                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update(['ticket_number' => sprintf('WKD-%08d', $order->id)]);
                });

            Schema::table('orders', function (Blueprint $table) use ($addedTicketNumber, $addedDeliveryAt): void {
                if ($addedTicketNumber) {
                    $table->unique('ticket_number');
                }

                if ($addedDeliveryAt) {
                    $table->index('delivery_at');
                }
            });
        }
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'category')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('category');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table): void {
                if (Schema::hasColumn('orders', 'delivery_at')) {
                    $table->dropIndex(['delivery_at']);
                    $table->dropColumn('delivery_at');
                }

                if (Schema::hasColumn('orders', 'ticket_number')) {
                    $table->dropUnique(['ticket_number']);
                    $table->dropColumn('ticket_number');
                }
            });
        }
    }
};
