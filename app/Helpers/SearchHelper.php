<?php

namespace App\Helpers;

class SearchHelper
{
    /**
     * Безопасно подсвечивает поисковый запрос в тексте
     * 
     * @param string $text
     * @param string $search
     * @return string
     */
    public static function highlightSearch($text, $search)
    {
        if (empty($search)) {
            return e($text);
        }
        
        // Экранируем специальные символы для регулярных выражений
        $escapedSearch = preg_quote(e($search), '/');
        
        // Экранируем текст и затем подсвечиваем
        $escapedText = e($text);
        
        return preg_replace('/(' . $escapedSearch . ')/i', '<mark>$1</mark>', $escapedText);
    }
}