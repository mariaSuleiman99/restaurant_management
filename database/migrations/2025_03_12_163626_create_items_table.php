<?php

use App\Models\Cart;
use App\Models\Item;
use App\Models\Order;
use App\Models\Restaurant;
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->string("image");
            $table->float("price");
            $table->decimal('avg_rate', 3, 1)->default(0);
            $table->foreignIdFor(Restaurant::class)->constrained();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer("count");
            $table->float("price");
            $table->foreignIdFor(Item::class)->constrained();
            $table->foreignIdFor(Order::class)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('items');
    }
};
