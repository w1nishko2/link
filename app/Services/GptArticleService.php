<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GptArticleService
{
    private $apiKey;
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key не настроен. Проверьте переменную OPENAI_API_KEY в .env файле.');
        }
    }

    /**
     * Генерирует статью с помощью GPT
     *
     * @param string $topic
     * @param string $style
     * @param User $author
     * @return array
     */
    public function generateArticle($topic, $style = 'informative', $author = null)
    {
        Log::channel('gpt')->info('GPT: Начало генерации статьи', [
            'topic' => $topic,
            'style' => $style,
            'author_id' => $author ? $author->id : null,
            'author_username' => $author ? $author->username : null,
        ]);

        try {
            // Находим пользователя @weebs если автор не указан
            if (!$author) {
                $author = User::where('username', 'weebs')->first();
                if (!$author) {
                    Log::channel('gpt')->error('GPT: Пользователь @weebs не найден');
                    throw new \Exception('Пользователь @weebs не найден');
                }
            }

            $prompt = $this->buildPrompt($topic, $style, $author);
            
            Log::channel('gpt')->info('GPT: Отправка запроса к OpenAI API', [
                'api_url' => $this->apiUrl,
                'model' => 'gpt-4',
                'prompt_length' => strlen($prompt),
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->apiUrl, [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Ты профессиональный копирайтер, который пишет качественные статьи для блога. Отвечай только на русском языке.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.7,
            ]);

            Log::channel('gpt')->info('GPT: Ответ от OpenAI API получен', [
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'response_size' => strlen($response->body()),
            ]);

            if ($response->failed()) {
                Log::channel('gpt')->error('GPT: Ошибка API OpenAI', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                throw new \Exception('Ошибка API OpenAI: ' . $response->body());
            }

            $data = $response->json();
            
            Log::channel('gpt')->info('GPT: Парсинг ответа от OpenAI', [
                'response_structure' => array_keys($data),
                'choices_count' => isset($data['choices']) ? count($data['choices']) : 0,
            ]);
            
            if (!isset($data['choices'][0]['message']['content'])) {
                Log::channel('gpt')->error('GPT: Неверный формат ответа от OpenAI', [
                    'response_data' => $data,
                ]);
                throw new \Exception('Неверный формат ответа от OpenAI');
            }

            $content = $data['choices'][0]['message']['content'];
            
            Log::channel('gpt')->info('GPT: Контент получен от OpenAI', [
                'content_length' => strlen($content),
                'content_preview' => substr($content, 0, 200) . '...',
            ]);
            
            $parsedArticle = $this->parseArticleContent($content, $topic, $author);
            
            Log::channel('gpt')->info('GPT: Статья успешно сгенерирована', [
                'title' => $parsedArticle['title'],
                'excerpt_length' => strlen($parsedArticle['excerpt']),
                'content_length' => strlen($parsedArticle['content']),
            ]);
            
            return $parsedArticle;

        } catch (\Exception $e) {
            Log::channel('gpt')->error('GPT: Ошибка генерации статьи', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'topic' => $topic,
                'style' => $style,
            ]);
            throw $e;
        }
    }

    /**
     * Создает статью в базе данных
     *
     * @param array $articleData
     * @param User $author
     * @param bool $publish
     * @return Article
     */
    public function createArticle($articleData, $author, $publish = false)
    {
        Log::channel('gpt')->info('GPT: Создание статьи в базе данных', [
            'author_id' => $author->id,
            'author_username' => $author->username,
            'title' => $articleData['title'],
            'publish' => $publish,
        ]);

        try {
            $slug = Str::slug($articleData['title']);
            
            // Проверяем уникальность slug
            $originalSlug = $slug;
            $counter = 1;
            while (Article::where('slug', $slug)->where('user_id', $author->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
                
                Log::channel('gpt')->info('GPT: Slug уже существует, создаем новый', [
                    'original_slug' => $originalSlug,
                    'new_slug' => $slug,
                    'counter' => $counter,
                ]);
            }

            $readTime = $this->calculateReadTime($articleData['content']);
            $keywords = $this->generateKeywords($articleData['title'], $articleData['content']);

            $article = Article::create([
                'user_id' => $author->id,
                'title' => $articleData['title'],
                'slug' => $slug,
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'is_published' => $publish,
                'read_time' => $readTime,
                'meta_description' => $articleData['excerpt'],
                'meta_keywords' => $keywords,
            ]);

            Log::channel('gpt')->info('GPT: Статья успешно создана в базе данных', [
                'article_id' => $article->id,
                'slug' => $article->slug,
                'read_time' => $readTime,
                'keywords_count' => str_word_count($keywords),
                'is_published' => $article->is_published,
            ]);

            return $article;

        } catch (\Exception $e) {
            Log::channel('gpt')->error('GPT: Ошибка создания статьи в базе данных', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'article_title' => $articleData['title'] ?? 'unknown',
                'author_id' => $author->id,
            ]);
            throw $e;
        }
    }

    /**
     * Строит промпт для GPT
     */
    private function buildPrompt($topic, $style, $author)
    {
        $stylePrompts = [
            'informative' => 'информативном и образовательном стиле',
            'casual' => 'неформальном и дружелюбном стиле',
            'professional' => 'профессиональном и деловом стиле',
            'creative' => 'творческом и оригинальном стиле',
        ];

        $styleText = $stylePrompts[$style] ?? $stylePrompts['informative'];

        return "Напиши статью на тему: \"{$topic}\" в {$styleText}.

Статья должна быть структурирована следующим образом:

ЗАГОЛОВОК: [Привлекательный заголовок статьи]

КРАТКОЕ_ОПИСАНИЕ: [Краткое описание статьи в 1-2 предложениях для анонса]

СОДЕРЖАНИЕ:
[Основной текст статьи с подзаголовками, списками и примерами. Используй HTML теги для форматирования: <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>. Статья должна быть объемом 800-1500 слов.]

Требования:
- Статья должна быть полезной и информативной
- Используй подзаголовки для структуры
- Добавь практические советы или примеры
- Текст должен быть уникальным и качественным
- Пиши от имени эксперта в данной области";
    }

    /**
     * Парсит содержимое ответа GPT
     */
    private function parseArticleContent($content, $topic, $author)
    {
        $lines = explode("\n", $content);
        $title = '';
        $excerpt = '';
        $articleContent = '';
        
        $currentSection = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (str_starts_with($line, 'ЗАГОЛОВОК:')) {
                $title = trim(str_replace('ЗАГОЛОВОК:', '', $line));
                continue;
            }
            
            if (str_starts_with($line, 'КРАТКОЕ_ОПИСАНИЕ:')) {
                $excerpt = trim(str_replace('КРАТКОЕ_ОПИСАНИЕ:', '', $line));
                continue;
            }
            
            if (str_starts_with($line, 'СОДЕРЖАНИЕ:')) {
                $currentSection = 'content';
                continue;
            }
            
            if ($currentSection === 'content') {
                $articleContent .= $line . "\n";
            }
        }

        // Если заголовок не найден, используем тему
        if (empty($title)) {
            $title = $topic;
        }

        // Если краткое описание не найдено, создаем из первых строк контента
        if (empty($excerpt)) {
            $excerpt = Str::limit(strip_tags($articleContent), 160);
        }

        return [
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => trim($articleContent),
        ];
    }

    /**
     * Подсчитывает время чтения статьи
     */
    private function calculateReadTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $readTime = ceil($wordCount / 200); // 200 слов в минуту
        return max(1, $readTime);
    }

    /**
     * Генерирует ключевые слова для SEO
     */
    private function generateKeywords($title, $content)
    {
        $text = $title . ' ' . strip_tags($content);
        $words = str_word_count(mb_strtolower($text), 1, 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя');
        
        // Убираем короткие слова и стоп-слова
        $stopWords = ['это', 'для', 'как', 'что', 'его', 'она', 'они', 'все', 'или', 'без', 'при', 'так', 'был', 'быть', 'есть'];
        $words = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 3 && !in_array($word, $stopWords);
        });
        
        $wordCounts = array_count_values($words);
        arsort($wordCounts);
        
        return implode(', ', array_slice(array_keys($wordCounts), 0, 10));
    }

    /**
     * Получает доступные стили генерации
     */
    public function getAvailableStyles()
    {
        return [
            'informative' => 'Информативный',
            'casual' => 'Неформальный',
            'professional' => 'Профессиональный',
            'creative' => 'Творческий',
        ];
    }

    /**
     * Генерирует несколько тем для статей
     */
    public function generateTopicIdeas($category = 'общие')
    {
        Log::channel('gpt')->info('GPT: Генерация идей тем', [
            'category' => $category,
        ]);

        try {
            $prompt = "Предложи 10 интересных тем для статей в категории '{$category}'. 
                      Каждая тема должна быть актуальной и полезной для читателей.
                      Ответь списком, каждая тема с новой строки, без нумерации.";

            Log::channel('gpt')->info('GPT: Отправка запроса генерации идей к OpenAI', [
                'prompt_length' => strlen($prompt),
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.8,
            ]);

            Log::channel('gpt')->info('GPT: Ответ идей от OpenAI получен', [
                'status_code' => $response->status(),
                'response_size' => strlen($response->body()),
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                $ideas = array_filter(explode("\n", $content));
                
                Log::channel('gpt')->info('GPT: Идеи тем успешно сгенерированы', [
                    'ideas_count' => count($ideas),
                    'ideas' => $ideas,
                ]);
                
                return $ideas;
            }

            Log::channel('gpt')->warning('GPT: Не удалось получить идеи тем', [
                'status_code' => $response->status(),
                'response_body' => $response->body(),
            ]);

            return [];

        } catch (\Exception $e) {
            Log::channel('gpt')->error('GPT: Ошибка генерации тем', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'category' => $category,
            ]);
            return [];
        }
    }
}