<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Carbon\Carbon;
use App\Models\Link;
use App\Models\ResetPassword;
use App\Models\Verification;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Mail;



class AuthController extends BaseController
{

    protected $user;

    public function __construct(){
        $this->middleware("auth:api",["except" => ["login","register","getUserData", "forgot", "reset", "verify"]]);
        $this->user = new User;
    }

    public function forgot(Request $request) {
        $hash = bin2hex(random_bytes(64));
        $link = url('').'/reset/'.$hash;
        \Mail::to($request->email)->send(new \App\Mail\ResetPasswordEmail($link));
        $user = User::where('email', $request->email)->get()->first();
        if (!$user) {
            $responseMessage = 'Email Not Found '.$request->email;
            return $this->sendResponse($success, $responseMessage);
        }

        $reset_user = ResetPassword::where('email', $request->email)->get()->first();

        if($reset_user) {
            ResetPassword::where('email',$request->email)->update(['token'=> $hash]);
            $reset = ResetPassword::where('email', $request->email)->get()->first();
        } else {
            $reset = new ResetPassword([
                'email' => $request->email,
                'token' => $hash
            ]);
            $reset->save();
        }

        $success['reset'] = $reset;
        $responseMessage = 'Reset Password Link sent '.$request->email;
        return $this->sendResponse($success, $responseMessage);
    }

    public function reset(Request $req) {
        $reset = ResetPassword::where('token', $req->token)->get()->last();
        $user = User::where('email', $reset->email)->get()->first();
        $user->password = Hash::make($req->password);
        $user->save();
        $responseMessage = 'Password Changed';
        return $this->sendResponse('', $responseMessage);
    }

    public function login(Request $request){
        $credentials = $request->only(["email","password"]);
        $user = User::where('email',$credentials['email'])->first();

        if($user->email_verified_at==null){
            $responseMessage = "email not verified";
            return $this->sendError($responseMessage,[],401);
        }

        if($user){
            if(!auth()->attempt($credentials)){
                $responseMessage = "Invalid username or password";
                return response()->json([
                    "success" => false,
                    "message" => $responseMessage,
                    "error" => $responseMessage
                ], 422);
            }

            $success['accessToken'] = auth()->user()->createToken('authToken')->plainTextToken;
            $success['user'] = auth()->user();
            $responseMessage = "Login Successful";

            return $this->sendResponse($success, $responseMessage);
        }
        else{
            $responseMessage = "Sorry, this user does not exist";
            return response()->json([
                "success" => false,
                "message" => $responseMessage,
                "error" => $responseMessage
            ], 422);
        }
    }

    public function register(Request $req) {
        $user = User::where('email',$req->email)->first();
        if ($user) {
            $responseMessage = "Email already exists";
            return $this->sendError($responseMessage,[],422);
        }
        $user = User::where('name',$req->name)->first();
        if ($user) {
            $responseMessage = "Username already exists";
            return $this->sendError($responseMessage,[],422);
        }

        $hash = Hash::make($req->password);
        $hashh = bin2hex(random_bytes(64));
        $link = url('').'/verify/'.$hashh;

        $user = new User([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $hash,
            'hash' => $hashh,
        ]);
        $user->save();
        \Mail::to($req->email)->send(new \App\Mail\VerificationEmail($req->username, $link));
        $credentials = $req->only(["email","password"]);
        auth()->attempt($credentials);
        $user = Auth::user();
        // $team = $this->createTeam($user);

        $success['accessToken'] = auth()->user()->createToken('authToken')->plainTextToken;
        $success['user'] = $user;
        // $success['team'] = $team;
        $responseMessage = "Register Successful!";
        return $this->sendResponse($success, $responseMessage);
    }

    private function createTeam(User $user) {

        $team = new Team([
            'user_id' => $user->id,
            'name' => explode(' ', $user->username, 2)[0]."'s Team",
            'personal_team' => true,
        ]);
        $team->save();
        return $team;
    }

    public function verify(Request $req) {
        $user = User::where('hash', $req->hash)->first();
        if ($user) {
            $user->email_verified_at = Carbon::now();
            $user->save();
            $success['user'] = $user;
            $responseMessage = "Verification Successful!";
            return $this->sendResponse($success, $responseMessage);

        } else {
            $responseMessage = "Failed Verification, Send again!";
            return $this->sendError($responseMessage,500);
        }
    }

    public function logout(){
        $user = Auth::guard("api")->user()->token();
        $user->revoke();
        $responseMessage = "successfully logged out";
        return $this->sendResponse($success, $responseMessage);
    }
}
