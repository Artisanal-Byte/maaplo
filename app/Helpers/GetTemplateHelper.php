<?php

namespace App\Helpers;

use App\Models\Template;
use App\Models\Measurement;

class GetTemplateHelper
{

    public static function getTemplateData($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }
        // Fetch public templates (where user_id is null)
        $publicTemplates = Template::with(['measurements:id,slug'])
            ->whereNull('user_id')
            ->select('id', 'name', 'gender', 'body_part', 'svg_logo', 'design_details')
            ->get();

        // Fetch private templates for the current user
        $privateTemplates = Template::with(['measurements:id,slug'])
            ->where('user_id', $userId)
            ->select('id', 'name', 'gender', 'body_part', 'svg_logo', 'design_details')
            ->get();

        // Fetch all measurements
        $allMeasurements = Measurement::select('id', 'slug', 'measurements_logo')->get();

        return [
            'publicTemplates' => $publicTemplates,
            'privateTemplates' => $privateTemplates,
            'allMeasurements' => $allMeasurements
        ];
    }
}
