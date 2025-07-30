<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Show a photo from the storage.
     *
     * @param string $photoName
     * @param int|null $year actual year, if applicable
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */

   public function showPhoto(
       string $photoName,
       ?int $year= null ): \Symfony\Component\HttpFoundation\BinaryFileResponse
   {
        $basePath = (isset($_ENV['BASE_PATH']) && !empty($_ENV['BASE_PATH']))
            ? 'private/'.env('BASE_PATH').'/'
            : 'private/';
        // If a year is provided, set the locale to that year
        if (!isset ($year) || $year === null) {
            $actual_year = (int) Carbon::now()->year;
            $path = "{$basePath}{$photoName}";
        }else {
            $path = "{$basePath}{$year}/{$photoName}";
        }


        // Check if the photo exists in the storage
        if (Storage::disk('local')->exists($path)) {
            // Return the photo as a response
            return response()->file(storage_path("app/{$path}"));
        }

        $photoPath = storage_path('app/' . $path);

        if (!file_exists($photoPath)) {
            abort(404, 'Photo not found');
        }

        return response()->file($photoPath);
    }

}
