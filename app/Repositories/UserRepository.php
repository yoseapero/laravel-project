<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserRepository
{

    public function createAndLogin($input)
    {
        try {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
            ]);

            if ($user->save()) {
                $result['status']   = true;
                $result['code']     = 201;
                $result['message']  = "Berhasil Membuat User baru";

                $authUser = [
                    'email' => $input['email'],
                    'password' => $input['password'],
                ];

                if (auth()->attempt($authUser)) {
                    $user       = Auth::user();
                    $objToken   = auth()->user()->createToken('LaravelAuthApp');
                    $strToken   = $objToken->accessToken;
                    $expiration = $objToken->token->expires_at->diffInSeconds(Carbon::now());

                    $result = array_merge($result, [
                      'token'       => $strToken,
                      'expires_in'  => $expiration
                    ]);
                }

                return $result;
            } else {
                $result['status']   = false;
                $result['code']     = 500;
                $result['message']  = "Gagal Membuat User baru";
                $result['user']     = (object)[];
                return $result;
            }
        } catch (\Exception $e) {
            $result['status']       = false;
            $result['code']         = 500;
            $result['message']      = "Gagal Update User";
            $result['user']         = (object)[];
            $result['error'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line_of_code' => $e->getLine(),
                'code' => $e->getCode(),
            ];
            return $result;
        }
    }

    public function update($input = [])
    {
        $user = Auth::user();

        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        try {
            if ($user->update($input)) {
                $result['code']       = 200;
                $result['status']     = true;
                $result['message']    = "Berhasil Update user";
                $result['user']       = $user;
            }

            return $result;
        } catch (\Exception $e) {
            $result['status']       = false;
            $result['code']         = 500;
            $result['message']      = "Gagal Update User";
            $result['user']         = (object)[];
            $result['error'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line_of_code' => $e->getLine(),
                'code' => $e->getCode(),
            ];
            return $result;
        }
    }

    public function delete($id)
    {
        $user = User::id($id);

        try {
            if ($user->delete($id)) {
                $result['code']       = 200;
                $result['status']     = true;
                $result['message']    = "Berhasil Delete user";
                $result['user']       = $user;
            }

            return $result;
        } catch (\Exception $e) {
            $result['status']       = false;
            $result['code']         = 500;
            $result['message']      = "Gagal Delete User";
            $result['user']         = (object)[];
            $result['error'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line_of_code' => $e->getLine(),
                'code' => $e->getCode(),
            ];
            return $result;
        }
    }
}