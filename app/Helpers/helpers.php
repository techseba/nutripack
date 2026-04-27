<?php

if (!function_exists('highlight')) {
    /**
     * Highlight matching text with glowing animation (dark/light mode)
     */
    function highlight($text, $query)
    {
        $query = trim($query);

        if (!$query) {
            return e($text);
        }

        $escapedText = e($text);

        // Tailwind utility classes for animated glow effect
        $highlightClass = 'relative bg-amber-500/40 text-amber-200 dark:bg-amber-500/30 dark:text-amber-100
                           px-1 rounded transition-colors
                           animate-pulse-glow';

        // Replace matched part with highlight span
        return preg_replace(
            '/(' . preg_quote($query, '/') . ')/i',
            '<span class="' . $highlightClass . '">$1</span>',
            $escapedText
        );
    }
}
