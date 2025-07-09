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
     * @param string $type
     * @return string
     */
    public static function imageProccess(
        UploadedFile $image,
        $customerId,
        $username,
        $userId,
        $customerName,
        $label = 'organization_logo',
        $type = 'user'
    ) {
        try {
            $timestamp = time();
            $fileName = "{$label}_{$timestamp}.webp";

            // Convert username and customer name to lowercase and replace spaces with underscores
            $usernameFormatted = strtolower(str_replace(' ', '_', $username));
            $customerNameFormatted = strtolower(str_replace(' ', '_', $customerName));

            // New base path format
            $basePath = "user_name_{$usernameFormatted}_id_{$userId}/customers/customers_name_{$customerNameFormatted}_id_{$customerId}";

            // Personal images subfolder
            $folderPath = "{$basePath}/personal_images";
            $fullPath = "{$folderPath}/{$fileName}";

            $directory = storage_path("app/public/{$folderPath}/");

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $storagePath = $directory . $fileName;

            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);
            $image = $image->scaleDown(width: 2000, height: 2000);
            $encoded = $image->toWebp(60);
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public static function storeOrderItemImage(
        UploadedFile $image,
        string $username,
        int $userId,
        string $customerName,
        int $customerId,
        int $itemId,
        string $label = 'image'
    ): string {
        try {
            $timestamp = time();
            $fileName = "{$label}_{$timestamp}.webp";

            // Convert username and customer name to lowercase and replace spaces with underscores
            $usernameFormatted = strtolower(str_replace(' ', '_', $username));
            $customerNameFormatted = strtolower(str_replace(' ', '_', $customerName));

            // New base path format
            $basePath = "user_name_{$usernameFormatted}_id_{$userId}/customers/customer_id_{$customerId}";

            // Order images subfolder with itemId
            $folderPath = "{$basePath}/orders_images/item_id_{$itemId}";

            $fullPath = "{$folderPath}/{$fileName}";

            $directory = storage_path("app/public/{$folderPath}/");

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $storagePath = $directory . $fileName;

            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);
            $image = $image->scaleDown(width: 2000, height: 2000);
            $encoded = $image->toWebp(60);
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    // public static function saveThumbnail(UploadedFile $image, $customerId, $username, $userId, $customerName, $label = 'thumbnail_logo', $type = 'user')
    // {
    //     try {
    //         $timestamp = time();
    //         $fileName = "{$label}_{$timestamp}.webp";

    //         // New folder path with user_name_user_id structure
    //         $folderPath = "users/{$username}_{$userId}/user_organization_logo";
    //         $fullPath = "{$folderPath}/{$label}/{$fileName}";
    //         $directory = storage_path("app/public/{$folderPath}/{$label}/");

    //         // Create directory if it doesn't exist
    //         if (!file_exists($directory)) {
    //             mkdir($directory, 0755, true);
    //         }

    //         $storagePath = $directory . $fileName;

    //         // Use Intervention Image
    //         $manager = new ImageManager(config('image.driver'));
    //         $image = $manager->read($image);
    //         $image = $image->scaleDown(width: 40, height: 40); // Resize
    //         $encoded = $image->toWebp(50); // Lower quality
    //         $encoded->save($storagePath);

    //         return "storage/{$fullPath}";
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //     }
    // }

    public static function imageAvatar(
        UploadedFile $image,
        string $username,
        int $userId,
        string $customerName,
        int $customerId
    ): string {
        try {
            $timestamp = time();
            $fileName = "avatar_{$timestamp}.webp";

            // Format names
            $usernameFormatted = strtolower(str_replace(' ', '_', $username));
            $customerNameFormatted = strtolower(str_replace(' ', '_', $customerName));

            // Same path as imageProccess
            $folderPath = "user_name_{$usernameFormatted}_id_{$userId}/customers/customers_name_{$customerNameFormatted}_id_{$customerId}/personal_images";
            $fullPath = "{$folderPath}/{$fileName}";
            $directory = storage_path("app/public/{$folderPath}/");

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $storagePath = $directory . $fileName;

            // Process image
            $manager = new ImageManager(config('image.driver'));
            $image = $manager->read($image);
            $encoded = $image->toWebp(40); // compress avatar more
            $encoded->save($storagePath);

            return "storage/{$fullPath}";
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
