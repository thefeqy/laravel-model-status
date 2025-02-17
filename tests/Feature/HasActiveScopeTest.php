<?php

namespace Thefeqy\ModelStatus\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thefeqy\ModelStatus\Status;
use Thefeqy\ModelStatus\Tests\TestCase;
use Thefeqy\ModelStatus\Traits\HasActiveScope;

class HasActiveScopeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary "products" table for testing
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status', 10)->default(Status::active());
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        // Drop the table after tests
        Schema::dropIfExists('products');
        parent::tearDown();
    }

    /** @test */
    public function it_returns_only_active_models_by_default()
    {
        $product1 = FakeProduct::create(['name' => 'Active Product', 'status' => Status::active()]);
        $product2 = FakeProduct::create(['name' => 'Inactive Product', 'status' => Status::inactive()]);

        $products = FakeProduct::all();

        $this->assertCount(1, $products);
        $this->assertEquals('Active Product', $products->first()->name);
    }
}

/**
 * Fake Model for Testing.
 */
class FakeProduct extends Model
{
    use HasActiveScope;

    protected $table = 'products';

    protected $fillable = ['name', 'status'];

    public $timestamps = true;
}
