<?php

namespace App\Services;

/** Deliberately small editorial HTML vocabulary; no scripts, styles, embeds or remote images. */
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
        $content = preg_replace_callback('/<(h[23])>(.*?)<\/\1>/s', function ($match) use (&$headings) {
            $id = 'section-'.(count($headings) + 1);
            $headings[] = ['id' => $id, 'text' => html_entity_decode(strip_tags($match[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8')];

            return '<'.$match[1].' id="'.$id.'">'.$match[2].'</'.$match[1].'>';
        }, $this->clean($html));

        return ['html' => $content, 'headings' => $headings];
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
            if (! in_array($tag, ['p', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'strong', 'em', 'b', 'i', 'a', 'br', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'caption', 'code', 'pre'], true)) {
                $result .= $content;

                continue;
            }
            $attributes = '';
            if ($tag === 'a') {
                $href = trim($child->getAttribute('href'));
                if (filter_var($href, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($href, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)) {
                    $attributes = ' href="'.htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'" rel="noopener noreferrer"';
                }
            }
            $result .= '<'.$tag.$attributes.'>'.$content.(in_array($tag, ['br', 'hr'], true) ? '' : '</'.$tag.'>');
        }

        return $result;
    }
}
