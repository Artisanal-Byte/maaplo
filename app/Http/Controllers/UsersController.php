<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

class ImageHelper
{
    /**
     * Save the customer image and return its storage path.
     *
     * @param UploadedFile $image
     * @param int $customerId
     * @param string $username
     * @param int $userId
     * @param string $customerName
     * @param string $label
     * @return string
     */
    public static function imageProccess(UploadedFile $image, $customerId, $username, $userId, $customerName, $label = 'organization_logo', $type = 'user')
    {
        try {
            $timestamp = time();
            $fileName = "{$label}_{$timestamp}.webp";

            // Conditional folder path
            if ($type === 'customer') {
                $folderPath = "customers/{$username}_{$userId}/images";
            } else {
                $folderPath = "users_organization_logo/{$username}_{$userId}";
            }

            // $folderPath = "  customers/{$username}_{$userId}/{$customerName}_{$customerId}/images";
            $fullPath = "{$folderPath}/{$fileName}";
            $directory = storage_path("app/public/{$folderPath}/");

            // Define storage path
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $storagePath = $directory . $fileName;

            // Use configured image driver (from config/image.php)
            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);

            $image = $image->scaleDown(width: 2000, height: 2000);
            // Read and encode image
            $image = $manager->read($image);
            $encoded = $image->toWebp(60);
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
