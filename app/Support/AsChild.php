<?php

namespace App\Support;

class AsChild
{
    /**
     * Apply attributes to the first HTML element in the slot content.
     * This implements the "asChild" pattern from Radix UI / Reka UI.
     *
     * @param string $slotContent HTML content from the slot
     * @param array $attributes Attributes to apply (key => value)
     * @param string|null $classes CSS classes to merge
     * @return string Modified HTML
     */
    public static function apply(string $slotContent, array $attributes = [], ?string $classes = null): string
    {
        // Find the first HTML tag in the slot
        if (preg_match('/<([a-zA-Z][a-zA-Z0-9]*)\b([^>]*)>(.*?)<\/\1>/s', $slotContent, $matches)) {
            $tag = $matches[1];
            $existingAttrs = $matches[2];
            $innerContent = $matches[3];
            
            // Build new attributes string
            $newAttrs = trim($existingAttrs);
            
            // Add or merge attributes
            foreach ($attributes as $key => $value) {
                if ($key === 'class' && $classes) {
                    // Merge classes
                    $newAttrs .= ' ' . $classes;
                } elseif (is_bool($value)) {
                    if ($value) {
                        $newAttrs .= ' ' . $key;
                    }
                } else {
                    $newAttrs .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
                }
            }
            
            // If no class in attributes but we have classes to add
            if (!isset($attributes['class']) && $classes) {
                $newAttrs .= ' class="' . $classes . '"';
            }
            
            $newAttrs = preg_replace('/\s+/', ' ', trim($newAttrs));
            
            return "<{$tag}{$newAttrs}>{$innerContent}</{$tag}>";
        }
        
        // If no valid HTML tag found, return original content
        return $slotContent;
    }
}