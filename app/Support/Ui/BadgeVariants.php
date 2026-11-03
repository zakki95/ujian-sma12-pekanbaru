<?php

namespace App\Support\Ui;

class BadgeVariants
{
    /**
     * Generate Tailwind CSS classes for badge component.
     *
     * @param string $variant default|outline|ghost|destructive|secondary
     * @param string $size sm|default|lg
     * @param string|null $additionalClasses
     * @return string
     */
    public static function classes(string $variant = 'default', string $size = 'default', ?string $additionalClasses = null): string
    {
        $baseClasses = 'inline-flex items-center rounded-full font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';

        $variantClasses = [
            'default' => 'bg-brand-500 text-white hover:bg-brand-600 focus:ring-brand-500',
            'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus:ring-gray-500 dark:bg-gray-800 dark:text-gray-300',
            'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-brand-500 dark:border-gray-700 dark:text-gray-300',
            'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:text-gray-300 dark:hover:bg-gray-800',
            'destructive' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
        ];

        $sizeClasses = [
            'sm' => 'px-2 py-0.5 text-xs',
            'default' => 'px-2.5 py-0.5 text-sm',
            'lg' => 'px-3 py-1 text-base',
        ];

        $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['default']);

        if ($additionalClasses) {
            $classes .= ' ' . $additionalClasses;
        }

        return trim($classes);
    }
}