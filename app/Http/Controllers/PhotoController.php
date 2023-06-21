<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Profile;

use Validator;

class PhotoController extends Controller
{
    public function update(Request $request, $profileCode) {
        $detail = Profile::where('profileCode', $profileCode)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'photoUrl'     => 'required',
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }

            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request, $detail) {
                $uploadFile = $request->file('photoUrl');
                $nameFile = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $extensionFile = $uploadFile->getClientOriginalExtension();
                $resultNameFile1 = $nameFile . "." . $extensionFile;
                $nameFile2 = $nameFile;

                $i = 2;
                while (Storage::disk('local')->exists('user/' . $nameFile . "." . $extensionFile)) {
                    $nameFile = (string) $nameFile2 . $i;
                    $resultNameFile1 = $nameFile . "." . $extensionFile;
                    $i++;
                }

                Storage::putFileAs('user', $request->file('photoUrl'), $resultNameFile1);

                $detail->update([
                    'photoUrl' => $resultNameFile1
                ]);

                $data['profileCode'] = $detail->profileCode;
                return $data;
            }), 200);
        }
    }

    public function download($profileCode) {
        $detail = Profile::where('profileCode', $profileCode)->firstOrFail();
        return Storage::download('user/' . $detail->photoUrl);
    }

    public function delete($profileCode) {
        $detail = Profile::where('profileCode', $profileCode)->firstOrFail();

        Storage::delete('user/' . $detail->photoUrl);

        $detail->update([
            'photoUrl' => null,
        ]);

        return response()->json(['profileCode' => $profileCode], 200);
    }
}
