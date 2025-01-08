<?php

namespace App\Http\Controllers;

use App\Support\Helpers\FileHelper;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function redirectToHomePage(Request $request)
    {
        $homePage = $request->user()->detectHomeRouteName();

        return redirect()->to($homePage);
    }

    public function uploadSimditorImage(Request $request)
    {
        $image = $request->file('image');
        $folder = $request->input('folder');
        $filename = FileHelper::uploadFile($image, $folder);

        return response()->json([
            'success' => true,
            'file_path' => asset($folder . '/' . $filename),
        ]);
    }

    public function navigateToPageNumber(Request $request)
    {
        $url = $request->input('full_url');
        $navigateToPage = $request->input('navigate_to_page');

        // Parse the URL and get the query as an array
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'] ?? '', $query);

        // Update the 'page' parameter with the new page number
        $query['page'] = $navigateToPage;

        // Build the modified URL with the updated query
        $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . http_build_query($query);

        // Redirect to the modified URL
        return redirect($newUrl);
    }
}
