<?php

namespace Swis;

/**
 * Class TextSnippet
 */
class TextSnippet
{
    protected static $specialChars = ['Â', 'Ã', 'Ä', 'À', 'Á', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
    protected static $specialReplaces = ['A', 'A', 'A', 'A', 'A', 'A', 'E', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'P', 'B', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'b', 'y', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
    protected static $highlightTemplate = '<span class="highlighted">%word%</span>';
    protected static $minWords = 30;
    protected static $maxWords = 100;

    /**
     * Break a text into sentences
     *
     * @param string $text
     * @return array
     */
    public static function breakIntoSentences(string $text): array
    {
        return preg_split('/(?<=[.?!;:])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Set the minimum number of words, returned in the snippet
     *
     * @param int $minWords
     */
    public static function setMinWords(int $minWords)
    {
        self::$minWords = $minWords;
    }

    /**
     * Set the maximum number of words, returned in the snippet
     *
     * @param int $maxWords
     */
    public static function setMaxWords(int $maxWords)
    {
        self::$maxWords = (int)$maxWords;
    }

    /**
     * Returns an array of matched sentences against the words in the query
     * Keys are the original sentence positions
     *
     * @param string $query
     * @param array $sentences
     * @return array
     */
    protected static function getMatchedSentences(string $query, array $sentences): array
    {
        $queryWords = str_word_count($query, 1, implode('', self::$specialChars));
        $matchedSentences = [];
        foreach ($queryWords as $word) {
            foreach ($sentences as $key => $sentence) {
                if (preg_match('/\b' . preg_quote(str_replace(self::$specialChars, self::$specialReplaces, $word)) . '\b/i', str_replace(self::$specialChars, self::$specialReplaces, $sentence))) {
                    // if word is matched in this sentence (word boundary)
                    $matchedSentences[$key] = $sentence;
                }
            }
        }
        ksort($matchedSentences);
        return $matchedSentences;
    }

    /**
     * Set the template for the highlighting, for example '<em>%word%</em>'
     *
     * @param string $template
     * @throws RuntimeException
     */
    public static function setHighlightTemplate(string $template)
    {
        if (strpos($template, '%word%') === false) {
            throw new \RuntimeException('HighlightTemplate should contain "%word%"');
        }
        self::$highlightTemplate = $template;
    }

    /**
     * Highlight words, while keeping casing and accents
     *
     * @param string $query
     * @param string $text
     * @return string
     */
    public static function highlightMatches(string $query, string $text): string
    {
        $queryWords = str_word_count($query, 1, implode('', self::$specialChars));
        $snippetWords = str_word_count(str_replace('-', ' ', $text), 1, implode('', self::$specialChars));
        $replaces = [];
        foreach ($queryWords as $word) {
            foreach ($snippetWords as $snippetWord) {
                // case-insensitive matching. accent-insensitive matching
                if (strtolower(str_replace(self::$specialChars, self::$specialReplaces, $word)) ==
                    strtolower(str_replace(self::$specialChars, self::$specialReplaces, $snippetWord))) {
                    $replaces['/\b' . preg_quote($snippetWord) . '\b/'] = str_replace('%word%', $snippetWord, self::$highlightTemplate);
                }
            }
        }
        return preg_replace(array_keys($replaces), array_values($replaces), $text);
    }

    /**
     * Create the snippet and highlight matched words
     *
     * @param string $query
     * @param string $text
     * @param bool $highlight
     * @return string
     */
    public static function createSnippet(string $query, string $text, bool $highlight = true)
    {
        $query = htmlspecialchars($query);
        $text = strip_tags($text);
        $sentences = self::breakIntoSentences($text);
        $matchedSentences = self::getMatchedSentences($query, $sentences);
        $result = '';
        $wordCounter = 0;
        $lastKey = key($matchedSentences) - 1;
        foreach ($matchedSentences as $key => $sentence) {
            $wordCounter += str_word_count($sentence, 0, implode('', self::$specialChars));
            if ($wordCounter < self::$maxWords || $result === '') {
                if ($key != $lastKey + 1) {
                    // if this sentence is not the next sentence, add ' ... '
                    $result .= ' ...';
                }
                $result .= ' ' . $sentence;
            }
            $lastKey = $key;
        }

        // Matched text is smaller than [minWords]. Try to add next sentences
        while ($wordCounter < self::$minWords && isset($sentences[$lastKey + 1]) && str_word_count($sentences[$lastKey + 1], 0, implode('', self::$specialChars)) + $wordCounter < self::$maxWords) {
            $result .= ' ' . $sentences[$lastKey + 1];
            $wordCounter += str_word_count($sentences[$lastKey + 1], 0, implode('', self::$specialChars));
            $lastKey++;
        }

        // Matched text is possibly still to small. Try to add sentences before the first sentence
        $firstKey = key($matchedSentences);
        while ($wordCounter < self::$minWords && isset($sentences[$firstKey - 1]) && str_word_count($sentences[$firstKey - 1], 0, implode('', self::$specialChars)) + $wordCounter < self::$maxWords) {
            // add this sentence before the current result
            $result = $sentences[$firstKey - 1] . ' ' . $result;
            $wordCounter += str_word_count($sentences[$firstKey - 1], 0, implode('', self::$specialChars));
            $firstKey--;
        }

        if ($highlight === true) {
            return self::highlightMatches($query, trim($result));
        }

        return trim($result);
    }
}