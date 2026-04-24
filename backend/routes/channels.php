<?php

use Illuminate\Support\Facades\Broadcast;
/*
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
*/
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('App.Appointment.User.{id}',function($user,$id){
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Appointment.Admin.{id}',function($user,$id){
    return(int)$user->id === (int) $id;
});
