<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: f97d497c160d73f434afb01c0c350e25"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            abort(500);
        }

        $result = json_decode($response);

        $provinces = $result->rajaongkir->results;
        return view('profile.index', [
            'title' => 'Profile',
            'user'  => Auth::user(),
            'provinces' => $provinces
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name'  => 'required|max:255',
            'email' => 'required|max:255',
            'telp'  => 'required|max:255',
            'address'   => 'required|max:255',
            'province'  => 'required',
            'city'  => 'required',
            'image' => 'image|max:2048',
            'zip_code'  => 'required|max:255'
        ]);

        if ($request->file('gambar')) {
            if ($request->gambarLama) {
                Storage::delete($request->gambarLama);
            }
            $validatedData['gambar'] = $request->file('gambar')->store('user-images');
        }
        User::where('id', Auth::id())
            ->update($validatedData);
        return ['success' => true, 'message' => 'Profile berhasil diupdate'];
    }
    public function getCity($province, Request $request)
    {
        if ($request->ajax()) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $province,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: f97d497c160d73f434afb01c0c350e25"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $city = json_decode($response)->rajaongkir->results;
            }

            return view('profile.city', [
                'city'  => $city,
                'user'  => Auth::user()
            ])->render();
        }
        abort(404);
    }
}
