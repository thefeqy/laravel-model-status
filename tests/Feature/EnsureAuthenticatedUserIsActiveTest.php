<?php

namespace Thefeqy\ModelStatus\Tests\Feature;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Thefeqy\ModelStatus\Middleware\EnsureAuthenticatedUserIsActive;
use Thefeqy\ModelStatus\Status;
use Thefeqy\ModelStatus\Tests\TestCase;

class EnsureAuthenticatedUserIsActiveTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a users table for testing
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status', 10)->default(Status::active());
            $table->timestamps();
        });

        Route::middleware(['auth', EnsureAuthenticatedUserIsActive::class])
            ->get('/protected-route', function () {
                return response()->json(['message' => 'Access granted']);
            });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('users');
        parent::tearDown();
    }

    #[Test]
    public function inactive_users_are_denied_access()
    {
        $user = FakeUser::create(['name' => 'Inactive User', 'status' => Status::inactive()]);
        $this->actingAs($user);

        $response = $this->get('/protected-route');
        $response->assertForbidden();
    }

    #[Test]
    public function active_users_can_access_routes()
    {
        $user = FakeUser::create(['name' => 'Active User', 'status' => Status::active()]);
        $this->actingAs($user);

        $response = $this->get('/protected-route');
        $response->assertOk();
    }
}

/**
 * Fake User Model for Testing.
 */
class FakeUser extends Model implements AuthenticatableContract
{
    use Authenticatable; // Implements Laravel's authentication methods

    protected $table = 'users';

    protected $fillable = ['name', 'status'];
}
