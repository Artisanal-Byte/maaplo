<?php

if (!function_exists('extractDesignDetailIds')) {
    /**
     * Extracts numeric design detail IDs from strings like "front part/3,4,6".
     *
     * @param array $designDetails
     * @return array
     */
    function extractDesignDetailIds(array $designDetails): array
    {
        return collect($designDetails)
            ->flatMap(function ($entry) {
                if (!str_contains($entry, '/')) return [];
                [, $ids] = explode('/', $entry);
                return explode(',', $ids);
            })
            ->map(fn($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
