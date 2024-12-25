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
}
