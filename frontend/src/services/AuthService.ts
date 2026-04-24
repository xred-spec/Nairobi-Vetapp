
import {fetchWithoutVerifying, fetchWithVerifying} from './api2';

 const AuthService = {
    login(payload:any) {
        return fetchWithoutVerifying('auth/login').post(payload).json();
    },
    logout() {
        
    },
    createUserIRL(payload:any){
        return fetchWithVerifying('auth/createUserirl').post(payload).json();
    },
    createUserDigital(payload:any){
        return fetchWithoutVerifying('auth/createUserdigital').post(payload).json();
    },
    createTrabajador(){

    },
    verificarCuenta(token:string){
        return fetchWithoutVerifying('auth/verificarcuenta',{
            headers: {
                'token': token
            }
        }).patch().json();
    },
    generateToken(payload:any){
        return fetchWithoutVerifying('auth/generatetoken').post(payload).json();
    },
    verificartoken(token:string){
        return fetchWithoutVerifying('auth/verificartoken',{
            headers: {
                'token': token
            }
        }).post().json();
    },
    recuperarPassword(payload:any){
        return fetchWithoutVerifying('auth/recuperarpassword').post(payload).json();

    },
    reclamarCuenta(payload:any){
        return fetchWithoutVerifying('auth/reclamarcuenta').post(payload).json();
    },
    setpassword(payload:any,token:string){
        return fetchWithoutVerifying('auth/setpassword',{
            headers:{
                'token': token
            }
        }).patch(payload).json();
    },

    automaticLogin(){
        return fetchWithoutVerifying('auth/user').get().json();
    }

};

export default {AuthService}