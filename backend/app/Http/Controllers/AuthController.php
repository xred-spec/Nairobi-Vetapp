<?php

namespace App\Http\Controllers;

use App\Events\PasswordSetter;
use App\Events\UserRegister;
use App\Events\ReclamarCuenta;
use App\Exceptions\TokenException;
use App\Http\Requests\CreateClienteRequest;
use App\Http\Requests\CreateTrabajadorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{
    use ApiResponse;

    public function user(Request $request){
        $user = $request->user();
        return $this->Respuesta($user->load('rol'),'login correcto',200,null);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->Respuesta(null, 'Sesión cerrada correctamente', 200);
    }

    public function login(LoginRequest $request)
    {
        $user = User::with('rol')->where('email','=',$request->email)->orwhere('telefono','=',$request->telefono)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return $this->Respuesta(null,'el usuario o la contraseña son incorrectos',404,'not user found');
        }
        if(!$user->email_verified_at){
            return $this->Respuesta(null,'El correo no ha sido verificado',400,'user not verified');
        }
        $token = $user->createToken('token')->plainTextToken;
        return $this->Respuesta([
            'token'=>$token,
            'user'=>$user
        ],'el usuario fue encontrado',200,null);
    }

    public function CreateUserIRL(CreateClienteRequest $request)
    {
        
        try{
                $data = DB::transaction(function () use ($request) {
            $user = User::create([
                'nombre'=>$request->nombre,
                'telefono'=>$request->telefono,
                'email'=>$request->email,
                'rol_id'=>'1',
                'estado'=>'pendiente',
            ]);   
        $user->cliente()->create([
                'municipio'=>$request->municipio,
                'colonia'=>$request->colonia,
                'codigo_postal'=>$request->codigo_postal,
                'calle'=>$request->calle,
                'numero_exterior'=>$request->numero_exterior,
            ]); 
        return [
             'usuario'=>$user,
             'cliente'=>$user->cliente
            ];
        });
        return $this->Respuesta(null,'usuario creado',200,null);
        }catch(\Throwable $th){
            $this->Respuesta(null,'Ocurrio un error',400,$th->getMessage());
        }
    }

    public function CreateUserDigital(CreateClienteRequest $request)
    {
        try{
            $request->validate(['password'=>'required|min:8'],['password.required'=>'La contraseña es obligatoria','password.min'=>'la contraseña debe de tener al menos 8 caracteres']);
            $data = DB::transaction(function() use ($request){
            $user = User::create([
                'nombre'=>$request->nombre,
                'telefono'=>$request->telefono,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'rol_id'=>'1',
                'estado'=>'pendiente',
            ]);   
            $user->cliente()->create([
                'municipio'=>$request->municipio,
                'colonia'=>$request->colonia,
                'codigo_postal'=>$request->codigo_postal,
                'calle'=>$request->calle,
                'numero_exterior'=>$request->numero_exterior,
            ]);
            return [
                'usuario'=>$user,
                'cliente'=>$user->cliente
            ];
            });
            event(new UserRegister($data['usuario']));
            return $this->Respuesta([
                'usuario'=>$data['usuario'],
            ],'usuario creado',200,null);
        }catch(\Throwable $th){
            return $this->Respuesta(null,'Ocurrio un error',400,$th->getMessage());
        }
        
    }

    /*public function CreateTrabajador(CreateTrabajadorRequest $request)
    {
       $data = DB::transaction(function() use ($request){
         $user = User::create([
        'nombre'=>$request->nombre,
        'telefono'=>$request->telefono,
        'email'=>$request->email,
        'rol_id'=>'2',
        'estado'=>'pendiente',
        ]);
        $trabajador = $user->trabajador()->create();  
        $trabajador->horarios()->attach($request->horarios);
        return[
        'usuario'=>$user
        ];
        });
    
        return $this->Respuesta([
        'usuario' => $data['usuario']->load('trabajador.horarios')
        ]); 
    }*/

    public function CreateTrabajador(CreateTrabajadorRequest $request)
    {
        $data = DB::transaction(function() use ($request) {
        $user = User::create([
            'nombre'   => $request->nombre,
            'telefono' => $request->telefono,
            'email'    => $request->email,
            'rol_id'   => '2',
            'estado'   => 'pendiente',
        ]);

        $trabajador = $user->trabajador()->create();

        if (!empty($request->horarios)) {
            $trabajador->horarios()->attach($request->horarios);
        }

        return [
            'usuario' => $user
        ];
    });

    return $this->Respuesta([
        'usuario' => $data['usuario']->load('trabajador.horarios')
    ]);
}
    
    public function VerificarCuenta(Request $request){
        $token = Token::with('user')->where('token','=',hash('sha256',$request->header('token')))->first();
        $this->globalTokenHandler($token);
        $token->user->email_verified_at = now();
        $token->user->estado = 'registrado';
        $token->update(['state'=>'used']);
        $token->user->save();
        return $this->Respuesta(null,'El correo ha sido verificado',200,null);
    }

    public function GenerateToken(Request $request){
        $user = User::where('email','=',$request->email)->where('telefono','=',$request->telefono)->first();
        if(!$user || $user->email_verified_at){
            return $this->Respuesta(null,'error al generar el token',400,'user exists or email verified');
        }
        if($user->password){
            event(new UserRegister($user));
            return $this->Respuesta(null,'se te ha enviado un correo de verificacion, revisa la bandeja de spam',200,null);
        }else{
            event(new ReclamarCuenta($user));
            return $this->Respuesta(null,'se te ha enviado un correo para reclamar tu cuenta, revisa la bandeja de spam',200,null);
        }
        
       
    }
    
    public function globalTokenHandler($token, $bypass = false){


        if(!$token || $token->state != 'process'){
         throw new TokenException('El token no es valido');
        }
        if($token->expired_at < now()){
            $token->update(['state'=>'expired']);
            throw new TokenException('el token ha expirado');

        }
        if($token->type == 'reclamar'){
            $bypass = false;
        }
        if($token->type == 'recuperar'){
            $bypass = true;
        }

        if($token->user->email_verified_at && !$bypass ){
            $token->update(['state'=>'used']);
             throw new TokenException('El correo ya ha sido verificado');
        }

        if($bypass && !$token->user->email_verified_at){
            throw new TokenException('El correo no ha sido verificado');
        }
    }

    public function ValidarToken(Request $request){
        $token = Token::with('user')->where('token','=',hash('sha256',$request->header('token')))->first();
        $this->globalTokenHandler($token);
        return $this->Respuesta(null,'el token es valido',200,null);
    }

    public function RecuperarPassword(Request $request){
        $user = User::where('email','=',$request->email)->where('telefono','=',$request->telefono)->first();
        if(!$user || !$user->email_verified_at){
            return $this->Respuesta(null,'error al generar el token',400,'user not found or not verified');
        }
     event(new PasswordSetter($user));
        return $this->Respuesta(null,'el correo fue enviado',200,null);
    }

    public function ReclamarCuenta(Request $request){
        $user = User::where('email','=',$request->email)->where('telefono','=',$request->telefono)->first();

        if(!$user){
            return $this->Respuesta(null,'error al generar el token',400,'user not found');
        }
       if($user->email_verified_at){
            return $this->Respuesta(null,'El correo ya ha sido verificado',400,null);
        }
        event(new ReclamarCuenta($user));
        return $this->Respuesta(null,'el correo fue enviado',200,null);
    }

    public function SetContraseña(Request $request){
        $request->validate([
            'password'=>['required','min:8']
        ],['password.required'=>'la contraseña es obligatoria','password.min'=>'la contraseña debe de tener minimo 8 caracteres']);
        $token = Token::with('user')->where('token','=',hash('sha256',$request->header('token')))->first();
        $this->globalTokenHandler($token,$bypass=true);
        $token->update(['state'=>'used']);
        if($token->type == 'reclamar'){
            $token->user->email_verified_at = now();
            $token->user->estado = 'registrado';
        }
        $token->user->password = Hash::make($request->password);
        $token->user->save();
        return $this->Respuesta(null,'la contraseña ha sido agregada',200,null);
    }

}
