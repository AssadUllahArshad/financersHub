<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
class VisitorAnalyticsTest extends TestCase {
 use RefreshDatabase;
 protected function setUp(): void {parent::setUp();config(['financershub.analytics_enabled'=>true]);$this->withHeaders(['User-Agent'=>'Mozilla/5.0 Chrome/130 Safari/537']);}
 public function test_visits_are_private_deduplicated_and_count_ip_changes(): void {
  $this->withHeaders(['Referer'=>'https://example.org/page?secret=hidden'])->get('/tools/compound-interest-calculator?amount=123')->assertOk();
  $this->get('/tools/compound-interest-calculator')->assertOk();
  $this->assertDatabaseCount('visitor_traces',1);
  $row=DB::table('visitor_traces')->first();$this->assertSame('/tools/compound-interest-calculator',$row->path);$this->assertSame('example.org',$row->referrer_host);$this->assertSame(64,strlen($row->visitor_hash));$this->assertStringNotContainsString('127.0.0.1',$row->visitor_hash);
  $this->withServerVariables(['REMOTE_ADDR'=>'192.0.2.2'])->get('/tools/compound-interest-calculator')->assertOk();$this->assertDatabaseCount('visitor_traces',2);
 }
 public function test_opt_out_bots_staff_and_disabled_tracking_are_excluded(): void {
  $this->withHeaders(['DNT'=>'1'])->get('/');$this->withHeaders(['DNT'=>'0','Sec-GPC'=>'1'])->get('/');
  $this->withHeaders(['Sec-GPC'=>'0','User-Agent'=>'Googlebot'])->get('/');
  $this->actingAs(User::factory()->create(['role'=>'admin']))->withHeaders(['User-Agent'=>'Mozilla/5.0'])->get('/');
  auth()->logout();config(['financershub.analytics_enabled'=>false]);$this->get('/');$this->assertDatabaseCount('visitor_traces',0);
 }
 public function test_analytics_requires_admin_and_retention_prunes_only_old_rows(): void {
  $this->get('/tools/compound-interest-calculator');DB::table('visitor_traces')->update(['visited_at'=>now()->subDays(91)]);
  $this->get('/about');$this->artisan('analytics:prune')->assertSuccessful();$this->assertDatabaseCount('visitor_traces',1);
  $this->actingAs(User::factory()->create(['role'=>'author']))->get('/admin/analytics')->assertForbidden();
  $this->actingAs(User::factory()->create(['role'=>'admin']))->get('/admin/analytics')->assertOk()->assertSee('Visitor analytics');
 }
}
