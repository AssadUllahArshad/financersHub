<?php
namespace Tests\Feature;
use App\Models\{Article,AuthorProfile,Category,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class LibraryDiscoveryTest extends TestCase
{
 use RefreshDatabase;
 public function test_filters_include_secondary_categories_exclude_drafts_and_sort(): void {
  config(['financershub.design_preview'=>false]);
  $user=User::factory()->create();
  $author=AuthorProfile::create(['name'=>'Team','slug'=>'team','is_demo'=>false]);
  $primary=Category::create(['name'=>'Saving','slug'=>'saving']);
  $secondary=Category::create(['name'=>'Planning','slug'=>'planning']);
  foreach(['Zebra guide','Alpha guide','Hidden draft'] as $i=>$title){
   $article=Article::create(['user_id'=>$user->id,'author_profile_id'=>$author->id,'category_id'=>$primary->id,'title'=>$title,'slug'=>'guide-'.$i,'excerpt'=>'Savings examples','body'=>'<p>Example.</p>','status'=>$i===2?'draft':'published','published_at'=>now()->subDays($i+1),'is_demo'=>false]);
   $article->categories()->sync([$secondary->id]);
  }
  $response=$this->get('/search?q=Savings&topic=planning&sort=title')->assertOk()->assertDontSee('Hidden draft')->assertSeeInOrder(['Alpha guide','Zebra guide']);
  $this->assertSame(2,$response->viewData('articles')->total());
  $this->get('/search?sort=newest')->assertSeeInOrder(['Zebra guide','Alpha guide']);
  $this->get('/search?sort=oldest')->assertSeeInOrder(['Alpha guide','Zebra guide']);
  $this->get('/search?q=unmatched')->assertSee('No guides match this selection.')->assertSee('Try the calculator');
 }
 public function test_invalid_filter_types_and_sort_are_rejected(): void {
  config(['financershub.design_preview'=>false]);
  $this->getJson('/search?q[]=bad&sort=invalid&topic=missing')->assertUnprocessable()->assertJsonValidationErrors(['q','sort','topic']);
 }
 public function test_home_links_to_available_topics_and_tool(): void {
  config(['financershub.design_preview'=>false]);
  Category::create(['name'=>'Saving','slug'=>'saving']);
  $this->get('/')->assertOk()->assertSee('Explore by topic')->assertSee('/categories/saving',false)->assertSee('Open the savings calculator');
 }
}
