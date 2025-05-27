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

            // Conditional folder path based on $type (customer or user)
            if ($type === 'customer') {
                $folderPath = "customers/{$username}_{$userId}/images";
            } else {
                $folderPath = "users/{$username}_{$userId}/user_organization_logo";
            }

            // Full path to file
            $fullPath = "{$folderPath}/{$fileName}";
            $directory = storage_path("app/public/{$folderPath}/");

            // Create the directory if it doesn't exist
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $storagePath = $directory . $fileName;

            // Use Intervention Image for image processing
            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);
            $image = $image->scaleDown(width: 2000, height: 2000); // Resize to 2000px max
            $encoded = $image->toWebp(60); // Compress the image to WebP format at 60% quality
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public static function saveThumbnail(UploadedFile $image, $customerId, $username, $userId, $customerName, $label = 'thumbnail_logo', $type = 'user')
    {
        try {
            $timestamp = time();
            $fileName = "{$label}_{$timestamp}.webp";

            // New folder path with user_name_user_id structure
            $folderPath = "users/{$username}_{$userId}/user_organization_logo";
            $fullPath = "{$folderPath}/{$label}/{$fileName}";
            $directory = storage_path("app/public/{$folderPath}/{$label}/");

            // Create directory if it doesn't exist
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $storagePath = $directory . $fileName;

            // Use Intervention Image
            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);
            $image = $image->scaleDown(width: 40, height: 40); // Resize
            $encoded = $image->toWebp(50); // Lower quality
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public static function imageAvatar(UploadedFile $image, string $username, int $userId): string
    {
        try {
            $timestamp = time();
            $fileName = "avatar_{$timestamp}.webp";
            $folderPath = "users/useravatar/{$username}_{$userId}";
            $fullPath = "{$folderPath}/{$fileName}";
            $directory = storage_path("app/public/{$folderPath}/");

            // Create directory if it doesn't exist
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $storagePath = $directory . $fileName;

            // Use Intervention Image
            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);
            // $image = $image->scaleDown(width: 40, height: 40); // Resize if necessary
            $encoded = $image->toWebp(40); // Compress to webp
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
