<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Article,AuthorProfile,Category,MediaAsset,SiteSetting,User,FaqEntry};
class LaunchEditorialSeeder extends Seeder {
 public function run(): void {
  $owner=User::where('role','admin')->firstOrFail();
  DB::transaction(function()use($owner){
   $author=AuthorProfile::firstOrCreate(['slug'=>'editorial-team'],['name'=>'FinancersHub Editorial Team','schema_type'=>'Organization','bio'=>'FinancersHub Editorial Team is the publication byline used for our educational finance guides. Guides include source links and worked examples. We do not claim individual professional credentials under this team byline. Questions and corrections can be sent through our contact form.','is_demo'=>false]);
   foreach(json_decode(file_get_contents(database_path('content/editorial-drafts.json')),true,512,JSON_THROW_ON_ERROR) as $item){
    $primary=Category::where('slug',$item['category'])->firstOrFail();$secondary=Category::where('slug',$item['secondary'])->firstOrFail();
    $cover=null;
    foreach(['cover','body'] as $kind){$path='images/editorial/'.$item['image'].'-'.$kind.'.webp';$file=public_path('uploads/'.$path);if(!is_file($file)){throw new \RuntimeException('Build editorial images before seeding.');}
     $media=MediaAsset::firstOrCreate(['path'=>$path],['user_id'=>$owner->id,'original_name'=>basename($path),'mime_type'=>'image/webp','size'=>filesize($file),'width'=>1400,'height'=>800,'alt_text'=>$kind==='cover'?$item['title'].' - original editorial illustration':'Illustrative figures for '.$item['title'],'rights'=>'Original programmatically generated FinancersHub editorial diagram. No third-party photographs. Illustrative figures, not forecasts.']);if($kind==='cover'){$cover=$media;}
    }
    $article=Article::withTrashed()->firstOrCreate(['slug'=>$item['slug']],['user_id'=>$owner->id,'author_profile_id'=>$author->id,'category_id'=>$primary->id,'media_asset_id'=>$cover->id,'title'=>$item['title'],'excerpt'=>$item['excerpt'],'body'=>$item['body'],'seo_title'=>$item['seo_title'],'seo_description'=>$item['seo_description'],'sources'=>$item['sources'],'status'=>'draft','is_demo'=>false,'is_featured'=>false,'disclosure'=>'AI-assisted draft prepared for human editorial review. General education only, not personalized financial, investment, tax or legal advice. Numerical examples are illustrative.']);
    if($article->wasRecentlyCreated){$article->categories()->sync([$primary->id,$secondary->id]);app(\App\Services\ArticleWorkflow::class)->snapshot($article,$owner,'editorial draft prepared for review');}
   }
   $faqs=[
    ['How do I search for a guide?','Use the search field in the header or the article library. Only published guides appear in public search.','using-financershub'],
    ['Does the calculator save my financial information?','The savings calculator runs in your browser. Entered amounts are not sent to the server by the calculator. The privacy page explains basic page-visit measurements.','using-financershub'],
    ['Is newsletter signup available?','Newsletter signup and reader accounts are currently disabled. No email address is collected by the disabled newsletter form.','newsletter-and-contact'],
    ['How can I contact the editors?','Use the contact form to send a question, topic suggestion or correction. Your message is stored for the editorial team and a reference is shown after submission.','newsletter-and-contact'],
    ['Are the guides personalized financial advice?','No. The guides and calculator provide general education. They do not take your circumstances into account or guarantee financial results.','privacy-and-editorial-standards'],
   ];foreach($faqs as $i=>[$q,$a,$group]){FaqEntry::firstOrCreate(['question'=>$q],['answer'=>$a,'group'=>$group,'position'=>$i+1,'published'=>true]);}
   SiteSetting::firstOrCreate(['key'=>'seo_site_name'],['value'=>'FinancersHub']);
   SiteSetting::firstOrCreate(['key'=>'seo_description'],['value'=>'Practical finance guides, transparent worked examples and a free savings calculator. Explore saving, budgeting and investing with FinancersHub.']);
  });
  $this->command?->info('Three review drafts and an editorial team profile prepared. Nothing published; existing edits preserved.');
 }
}
