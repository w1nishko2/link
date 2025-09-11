<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Helpers\SeoHelper;

class SeoMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Проверяем, что это HTML ответ
        if ($response->headers->get('content-type') && 
            strpos($response->headers->get('content-type'), 'text/html') !== false) {
            
            $content = $response->getContent();
            
            // Добавляем автоматические SEO улучшения только если нет explicit мета-тегов
            if (strpos($content, '<meta name="description"') === false) {
                $this->addDefaultMetaTags($content);
            }
            
            // Добавляем микроразметку Organization для всех страниц
            $this->addOrganizationSchema($content);
            
            $response->setContent($content);
        }
        
        return $response;
    }
    
    /**
     * Добавляет базовые мета-теги если они отсутствуют
     */
    private function addDefaultMetaTags(&$content)
    {
        $title = $this->extractTitle($content);
        $description = SeoHelper::generateDescription($this->extractBodyText($content));
        
        $metaTags = "
    <meta name=\"description\" content=\"{$description}\">
    <meta name=\"robots\" content=\"index, follow\">
    <meta property=\"og:type\" content=\"website\">
    <meta property=\"og:title\" content=\"{$title}\">
    <meta property=\"og:description\" content=\"{$description}\">
    <meta property=\"og:url\" content=\"" . request()->url() . "\">
    <meta name=\"twitter:card\" content=\"summary\">
    <meta name=\"twitter:title\" content=\"{$title}\">
    <meta name=\"twitter:description\" content=\"{$description}\">
";
        
        $content = str_replace('</head>', $metaTags . '</head>', $content);
    }
    
    /**
     * Добавляет базовую микроразметку Organization
     */
    private function addOrganizationSchema(&$content)
    {
        $schema = '
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "' . config('app.name') . '",
    "url": "' . config('app.url') . '",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "' . config('app.url') . '/search?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>';
        
        if (strpos($content, '@type": "WebSite"') === false) {
            $content = str_replace('</head>', $schema . '</head>', $content);
        }
    }
    
    /**
     * Извлекает title страницы
     */
    private function extractTitle($content)
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $content, $matches)) {
            return strip_tags($matches[1]);
        }
        return config('app.name', 'Laravel');
    }
    
    /**
     * Извлекает текст из body страницы
     */
    private function extractBodyText($content)
    {
        // Убираем скрипты и стили
        $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $content);
        $content = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $content);
        
        // Извлекаем содержимое body
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
            $bodyContent = $matches[1];
            $bodyContent = strip_tags($bodyContent);
            $bodyContent = preg_replace('/\s+/', ' ', $bodyContent);
            return trim($bodyContent);
        }
        
        return '';
    }
}
