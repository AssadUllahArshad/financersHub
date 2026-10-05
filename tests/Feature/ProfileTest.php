<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
class ProfileTest extends TestCase
{
 use RefreshDatabase;
 private function admin(): User { return User::factory()->create(['role'=>'admin','password'=>'Existing-pass123!']); }
 public function test_guest_cannot_access_profile(): void { $this->get('/admin/profile')->assertRedirect('/login'); $this->put('/admin/profile',[])->assertRedirect('/login'); }
 public function test_profile_updates_only_current_user_and_preserves_role(): void {
  $admin=$this->admin();$other=User::factory()->create();
  $this->actingAs($admin)->get('/admin/profile')->assertOk()->assertSee('My profile');
  $this->put('/admin/profile',['name'=>'Updated Admin','email'=>'updated@example.com','current_password'=>'Existing-pass123!','id'=>$other->id,'role'=>'author'])->assertSessionHasNoErrors()->assertRedirect('/admin/profile');
  $this->assertSame('Updated Admin',$admin->fresh()->name);$this->assertSame('admin',$admin->fresh()->role);$this->assertSame($other->email,$other->fresh()->email);
 }
 public function test_wrong_password_and_duplicate_email_are_rejected_without_flashing_secrets(): void {
  $admin=$this->admin();$other=User::factory()->create();$this->actingAs($admin);
  $this->from('/admin/profile')->put('/admin/profile',['name'=>'Changed','email'=>$other->email,'current_password'=>'incorrect'])->assertSessionHasErrors(['email','current_password']);
  $this->assertNull(session('_old_input.current_password'));$this->assertSame($admin->name,$admin->fresh()->name);
 }
 public function test_password_requires_confirmation_and_strength(): void {
  $this->actingAs($this->admin())->put('/admin/profile/password',['current_password'=>'Existing-pass123!','password'=>'weak','password_confirmation'=>'different'])->assertSessionHasErrors('password');
 }
 public function test_password_change_hashes_password_and_requires_sign_in(): void {
  $admin=$this->admin();$this->actingAs($admin)->put('/admin/profile/password',['current_password'=>'Existing-pass123!','password'=>'New-strong-password123!','password_confirmation'=>'New-strong-password123!'])->assertRedirect('/login')->assertSessionHas('status');
  $this->assertGuest();$this->assertTrue(Hash::check('New-strong-password123!',$admin->fresh()->password));$this->assertFalse(Hash::check('Existing-pass123!',$admin->fresh()->password));
 }
}
