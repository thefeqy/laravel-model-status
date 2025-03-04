<?php

namespace Thefeqy\ModelStatus\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thefeqy\ModelStatus\Casts\StatusCast;
use Thefeqy\ModelStatus\Status;
use Thefeqy\ModelStatus\Tests\TestCase;
use Thefeqy\ModelStatus\Traits\HasActiveScope;

class CascadeDeactivationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status', 10)->default(Status::active());
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('status', Status::allowedStatuses())->default(Status::active());
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        parent::tearDown();
    }

    /** @test */
    public function deactivating_a_category_deactivates_its_products()
    {
        $category = FakeCategory::create(['name' => 'Electronics']);
        $product1 = FakeScopedProduct::create(['name' => 'Laptop', 'category_id' => $category->id]);
        $product2 = FakeScopedProduct::create(['name' => 'Phone', 'category_id' => $category->id]);

        $this->assertTrue($product1->status->isActive());
        $this->assertTrue($product2->status->isActive());

        $category->deactivate();

        $this->assertTrue($category->status->isInactive());
        $this->assertTrue($product1->fresh()->status->isInactive());
        $this->assertTrue($product2->fresh()->status->isInactive());
    }
}

/**
 * Fake Category Model for Testing.
 */
class FakeCategory extends Model
{
    use HasActiveScope;

    protected $table = 'categories';

    protected $fillable = ['name', 'status'];

    protected array $cascadeDeactivate = ['products'];

    protected $casts = [
        'status' => StatusCast::class,
    ];

    public function products()
    {
        return $this->hasMany(FakeScopedProduct::class, 'category_id');
    }
}

/**
 * Fake Product Model for Testing.
 */
class FakeScopedProduct extends Model
{
    use HasActiveScope;

    protected $table = 'products';

    protected $fillable = ['name', 'category_id'];

    protected $attributes = [
        'status' => 'active',
    ];

    protected $casts = [
        'status' => StatusCast::class,
    ];

    public function category()
    {
        return $this->belongsTo(FakeCategory::class, 'category_id');
    }
}
