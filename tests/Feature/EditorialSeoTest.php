<?php
namespace Tests\Feature;
use App\Models\AuthorProfile;
use App\Models\FaqEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class EditorialSeoTest extends TestCase
{
    use RefreshDatabase;
    public function test_launch_indexing_switch_protects_public_pages(): void
    {
        config(['financershub.design_preview'=>false,'financershub.search_indexing_enabled'=>false]);
        $this->get('/')->assertOk()->assertSee('noindex,nofollow');
        $this->get('/robots.txt')->assertSee("Disallow: /\n");
        config(['financershub.search_indexing_enabled'=>true]);
        $this->get('/')->assertDontSee('noindex,nofollow');
        $this->get('/robots.txt')->assertSee('Sitemap:');
    }
    public function test_editorial_team_is_discoverable_before_first_publication(): void
    {
        config(['financershub.design_preview'=>false]);
        AuthorProfile::create(['name'=>'FinancersHub Editorial Team','slug'=>'editorial-team','schema_type'=>'Organization','bio'=>'Publication team.','is_demo'=>false]);
        $this->get('/authors')->assertOk()->assertSee('/authors/editorial-team',false);
        $this->get('/authors/editorial-team')->assertOk()->assertSee('Publication team.');
        $this->assertStringContainsString('/authors/editorial-team',$this->get('/sitemap.xml')->streamedContent());
    }
    public function test_faq_schema_matches_visible_published_answers(): void
    {
        config(['financershub.design_preview'=>false]);
        FaqEntry::create(['question'=>'A visible question?','answer'=>'A plain answer.','group'=>'using-financershub','published'=>true]);
        FaqEntry::create(['question'=>'Private draft?','answer'=>'Not published.','group'=>'using-financershub','published'=>false]);
        $html=$this->get('/faq')->assertOk()->assertDontSee('Private draft?')->getContent();
        preg_match('~<script type="application/ld\+json">(.*?)</script>~s',$html,$matches);
        $schema=json_decode($matches[1],true,512,JSON_THROW_ON_ERROR);
        $this->assertSame('FAQPage',$schema['@type']);
        $this->assertCount(1,$schema['mainEntity']);
        $this->assertSame('A plain answer.',$schema['mainEntity'][0]['acceptedAnswer']['text']);
    }
}
