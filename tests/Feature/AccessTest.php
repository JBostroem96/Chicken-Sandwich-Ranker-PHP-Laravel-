<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\ChickenSandwich;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;

//This class' purpose is to feature test authorization
class AccessTest extends TestCase {

    use RefreshDatabase;

    //Initial setup
    protected function setUp(): void {

        parent::setUp();

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
    }

    /**
     * return the created user for this test case
     */
    public function getUser(): User {

        return User::factory()->create();
    }

    /**
     * test that the admin can access the chicken sandwich submit page
     */
    public function test_admin_can_access_submit_page(): void {

        $admin = $this->getUser();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/submit');

        $response->assertOk();
    }

    /**
     * test that users cannot access the chicken sandwich submit page
     */
    public function test_user_cannot_access_admin_page(): void {

        $user = $this->getUser();

        $this->actingAs($user)
            ->get('/submit')
            ->assertForbidden();
    }

    /**
     * test that guests get redirected to the login page upon attempting to reach the submit page
     */
    public function test_guest_is_redirected_to_login(): void {
        
        $this->get('/submit')
            ->assertRedirect(route('login'));
    }

    /**
     * Insert the new test entry and return the response
     *
     * @param User|null $user               the user or null object
     */
    public function insertTestEntry(?User $user, Array $test_data): TestResponse {

        if ($user !== null) {

            return $this->actingAs($user)->post('/submit', $test_data);
            
        } else {

            return $this->post('/submit', $test_data);
        }
    }

    /**
     * create the test data and return it
     */
    public function createTestData(): array {

        Storage::fake('public');

        return $test_entry_data = ['name' => 'test',
            'company' => 'test', 
            'image' => UploadedFile::fake()->image('image.jpg'),
            'logo' => UploadedFile::fake()->image('logo.jpg')];
    }

    /**
     * Test that the admin can submit a new chicken sandwich entry
     */
    public function test_admin_can_submit(): void {
        
        $admin = $this->getUser();
        $admin->assignRole('admin');

        $test_data = $this->createTestData();

        $this->insertTestEntry($admin, $test_data)->assertStatus(302);

        Storage::disk('public')->assertExists('logos/');
        Storage::disk('public')->assertExists('images/');

        $this->assertDatabaseHas('chicken_sandwiches', [
            'name' => $test_data['name'],
            'company' => $test_data['company'],
        ]);
    }

    /**
     * Test that users cannot submit a new chicken sandwich entry
     */
    public function test_user_cannot_submit(): void {
        
        $test_data = $this->createTestData();

        $user = $this->getUser();

        $this->insertTestEntry($user, $test_data)->assertForbidden();

        $this->assertDatabaseMissing('chicken_sandwiches', [
            'name' => $test_data['name'],
            'company' => $test_data['company']
        ]);
    }

    /**
     * Test that guests cannot submit a new chicken sandwich entry
     */
    public function test_guest_cannot_submit(): void {
        
        $test_data = $this->createTestData();

        $this->insertTestEntry(null, $test_data)->assertRedirect(route('login'));

        $this->assertDatabaseMissing('chicken_sandwiches', [
            'name' => $test_data['name'],
            'company' => $test_data['company']
        ]);
    }

    /**
     * Test that the admin can delete the newly inserted chicken sandwich entry
     */
    public function test_admin_can_delete(): void {
        
        $admin = $this->getUser();
        $admin->assignRole('admin');
        $entry = ChickenSandwich::factory()->create();

        $response = $this->actingAs($admin)->delete("/chicken-sandwiches/{$entry->id}"); 
            
        //expected response is for the admin to be sent to the result listing
        $response->assertStatus(302);

        $this->assertDatabaseMissing('chicken_sandwiches', [
            'id' => $entry->id
        ]);
    }

    /**
     * Test that users cannot delete the newly inserted chicken sandwich entry
     */
    public function test_user_cannot_delete(): void {

        $user = $this->getUser();
       
        $entry = ChickenSandwich::factory()->create();

        //attempt to delete the last entry as a user
        $response = $this->actingAs($user)->delete("/chicken-sandwiches/{$entry->id}"); 
            
        $response->assertStatus(403);

        $this->assertDatabaseHas('chicken_sandwiches', [
            'id' => $entry->id
        ]);
    }

    /**
     * Test that guests cannot delete the newly inserted chicken sandwich entry
     */
    public function test_guests_cannot_delete(): void {

        $entry = ChickenSandwich::factory()->create();
        
        $response = $this->delete("/chicken-sandwiches/{$entry->id}");

        $response->assertStatus(302);

        $this->assertDatabaseHas('chicken_sandwiches', [
            'id' => $entry->id
        ]);
    }


    /**
     * AUTH USER RATING SUBMISSION TESTS
     */


    /**
     * create the test data and return it
     */
    public function createTestReviewData(): array {

        $entry = ChickenSandwich::factory()->create();

        return $test_entry_data = [
            'chicken_sandwich_id' => $entry->id,
            'score' => 8,
            'review' => "This is a review test and it needs to be at least 30 characters long
            so I'm just writing a test review to make sure that it reaches 30 characters long" 
        ];
    }

    /**
    * Insert the new test entry and return the response
    *
    * @param User|null $user               the user or null object
    */
    public function insertTestReviewEntry(?User $user, Array $test_data): TestResponse {

        if ($user !== null) {

            return $this->actingAs($user)->post('/chicken-sandwiches', $test_data);
            
        } else {

            return $this->post('/chicken-sandwiches', $test_data);
        }
    }

    /**
     * test that a user can submit a rating
     */
    public function test_user_can_submit_rating(): void {

        $test_data = $this->createTestReviewData();

        $user = $this->getUser();

        $this->insertTestReviewEntry($user, $test_data)->assertRedirect();

        $this->assertDatabaseHas('user_chicken_sandwiches', [

            'user_id' => $user->id,
            'chicken_sandwich_id' => $test_data['chicken_sandwich_id'],
            'score' => $test_data['score'],
            'review' => $test_data['review']
        ]);
    }

    /**
     * test that guest cannot submit a rating
     */
    public function test_guest_cannot_submit_rating(): void {

        $test_data = $this->createTestReviewData();

        $user = $this->getUser();
        $user = 
        $this->insertTestReviewEntry(null, $test_data)->assertRedirect(route('login'));;

        $this->assertDatabaseMissing('user_chicken_sandwiches', [
            
            'chicken_sandwich_id' => $test_data['chicken_sandwich_id'],
            'score' => $test_data['score'],
            'review' => $test_data['review']
        ]);
    }

    /**
     * test that admin can submit a chicken sandwich rating
     */
    public function test_admin_can_submit_rating(): void {

        $test_data = $this->createTestReviewData();
        $admin = $this->getUser();
        $admin->assignRole('admin');

        $this->insertTestReviewEntry($admin, $test_data)->assertRedirect();

        $this->assertDatabaseHas('user_chicken_sandwiches', [
            'user_id' => $admin->id,
            'chicken_sandwich_id' => $test_data['chicken_sandwich_id'],
            'score' => $test_data['score'],
            'review' => $test_data['review']
        ]);
    }       
}
