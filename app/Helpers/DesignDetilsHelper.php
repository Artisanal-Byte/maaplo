<?php

namespace App\Helpers;

use App\Models\DesignDetail;

class DesignDetilsHelper
{
    /**
     * Create a new class instance.
     */

    public static function getDesignDetailsData(array $orderItemsData): array
    {
        $temp = [];

        foreach ($orderItemsData as $orderItem) {
            $designDetailIds = json_decode($orderItem['design_detail'], true);

            if (!is_array($designDetailIds)) {
                continue; // skip invalid or empty data
            }

            $designDetails = DesignDetail::whereIn('id', array_values($designDetailIds))->with('bodyPartValue')->get();

            foreach ($designDetails as $detail) {
                $temp[] = [
                    'body_part' => $detail->bodyPartValue->body_part ?? 'Unknown',
                    'body_section' => $detail->value,
                ];
            }
        }

        return $temp;
    }
}
