import Service from "@/services/AuthService";

export default function useAuth(){
    const login = async (payload:any) => {
        return await Service.AuthService.login(payload);
    }
    const automaticLogin = async()=>{
        return await Service.AuthService.automaticLogin();
    }
    const logout = async () => {
        return await Service.AuthService.logout;
    }
    const createUserIRL = async(payload:any)=>{
        return await Service.AuthService.createUserIRL(payload);
    }
    const createUserDigital = async(payload:any)=>{
        return await Service.AuthService.createUserDigital(payload);
    }
    const verificarCuenta = async(token:string)=>{
        return await Service.AuthService.verificarCuenta(token);
    }
    const generatetoken = async(payload:any)=>{
        return await Service.AuthService.generateToken(payload);
    }
    const reclamarCuenta = async(payload:any)=>{
        return await Service.AuthService.reclamarCuenta(payload);
    }
    const verificartoken = async(token:string) =>{
        return await Service.AuthService.verificartoken(token)
    }
    const setpassword = async(payload:any,token:string) =>{
        return await Service.AuthService.setpassword(payload,token);
    }
    const RecuperarPassword = async(payload:any) =>{
        return await Service.AuthService.recuperarPassword(payload);
    }
    return {login,logout,createUserIRL,createUserDigital,verificarCuenta,generatetoken,reclamarCuenta,verificartoken,setpassword,RecuperarPassword,automaticLogin};
}