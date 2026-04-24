import {defineStore } from 'pinia';

export const token = defineStore('token', () => {
    const getToken = () => localStorage.getItem('token');
    const setToken = (token: string) => localStorage.setItem('token', token);
    const removeToken = ()=> localStorage.removeItem('token');
    return { getToken, setToken,removeToken };
})

