<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Profile;

use Validator;

class ProfileController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'wantedJobTitle'     => 'required',
            'firstName'     => 'required',
            'lastName'     => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
            'country'     => 'required',
            'city'     => 'required',
            'address'     => 'required',
            'postalCode'     => 'required',
            'drivingLicense'     => 'required',
            'nationality'     => 'required',
            'placeOfBirth'     => 'required',
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
            return response()->json(DB::transaction(function () use ($request) {
                $profileCode = mt_rand(10000000, 99999999);
                $datas = $request->all();
                $datas['profileCode'] = $profileCode;
                //echo "<pre>";
                //var_dump($datas);
                //echo "</pre>";
                //die;

                Profile::create($datas);

                $data['profileCode'] = $profileCode;
                return $data;
            }), 200);
        }
    }

    public function update(Request $request, $profileCode) {
        $detail = Profile::where('profileCode', $profileCode)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'wantedJobTitle'     => 'required',
            'firstName'     => 'required',
            'lastName'     => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
            'country'     => 'required',
            'city'     => 'required',
            'address'     => 'required',
            'postalCode'     => 'required',
            'drivingLicense'     => 'required',
            'nationality'     => 'required',
            'placeOfBirth'     => 'required',
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
                $datas = $request->all();
                //echo "<pre>";
                //var_dump($datas);
                //echo "</pre>";
                //die;

                $detail->update($datas);

                $data['profileCode'] = $detail->profileCode;
                return $data;
            }), 200);
        }
    }

    public function show($profileCode) {
        $data = Profile::where('profileCode', $profileCode)->firstOrFail();

        return response()->json($data, 200);
    }
}
