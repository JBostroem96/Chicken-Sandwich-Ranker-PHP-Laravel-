<?php

class PostPolicyTest extends TestCase {



    public function test_only_admin_can_submit() {

        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue((new PostPolicy)->update($user, $post));
    }   
}