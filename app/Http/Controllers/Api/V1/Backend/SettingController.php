<?php

namespace App\Http\Controllers\Api\V1\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(['setting' => Setting::first()], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'web_name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'facebook' => 'required',
            'instagram' => 'required',
            'youtube' => 'required'
        ]);
        $setting = Setting::first();
        $setting->update([
            'web_name' => $request->web_name,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
        ]);
        return response()->json([], 201);
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);
        $image = $request->file('image');
        $input['imageName'] = time() . '.' . $image->extension();
        $image = Image::make($image->getRealPath());
        $image->resize(100, 100);
        $image->save(storage_path() . DIRECTORY_SEPARATOR . 'app/public/logo/' . $input['imageName'], 80);

        $setting = Setting::first();

        if (Storage::disk('public')->exists($setting->logo)) {
            Storage::disk('public')->delete($setting->logo);
        }

        $setting->update([
            'logo' => 'logo' . "/" . $input['imageName']
        ]);

        return response()
            ->json(['msg' => 'ok!'], 200);
    }
}
