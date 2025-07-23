<?php

namespace App\Support\Traits\Model;

use Illuminate\Http\Request;
use App\Support\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;

trait UploadsFile
{
    /**
     * Handle file upload for the model, updating the model's file attribute.
     *
     * @param string $attribute The model attribute where the filename should be stored.
     * @param string $path The path where the file will be uploaded.
     * @param string|null $name The desired filename (without extension).
     * @param Request|null $request Optional request object. Falls back to the global request if null.
     * @return string|null The full path to the uploaded file on success, or null on failure.
     */
    public function uploadFile(string $attribute, string $path, ?string $name = null, ?Request $request = null): ?string
    {
        // Use the provided request object or fall back to the global request.
        $request = $request ?? request();

        // Check if the file exists in the request and is valid.
        if (!$request->hasFile($attribute) || !$request->file($attribute)->isValid()) {
            return null; // Return null if no valid file is found.
        }

        // Retrieve the file from the request.
        $file = $request->file($attribute);

        try {
            // Upload the file using the FileHelper class.
            $uploadedFilename = FileHelper::uploadFile($file, $path, $name);

            // Update the model's file attribute with the uploaded filename.
            $this->{$attribute} = $uploadedFilename;
            $this->save(); // Save the model to persist the filename.

            // Return the full path to the uploaded file.
            return $path . '/' . $uploadedFilename;
        } catch (\Exception $e) {
            // Handle any errors (e.g., log the error or return false).
            return null; // Return null on error.
        }
    }
}
