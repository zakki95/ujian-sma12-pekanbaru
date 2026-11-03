<?php

namespace App\Support\Ui;

class ButtonVariants
{
    /**
     * Generate Tailwind CSS classes for button component.
     *
     * @param string $variant default|outline|ghost|destructive|link
     * @param string $size default|xs|sm|lg|icon|icon-xs|icon-sm|icon-lg
     * @param string|null $additionalClasses
     * @return string
     */
    public static function classes(string $variant = 'default', string $size = 'default', ?string $additionalClasses = null): string
    {
        $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

        $variantClasses = [
            'default' => 'bg-brand-500 text-white hover:bg-brand-600 focus:ring-brand-500 dark:bg-brand-600 dark:hover:bg-brand-700',
            'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
            'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:text-gray-300 dark:hover:bg-gray-800',
            'destructive' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700',
            'link' => 'text-brand-500 underline-offset-4 hover:underline focus:ring-brand-500 dark:text-brand-400',
        ];

        $sizeClasses = [
            'default' => 'px-4 py-2 text-sm',
            'xs' => 'px-2.5 py-1.5 text-xs',
            'sm' => 'px-3 py-2 text-sm',
            'lg' => 'px-6 py-3 text-base',
            'icon' => 'h-10 w-10',
            'icon-xs' => 'h-6 w-6',
            'icon-sm' => 'h-8 w-8',
            'icon-lg' => 'h-11 w-11',
        ];

        $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['default']);

        if ($additionalClasses) {
            $classes .= ' ' . $additionalClasses;
        }

        return trim($classes);
    }
}
