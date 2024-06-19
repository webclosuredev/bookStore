<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Controllers\CustomerController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Mail\LaravelTenTestMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 400);       
        }

        $input = $request->all();
        
        $user = User::where('email', $request->email)->get();
        if(count($user) > 0 )
            return $this->sendError('User with email ' . $request->email . ' exist', 400);    
       
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        Auth::login($user);
        // $this->registerEmail($request->email);
        return $user;
    }
    

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        $input = $request->only('email', 'password');
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function registerEmail($email): JsonResponse {
        $data = [
            'data' => 'https://kendydrink.com/shop?subscribe=1',
            'subject' => 'Registrazione',
            'view' => 'register'
        ];
        Mail::to($email)->send(
            new LaravelTenTestMail($data)
        );
        return $this->sendResponse(200, 'OTP send successfully.');
    }
}