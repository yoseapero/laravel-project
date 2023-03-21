<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
	public function register(Request $request)
	{
		$inputRequest = $request->input();
		$validator = Validator::make($inputRequest, [
			'email'     => 'unique:users,email|required|string',
			'name'      => 'required|string',
			'password'  => 'required|string',
		]);

		if ($validator->fails()) {
			$error                = $validator->messages()->first();
			$response['status']   = false;
			$response['message']  = $error;
			return response()->json($response, 400);
		}

		$userRepo   = new UserRepository();
		$response   = $userRepo->createAndLogin($inputRequest);
		$statusCode = $response['code'];
		unset($response['code']);

		return response()->json($response, $statusCode);
	}

	public function login(Request $request)
	{
		$input_request = $request->input();
		$validator = Validator::make($input_request, [
			'email'     => 'required|exists:users,email',
			'password'  => 'required',
		]);

		if ($validator->fails()) {
			$error = $validator->messages()->first();
			$response['status'] = false;
			$response['message'] = $error;
			return response()->json($response, 400);
		}

		$data = [
			'email'     => $input_request['email'],
			'password'  => $input_request['password']
		];

		if (auth()->attempt($data)) {
			$user       = Auth::user();
			$objToken   = auth()->user()->createToken('LaravelAuthApp');
			$strToken   = $objToken->accessToken;
			$expiration = $objToken->token->expires_at->diffInSeconds(Carbon::now());

			return response()->json([
				'token'       => $strToken,
				'expires_in'  => $expiration,
				'data'        => $user
			], 200);
		} else {

			return response()->json([
				'error' => 'Email atau Password alah',
				'status' => false
			], 401);
		}
	}

	public function profile(Request $request)
	{
		$user       = Auth::user();
		return response()->json($user, 200);
	}

	public function put(Request $request)
	{
		$user     = Auth::user();
		$input    = $request->all();

		$validator =  Validator::make($input, [
			'name' => 'string|required',
			'email' => 'unique:users,email,' . $user->id . ',id|string|required',
			'password' => 'sometimes|required|string'
		]);

		if ($validator->fails()) {
			$error = $validator->messages()->first();
			$response['status']   = false;
			$response['message']  = $error;
			return response()->json($response, 400);
		}

		$userRepo   = new UserRepository();
		$response   = $userRepo->update($input);

		$statusCode = $response['code'];
		unset($response['code']);

		if ($response['status'] === true) {
			return response()->json($response, $statusCode);
		}

		return response()->json($response, $statusCode);
	}

	public function patch(Request $request)
	{
		$user     = Auth::user();
		$input    = $request->all();

		$validator =  Validator::make($input, [
			'name' => 'sometimes|string|required',
			'email' => 'sometimes|unique:users,email,' . $user->id . ',id|string|required',
			'password' => 'sometimes|required|string'
		]);

		if ($validator->fails()) {
			$error = $validator->messages()->first();
			$response['status']   = false;
			$response['message']  = $error;
			return response()->json($response, 400);
		}

		$userRepo   = new UserRepository();
		$response   = $userRepo->update($input);

		$statusCode = $response['code'];
		unset($response['code']);

		if ($response['status'] === true) {
			return response()->json($response, $statusCode);
		}

		return response()->json($response, $statusCode);
	}

	public function delete(Request $request)
	{
		$user     = Auth::user();
		$userRepo   = new UserRepository();
		$response   = $userRepo->delete($user->id);

		$statusCode = $response['code'];
		unset($response['code']);

		if ($response['status'] === true) {
			return response()->json($response, $statusCode);
		}

		return response()->json($response, $statusCode);
	}

	public function logout(Request $request)
	{
		$request->user()->token()->revoke();
		return response()->json([
			'message' => 'Successfully logged out'
		], 200);
	}
}
