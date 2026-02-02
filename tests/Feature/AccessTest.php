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
    public function insertTestEntry(?User $user): TestResponse {

        $test_entry_data = ['name' => 'test',
                'company' => 'test', 
                'image' => UploadedFile::fake()->image('image.jpg'),
                'logo' => UploadedFile::fake()->image('logo.jpg')];

        if ($user !== null) {

            return $this->actingAs($user)->post('/submit', $test_entry_data);

        } else {

            return $this->post('/submit', $test_entry_data);
        }
    }

    /**
     * Test that the admin can submit a new chicken sandwich entry
     */
    public function test_admin_can_submit(): void {
        
        Storage::fake('public');

        $admin = $this->getUser();
        $admin->assignRole('admin');

        $this->insertTestEntry($admin)->assertStatus(302);

        Storage::disk('public')->assertExists('logos/');
        Storage::disk('public')->assertExists('images/');
    }

    /**
     * Test that users cannot submit a new chicken sandwich entry
     */
    public function test_user_cannot_submit(): void {
        
        $user = $this->getUser();

        $this->insertTestEntry($user)->assertForbidden();
    }

    /**
     * Test that guests cannot submit a new chicken sandwich entry
     */
    public function test_guest_cannot_submit(): void {
        
        $this->insertTestEntry(null)->assertRedirect(route('login'));
    }

    /**
     * Test that the admin can delete the newly inserted chicken sandwich entry
     */
    public function test_admin_can_delete(): void {
        
        $admin = $this->getUser();
        $admin->assignRole('admin');
        $this->insertTestEntry($admin);
        $last_inserted_entry = ChickenSandwich::latest()->first();

        $response = $this->actingAs($admin)->delete("/delete/{$last_inserted_entry->id}"); 
            
        //expected response is for the admin to be sent to the result listing
        $response->assertStatus(302);
    }

    /**
     * Test that users cannot insert the newly inserted chicken sandwich entry
     */
    public function test_users_cannot_delete(): void {

        $user = $this->getUser();
        //only admins can insert, so we need that here due to authorization
        $admin = $this->getUser();
        $admin->assignRole('admin');
        $this->insertTestEntry($admin);
        $last_inserted_entry = ChickenSandwich::latest()->first();

        //attempt to delete the last entry as a user
        $response = $this->actingAs($user)->delete("/chicken-sandwich/{$last_inserted_entry}"); 
            
        $response->assertStatus(403);
    }

    /**
     * Test that guests cannot insert the newly inserted chicken sandwich entry
     */
    public function test_guests_cannot_delete(): void {

        
        //only admins can insert, so we need that here due to authorization
        $admin = $this->getUser();
        $admin->assignRole('admin');
        $this->insertTestEntry($admin);
        $last_inserted_entry = ChickenSandwich::latest()->first();
        
        //attempt to delete the last entry as a user
        $response = $this->delete("/chicken-sandwich/{$last_inserted_entry->id}"); 
        $response->assertStatus(302);

        $this->assertDatabaseHas('chicken_sandwiches', [
            'id' => $last_inserted_entry->id
        ]);


            
        
    }
        
}
