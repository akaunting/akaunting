<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait Tailwind
{
    public function getHexCodeOfTailwindClass($class)
    {
        $colors = [
            'gray'          => '#6b7280',
            'gray-50'       => '#f9fafb',
            'gray-100'      => '#f3f4f6',
            'gray-200'      => '#e5e7eb',
            'gray-300'      => '#d1d5db',
            'gray-400'      => '#9ca3af',
            'gray-500'      => '#6b7280',
            'gray-600'      => '#4b5563',
            'gray-700'      => '#374151',
            'gray-800'      => '#1f2937',
            'gray-900'      => '#111827',

            'red'           => '#cc0000',
            'red-50'        => '#fcf2f2',
            'red-100'       => '#fae6e6',
            'red-200'       => '#f2bfbf',
            'red-300'       => '#eb9999',
            'red-400'       => '#db4d4d',
            'red-500'       => '#cc0000',
            'red-600'       => '#b80000',
            'red-700'       => '#990000',
            'red-800'       => '#7a0000',
            'red-900'       => '#640000',

            'yellow'        => '#eab308',
            'yellow-50'     => '#fefce8',
            'yellow-100'    => '#fef9c3',
            'yellow-200'    => '#fef08a',
            'yellow-300'    => '#fde047',
            'yellow-400'    => '#facc15',
            'yellow-500'    => '#eab308',
            'yellow-600'    => '#ca8a04',
            'yellow-700'    => '#a16207',
            'yellow-800'    => '#854d0e',
            'yellow-900'    => '#713f12',

            'green'         => '#6ea152',
            'green-50'      => '#f8faf6',
            'green-100'     => '#f1f6ee',
            'green-200'     => '#dbe8d4',
            'green-300'     => '#c5d9ba',
            'green-400'     => '#9abd86',
            'green-500'     => '#6ea152',
            'green-600'     => '#63914a',
            'green-700'     => '#53793e',
            'green-800'     => '#426131',
            'green-900'     => '#364f28',

            'blue'          => '#006ea6',
            'blue-50'       => '#f2f8fb',
            'blue-100'      => '#e6f1f6',
            'blue-200'      => '#bfdbe9',
            'blue-300'      => '#99c5db',
            'blue-400'      => '#4d9ac1',
            'blue-500'      => '#006ea6',
            'blue-600'      => '#006395',
            'blue-700'      => '#00537d',
            'blue-800'      => '#004264',
            'blue-900'      => '#003651',

            'indigo'        => '#6366f1',
            'indigo-50'     => '#eef2ff',
            'indigo-100'    => '#e0e7ff',
            'indigo-200'    => '#c7d2fe',
            'indigo-300'    => '#a5b4fc',
            'indigo-400'    => '#818cf8',
            'indigo-500'    => '#6366f1',
            'indigo-600'    => '#4f46e5',
            'indigo-700'    => '#4338ca',
            'indigo-800'    => '#3730a3',
            'indigo-900'    => '#312e81',

            'purple'        => '#55588b',
            'purple-50'     => '#f7f7f9',
            'purple-100'    => '#eeeef3',
            'purple-200'    => '#d5d5e2',
            'purple-300'    => '#bbbcd1',
            'purple-400'    => '#888aae',
            'purple-500'    => '#55588b',
            'purple-600'    => '#4d4f7d',
            'purple-700'    => '#404268',
            'purple-800'    => '#333553',
            'purple-900'    => '#2a2b44',

            'pink'          => '#ec4899',
            'pink-50'       => '#fdf2f8',
            'pink-100'      => '#fce7f3',
            'pink-200'      => '#fbcfe8',
            'pink-300'      => '#f9a8d4',
            'pink-400'      => '#f472b6',
            'pink-500'      => '#ec4899',
            'pink-600'      => '#db2777',
            'pink-700'      => '#be185d',
            'pink-800'      => '#9d174d',
            'pink-900'      => '#831843',
        ];

        return Arr::exists($colors, $class)
                ? $colors[$class]
                : $class;
    }
}
