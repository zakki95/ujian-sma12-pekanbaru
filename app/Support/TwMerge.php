<?php

namespace App\Support;

class TwMerge
{
    /**
     * Merge Tailwind CSS classes with proper priority.
     *
     * @param string ...$classes
     * @return string
     */
    public static function merge(...$classes): string
    {
        $classList = [];
        
        foreach ($classes as $class) {
            if (is_string($class)) {
                $classList = array_merge($classList, explode(' ', trim($class)));
            } elseif (is_array($class)) {
                $classList = array_merge($classList, $class);
            }
        }
        
        // Filter empty strings
        $classList = array_filter(array_map('trim', $classList));
        
        return implode(' ', array_values($classList));
    }
}