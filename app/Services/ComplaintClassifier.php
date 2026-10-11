<?php

namespace App\Services;

use App\Models\Keyword;
use Illuminate\Support\Str;

class ComplaintClassifier
{
    
    public function classify(string $title, string $description): ?int
    {
        
        $text = Str::lower($title . ' ' . $description);

       
        $keywords = Keyword::all();

       
        $scores = [];

        foreach ($keywords as $keyword) {
            $needle = Str::lower($keyword->keyword);

            if ($needle !== '' && Str::contains($text, $needle)) {
                $id = $keyword->category_id;
                $scores[$id] = ($scores[$id] ?? 0) + 1;
            }
        }

        if (empty($scores)) {
            return null;
        }

        
        arsort($scores);

        return array_key_first($scores);
    }
}