<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EssayGradingServiceProvider extends ServiceProvider
{
    /**
     * Grade an essay answer by comparing it to the correct answer
     * 
     * @param string $studentAnswer The student's essay answer
     * @param string $correctAnswer The correct essay answer from the database
     * @return array Returns grading results with score and correctness
     */
    public function gradeEssay($studentAnswer, $correctAnswer)
    {
        // Clean the text for comparison
        $cleanStudentAnswer = $this->cleanText($studentAnswer);
        $cleanCorrectAnswer = $this->cleanText($correctAnswer);

        // Calculate basic similarity
        similar_text($cleanStudentAnswer, $cleanCorrectAnswer, $similarity);

        // Calculate word overlap score (alternative approach)
        $wordOverlapScore = $this->calculateWordOverlap($cleanStudentAnswer, $cleanCorrectAnswer);

        // Calculate keyword presence
        $keywordScore = $this->calculateKeywordPresence($cleanStudentAnswer, $cleanCorrectAnswer);

        // Combine scores (weighted average)
        $finalSimilarity = ($similarity * 0.5) + ($wordOverlapScore * 0.3) + ($keywordScore * 0.2);

        // Determine final score and correctness
        $score = $this->mapSimilarityToScore($finalSimilarity);
        $isCorrect = $this->determineCorrectness($score);

        return [
            'score' => $score,
            'is_correct' => $isCorrect,
            'similarity' => $finalSimilarity,
            'metrics' => [
                'character_similarity' => $similarity,
                'word_overlap' => $wordOverlapScore,
                'keyword_presence' => $keywordScore
            ]
        ];
    }

    /**
     * Clean text for comparison by removing HTML tags, extra whitespace, etc.
     */
    private function cleanText($text)
    {
        // Remove HTML tags
        $text = strip_tags($text);

        // Convert to lowercase
        $text = mb_strtolower($text);

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        // Remove punctuation
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        // Trim whitespace
        return trim($text);
    }

    /**
     * Calculate word overlap between student answer and correct answer
     */
    private function calculateWordOverlap($studentAnswer, $correctAnswer)
    {
        // Get word arrays
        $studentWords = array_count_values(explode(' ', $studentAnswer));
        $correctWords = array_count_values(explode(' ', $correctAnswer));

        // Count overlapping words
        $overlap = 0;
        $totalCorrectWords = count($correctWords);

        foreach ($correctWords as $word => $count) {
            if (isset($studentWords[$word])) {
                $overlap += min($count, $studentWords[$word]);
            }
        }

        // Calculate percentage
        return $totalCorrectWords > 0 ? ($overlap / $totalCorrectWords) * 100 : 0;
    }

    /**
     * Calculate keyword presence in student answer
     */
    private function calculateKeywordPresence($studentAnswer, $correctAnswer)
    {
        // Extract keywords (words with 4+ characters)
        $correctWords = explode(' ', $correctAnswer);
        $keywords = array_filter($correctWords, function ($word) {
            return mb_strlen($word) >= 4;
        });

        if (empty($keywords)) {
            return 0;
        }

        // Count keywords present in student answer
        $presentKeywords = 0;
        foreach ($keywords as $keyword) {
            if (mb_strpos($studentAnswer, $keyword) !== false) {
                $presentKeywords++;
            }
        }

        // Calculate percentage
        return (count($keywords) > 0) ? ($presentKeywords / count($keywords)) * 100 : 0;
    }

    /**
     * Map similarity percentage to score
     */
    private function mapSimilarityToScore($similarity)
    {
        // Define grading scale
        if ($similarity >= 90) {
            return 1.0;
        } elseif ($similarity >= 80) {
            return 0.9;
        } elseif ($similarity >= 70) {
            return 0.8;
        } elseif ($similarity >= 60) {
            return 0.7;
        } elseif ($similarity >= 50) {
            return 0.6;
        } elseif ($similarity >= 40) {
            return 0.5;
        } elseif ($similarity >= 30) {
            return 0.4;
        } elseif ($similarity >= 20) {
            return 0.3;
        } elseif ($similarity >= 10) {
            return 0.2;
        } elseif ($similarity > 0) {
            return 0.1;
        } else {
            return 0;
        }
    }

    /**
     * Determine correctness based on score
     */
    private function determineCorrectness($score)
    {
        if ($score >= 0.9) {
            return 'Y';  // Fully correct
        } elseif ($score > 0) {
            return 'P';  // Partially correct
        } else {
            return 'N';  // Incorrect
        }
    }
}
