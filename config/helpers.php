<?php

if (!function_exists('getPathologyColor')) {
    function getPathologyColor($pathology) {
        $colors = [
            'HIPERTENSION' => '#e74a3b',
            'DIABETES' => '#fd7e14',
            'OBESIDAD' => '#20c997',
            'TABAQUISMO' => '#6f42c1',
            'ALCOHOLISMO' => '#dc3545',
            'OTRO' => '#6c757d'
        ];
        
        return $colors[$pathology] ?? '#6c757d';
    }
}

if (!function_exists('formatPercentage')) {
    function formatPercentage($part, $total) {
        if ($total == 0) return 0;
        return number_format(($part / $total) * 100, 1);
    }
}