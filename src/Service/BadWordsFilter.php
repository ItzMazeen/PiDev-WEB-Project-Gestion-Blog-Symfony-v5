<?php

namespace App\Service;

class MyBadWordsFilter
{
    private $badWords;

    public function __construct(array $badWords)
    {
        $this->badWords = $badWords;
    }

    public function filter(string $text): string
    {
        foreach ($this->badWords as $badWord) {
            $text = preg_replace('/\b' . $badWord . '\b/iu', '****', $text);
        }

        return $text;
    }
}