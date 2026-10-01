<?php

namespace App\Services;

/** Allowlisted editorial markup; executable content and unsafe URLs are removed. */
class ArticleHtml
{
    public function clean(string $html): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $body = $dom->getElementsByTagName('body')->item(0);

        return $body ? $this->children($body) : '';
    }

    public function prepare(string $html): array
    {
        $headings = [];
        $content = preg_replace_callback('/<(h[23])([^>]*)>(.*?)<\/\1>/s', function ($match) use (&$headings) {
            $id = 'section-'.(count($headings) + 1);
            $headings[] = ['id' => $id, 'text' => html_entity_decode(strip_tags($match[3]), ENT_QUOTES | ENT_HTML5, 'UTF-8')];

            return '<'.$match[1].$match[2].' id="'.$id.'">'.$match[3].'</'.$match[1].'>';
        }, $this->clean($html));

        return ['html' => $content, 'headings' => $headings];
    }

    private function safeImage(string $url): bool
    {
        if (preg_match('/[\x00-\x20\x7f]/', $url) || str_contains($url, chr(92))) { return false; }
        if (str_starts_with($url, '/uploads/') || str_starts_with($url, '/assets/images/') || preg_match('~^/media/[0-9]+$~', $url)) {
            $path = rawurldecode(parse_url($url, PHP_URL_PATH) ?? '');
            return !preg_match('~(?:^|/)\.\.?(?:/|$)~', $path) && !str_contains($path, chr(92));
        }
        return filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['https', 'http'], true);
    }

    private function safeLink(string $url): bool
    {
        if (preg_match('/[\x00-\x20\x7f]/', $url) || str_contains($url, chr(92))) { return false; }
        if (str_starts_with($url, '#')) { return strlen($url) > 1; }
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) { return true; }
        return filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true);
    }

    private function children(\DOMNode $node): string
    {
        $result = '';
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMText) {
                $result .= htmlspecialchars($child->nodeValue, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                continue;
            }
            if (! $child instanceof \DOMElement) {
                continue;
            }
            $tag = strtolower($child->tagName);
            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math', 'form', 'input', 'button', 'textarea', 'template'], true)) {
                continue;
            }
            $content = $this->children($child);
            if (! in_array($tag, ['img', 'figure', 'figcaption', 'span', 'u', 's', 'sub', 'sup', 'tfoot', 'colgroup', 'col', 'h5', 'h6', 'p', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'strong', 'em', 'b', 'i', 'a', 'br', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'caption', 'code', 'pre'], true)) {
                $result .= $content;

                continue;
            }
            $attributes = '';
            if ($tag === 'a') {
                $href = trim($child->getAttribute('href'));
                if ($this->safeLink($href)) {
                    $attributes = ' href="'.htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'" rel="noopener noreferrer"';
                }
            }
            if ($tag === 'img') {
                $src = trim($child->getAttribute('src'));
                if (! $this->safeImage($src)) { continue; }
                $attributes .= ' src="'.htmlspecialchars($src, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'" alt="'.htmlspecialchars($child->getAttribute('alt'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'" loading="lazy" decoding="async"';
            }
            foreach (['width', 'height', 'colspan', 'rowspan', 'span', 'start'] as $attribute) {
                $value = $child->getAttribute($attribute);
                if (ctype_digit($value) && (int) $value > 0 && (int) $value <= 10000) {
                    $attributes .= ' '.$attribute.'="'.$value.'"';
                }
            }
            if ($tag === 'th' && in_array($child->getAttribute('scope'), ['row', 'col', 'rowgroup', 'colgroup'], true)) {
                $attributes .= ' scope="'.$child->getAttribute('scope').'"';
            }
            $styles = [];
            foreach (explode(';', $child->getAttribute('style')) as $declaration) {
                $parts = explode(':', $declaration, 2);
                if (count($parts) !== 2) { continue; }
                [$property, $value] = array_map('trim', $parts);
                $rules = [
                    'text-align' => '/^(left|right|center|justify)$/',
                    'vertical-align' => '/^(top|middle|bottom|baseline)$/',
                    'width' => '/^(100|[1-9]?[0-9])(\.[0-9]+)?%$|^[1-9][0-9]{0,3}px$/',
                    'color' => '/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/',
                    'background-color' => '/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/',
                ];
                if (isset($rules[$property]) && preg_match($rules[$property], $value)) { $styles[] = $property.':'.$value; }
            }
            if ($styles) { $attributes .= ' style="'.implode(';', $styles).'"'; }
            $result .= '<'.$tag.$attributes.'>'.$content.(in_array($tag, ['br', 'hr', 'img', 'col'], true) ? '' : '</'.$tag.'>');
        }

        return $result;
    }
}
