<?php

namespace App;

use App\Models\User;

class MailTemplate
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function EmailVerification(String $BaseUrL,User $user){
       return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body style="font-family: Roboto, sans-serif; padding: 0; margin: 0;">
    <h1 style="text-align: center; font-size: 30px; color: rgb(53, 29, 0);">
        Te damos la Bienvenida  $user->nombre a nairobi veterinaria, para confirmar
        tu registro es necesario que le des click al boton que se encuentra debajo de este mensaje.
    </h1>
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
        <tr>    
          
            <td align="center">
                <a href= "$BaseUrL"
                   style="text-decoration: none; color: white; background-color: rgb(53, 29, 0); padding: 10px 20px; border-radius: 5px; display: inline-block;">
                    Confirmar Cuenta
                </a>
            </td>
        </tr>
        <tr>
            <td style="height: 1px; background-color: rgba(53, 29, 0, 0.664); margin: 10px 0;"></td>
        </tr>
        <tr>
            <td align="center" style="padding-top: 10px;">
                <p>Si no has solicitado este correo, puedes ignorarlo</p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }


    public static function ReclamarCuenta(String $BaseUrL,User $user){
         return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body style="font-family: Roboto, sans-serif; padding: 0; margin: 0;">
    <h1 style="text-align: center; font-size: 30px; color: rgb(53, 29, 0);">
        Te damos la Bienvenida  $user->nombre a nairobi veterinaria, para reclamar
        tu cuenta es necesario que le des click al boton que se encuentra debajo de este mensaje.
    </h1>
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
        <tr>
          
            <td align="center">
                <a href= "$BaseUrL"
                   style="text-decoration: none; color: white; background-color: rgb(53, 29, 0); padding: 10px 20px; border-radius: 5px; display: inline-block;">
                    Reclamar Cuenta
                </a>
            </td>
        </tr>
        <tr>
            <td style="height: 1px; background-color: rgba(53, 29, 0, 0.664); margin: 10px 0;"></td>
        </tr>
        <tr>
            <td align="center" style="padding-top: 10px;">
                <p>Si no has solicitado este correo, puedes ignorarlo</p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    public static function PasswordReset(String $BaseUrL,User $user){
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reestablecer Contraseña</title>
</head>
<body style="font-family: Roboto, sans-serif; padding: 0; margin: 0;">
    <h1 style="text-align: center; font-size: 30px; color: rgb(53, 29, 0);">
        Hola  $user->nombre, este correo es para reestablecer tu contraseña
        para esto, es necesario que le des click al boton que se encuentra debajo de este mensaje.
    </h1>
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
        <tr>
          
            <td align="center">
                <a href= "$BaseUrL"
                   style="text-decoration: none; color: white; background-color: rgb(53, 29, 0); padding: 10px 20px; border-radius: 5px; display: inline-block;">
                    Reestablecer Contraseña
                </a>
            </td>
        </tr>
        <tr>
            <td style="height: 1px; background-color: rgba(53, 29, 0, 0.664); margin: 10px 0;"></td>
        </tr>
        <tr>
            <td align="center" style="padding-top: 10px;">
                <p>Si no has solicitado este correo, puedes ignorarlo</p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    public static function EmailConfirmation(){
        return <<<HTML

        HTML;
    }
}
