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
        foreach ($orderItemsData as $key => $orderItem) {
            $designDetailIds =  json_decode($orderItem['design_detail']);
            $getDesignDetailValue = DesignDetail::whereIn('id', $designDetailIds)->get()->toArray();
            foreach ($getDesignDetailValue as $key => $value) {
                $formatedValue = [
                    'body_part' => $value['body_part_value']['body_part'],
                    'body_section' => $value['value']
                ];
                $temp = $formatedValue;
                // dd($temp);
            }
        }
        return $temp;
    }
}
