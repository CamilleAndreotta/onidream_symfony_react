import axios from "axios";

export interface UserCredentials {
    email: string,
    password: string,
}

export const login = async (credentials: UserCredentials) => {
    const API_URL = "http://localhost:8080/api/login";

    try {
        const response = await axios.post(API_URL, {
            user: credentials,
        });

        localStorage.removeItem("token");
        localStorage.setItem("token", response.data.token);

        return response.data;

    } catch (error: any) {
        if (error.response) {
            throw new Error(error.response.data.message || "Erreur lors de la connexion.");
        } else if (error.request) {
            throw new Error("Pas de réponse du serveur.");
        } else {
            throw new Error("Erreur inattendue.");
        }
    }
};

export interface CreateUser {
    firstname: string,
    lastname: string,
    email: string,
    password: string,
}

export const registerUser = async (createUser: CreateUser) => {
    const API_URL = "http://localhost:8080/api/register";
    try {
        const response = await axios.post(API_URL, {
            user: createUser,
        })

        localStorage.setItem("token", response.data.token);

        return response.data;
    } catch (error: any){
        if (error.response) {
            throw new Error(error.response.data.message || "Erreur lors de la connexion.");
        } else if (error.request) {
            throw new Error("Pas de réponse du serveur.");
        } else {
            throw new Error("Erreur inattendue.");
        }
    }
}

export const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem('user');
};

export function getToken() {
    return localStorage.getItem("token");
}