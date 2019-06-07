<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use Auth;
use App\User;

use App\Http\Requests\Api\V1\Account\UpdateAccount;
use App\Http\Requests\Api\V1\Account\UpdateFCMToken;

class AccountController extends Controller
{
    /**
     * Display the account.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return User::find($request->user()->id);
    }

    /**
     * Update the specified account.
     *
     * @param  \App\Http\Requests\Api\V1\Account\UpdateAccount  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccount $request)
    {
        \DB::beginTransaction();
		try{
            $user = $request->user();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => trim($request->password) !== '' ? Hash::make($request->password) : Auth::user()->password
            ]);
            \DB::commit();
            return $user;
        }
        catch(\Exception $e){
            \DB::rollback();
			return response()->json([
				'errors' => $e->getMessage(),
            ], in_array($e->getCode(), config('app.common_http_errors'))? $e->getCode() : 500);
        }
    }

    /**
     * Save device FCM token the specified account.
     *
     * @param  \App\Http\Requests\Api\V1\Account\UpdateFCMToken  $request
     * @return \Illuminate\Http\Response
     */
    public function saveToken(UpdateFCMToken $request)
    {
        \DB::beginTransaction();
		try{
            $data = $request->user();
            // $data->fcm()->delete();
            $token = $data->fcm()->updateOrCreate([
                'device_type' => $request->device_type,
            ], [
                'token' => $request->token
            ]);
            \DB::commit();
            return response()->json($token);
        }
        catch(\Exception $e){
            \DB::rollback();
			return response()->json([
				'errors' => $e->getMessage(),
            ], in_array($e->getCode(), config('app.common_http_errors'))? $e->getCode() : 500);
        }
    }
}
