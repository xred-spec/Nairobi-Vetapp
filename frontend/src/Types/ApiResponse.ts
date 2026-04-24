export default interface ApiResponse<T = any>{
    data: T,
    message: string | null,
    error: string | null,
    code?: number
} 