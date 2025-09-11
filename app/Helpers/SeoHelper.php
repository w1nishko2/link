<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SeoHelper
{
    /**
     * Генерирует оптимизированное описание из текста
     */
    public static function generateDescription(string $text, int $length = 160): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        $text = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($text, ' ');
        
        if ($lastSpace !== false) {
            $text = mb_substr($text, 0, $lastSpace);
        }
        
        return rtrim($text, '.,!?;') . '...';
    }

    /**
     * Генерирует ключевые слова из текста
     */
    public static function generateKeywords(string $text, array $additionalKeywords = []): string
    {
        $text = strip_tags($text);
        $text = mb_strtolower($text);
        
        // Удаляем стоп-слова
        $stopWords = [
            'и', 'в', 'не', 'на', 'я', 'с', 'он', 'а', 'то', 'все', 'она', 
            'так', 'его', 'но', 'да', 'ты', 'к', 'у', 'же', 'вы', 'за',
            'бы', 'по', 'только', 'ее', 'мне', 'было', 'вот', 'от', 'меня',
            'еще', 'нет', 'о', 'из', 'ему', 'теперь', 'когда', 'даже',
            'ну', 'вдруг', 'ли', 'если', 'уже', 'или', 'ни', 'быть',
            'был', 'него', 'до', 'вас', 'нибудь', 'опять', 'уж', 'вам',
            'ведь', 'там', 'потом', 'себя', 'ничего', 'ей', 'может', 'они',
            'тут', 'где', 'есть', 'надо', 'ней', 'для', 'мы', 'тебя',
            'их', 'чем', 'была', 'сам', 'чтоб', 'без', 'будто', 'чего',
            'раз', 'тоже', 'себе', 'под', 'будет', 'ж', 'тогда', 'кто',
            'этот', 'того', 'потому', 'этого', 'какой', 'совсем', 'ним',
            'здесь', 'этом', 'один', 'почти', 'мой', 'тем', 'чтобы', 'нее',
            'сейчас', 'были', 'куда', 'зачем', 'всех', 'никогда', 'можно',
            'при', 'наконец', 'два', 'об', 'другой', 'хоть', 'после',
            'над', 'больше', 'тот', 'через', 'эти', 'нас', 'про', 'всего',
            'них', 'какая', 'много', 'разве', 'три', 'эту', 'моя', 'впрочем',
            'хорошо', 'свою', 'этой', 'перед', 'иногда', 'лучше', 'чуть',
            'том', 'нельзя', 'такой', 'им', 'более', 'всегда', 'конечно',
            'всю', 'между'
        ];
        
        $words = str_word_count($text, 1, 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя');
        $words = array_filter($words, function($word) use ($stopWords) {
            return mb_strlen($word) > 2 && !in_array($word, $stopWords);
        });
        
        $wordCounts = array_count_values($words);
        arsort($wordCounts);
        
        $keywords = array_merge(
            $additionalKeywords,
            array_slice(array_keys($wordCounts), 0, 10)
        );
        
        return implode(', ', array_unique($keywords));
    }

    /**
     * Создает SEO-friendly URL из строки
     */
    public static function createSlug(string $text): string
    {
        return Str::slug($text);
    }

    /**
     * Проверяет длину title для SEO
     */
    public static function validateTitleLength(string $title): array
    {
        $length = mb_strlen($title);
        
        return [
            'length' => $length,
            'is_optimal' => $length >= 30 && $length <= 60,
            'status' => $length < 30 ? 'too_short' : ($length > 60 ? 'too_long' : 'optimal'),
            'message' => $length < 30 
                ? 'Заголовок слишком короткий для SEO (рекомендуется 30-60 символов)'
                : ($length > 60 
                    ? 'Заголовок слишком длинный для SEO (рекомендуется 30-60 символов)'
                    : 'Оптимальная длина заголовка')
        ];
    }

    /**
     * Проверяет длину description для SEO
     */
    public static function validateDescriptionLength(string $description): array
    {
        $length = mb_strlen($description);
        
        return [
            'length' => $length,
            'is_optimal' => $length >= 120 && $length <= 160,
            'status' => $length < 120 ? 'too_short' : ($length > 160 ? 'too_long' : 'optimal'),
            'message' => $length < 120 
                ? 'Описание слишком короткое для SEO (рекомендуется 120-160 символов)'
                : ($length > 160 
                    ? 'Описание слишком длинное для SEO (рекомендуется 120-160 символов)'
                    : 'Оптимальная длина описания')
        ];
    }

    /**
     * Анализирует контент на SEO факторы
     */
    public static function analyzeContent(string $title, string $content): array
    {
        $wordCount = str_word_count(strip_tags($content));
        $titleWords = explode(' ', mb_strtolower($title));
        $contentLower = mb_strtolower(strip_tags($content));
        
        $titleInContent = false;
        foreach ($titleWords as $word) {
            if (mb_strlen($word) > 3 && mb_strpos($contentLower, $word) !== false) {
                $titleInContent = true;
                break;
            }
        }
        
        return [
            'word_count' => $wordCount,
            'is_sufficient_length' => $wordCount >= 300,
            'title_in_content' => $titleInContent,
            'readability_score' => self::calculateReadabilityScore($content),
            'recommendations' => self::generateContentRecommendations($wordCount, $titleInContent)
        ];
    }

    /**
     * Вычисляет простой индекс читаемости
     */
    private static function calculateReadabilityScore(string $content): int
    {
        $text = strip_tags($content);
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($text);
        
        if (count($sentences) == 0) return 0;
        
        $avgWordsPerSentence = $words / count($sentences);
        
        // Простая формула читаемости (чем меньше слов в предложении, тем лучше)
        if ($avgWordsPerSentence <= 15) return 100;
        if ($avgWordsPerSentence <= 20) return 80;
        if ($avgWordsPerSentence <= 25) return 60;
        if ($avgWordsPerSentence <= 30) return 40;
        return 20;
    }

    /**
     * Генерирует рекомендации по улучшению контента
     */
    private static function generateContentRecommendations(int $wordCount, bool $titleInContent): array
    {
        $recommendations = [];
        
        if ($wordCount < 300) {
            $recommendations[] = 'Увеличьте объем контента до 300+ слов для лучшего SEO';
        }
        
        if (!$titleInContent) {
            $recommendations[] = 'Включите ключевые слова из заголовка в текст статьи';
        }
        
        if ($wordCount > 2000) {
            $recommendations[] = 'Разбейте длинную статью на разделы с подзаголовками';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'Контент соответствует базовым требованиям SEO';
        }
        
        return $recommendations;
    }
}
