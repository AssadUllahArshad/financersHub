<?php
namespace Tests\Feature;

use App\Services\ArticleHtml;
use Tests\TestCase;

class ArticleContentTest extends TestCase
{
    public function test_rich_content_survives_without_executable_markup(): void
    {
        $html = '<h2>Guide</h2><figure><img src="/uploads/images/example.webp" alt="Budget chart" width="800" onerror="alert(1)"><figcaption>Monthly budget</figcaption></figure><table><thead><tr><th scope="col" colspan="2">Costs</th></tr></thead><tbody><tr><td rowspan="2" style="text-align:center;position:fixed">Food</td><td><strong>100</strong></td></tr></tbody></table><p><sup>2</sup><s>old</s></p><script>alert(1)</script><img src="javascript:alert(1)"><img src="data:image/svg+xml,bad">';
        $service = new ArticleHtml;
        $clean = $service->clean($html);
        foreach (['/uploads/images/example.webp', 'alt="Budget chart"', '<figcaption>', 'colspan="2"', 'rowspan="2"', 'scope="col"', 'text-align:center', '<sup>2</sup>'] as $expected) { $this->assertStringContainsString($expected, $clean); }
        foreach (['onerror', '<script', 'javascript:', 'data:image', 'position:fixed'] as $unsafe) { $this->assertStringNotContainsString($unsafe, $clean); }
        $this->assertSame($clean, $service->clean($clean));
        $this->assertCount(1, $service->prepare($clean)['headings']);
    }
    public function test_internal_links_and_media_routes_are_preserved_but_unsafe_urls_are_removed(): void
    {
        $html = '<p><a href="/tools/compound-interest-calculator">Calculator</a><a href="#section-1">Jump</a><a href="//evil.example">Bad</a></p><img src="/media/12" alt="Chart"><img src="/uploads/../private"><img src="/uploads/%2e%2e/private"><img src="javascript:alert(1)">';
        $clean = (new ArticleHtml)->clean($html);
        $this->assertStringContainsString('href="/tools/compound-interest-calculator"', $clean);
        $this->assertStringContainsString('href="#section-1"', $clean);
        $this->assertStringContainsString('src="/media/12"', $clean);
        foreach (['evil.example', '/private', 'javascript:'] as $unsafe) { $this->assertStringNotContainsString($unsafe, $clean); }
    }
}
