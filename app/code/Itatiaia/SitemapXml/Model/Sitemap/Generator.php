<?php
namespace Itatiaia\SitemapXml\Model\Sitemap;

class Generator
{
    public function parseMustacheTemplate($code, $variables)
    {
        $m = new \Mustache_Engine();
        return $m->render($code, $variables);
    }
}
