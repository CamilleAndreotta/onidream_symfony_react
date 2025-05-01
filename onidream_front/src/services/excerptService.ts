import axios from "axios";

interface CreateOrUpdateExcerpt{
    excerpt: Excerpt,
    book: Book,
    categories: Category[]
}

interface Excerpt {
    id?: string;
    text: string;
    bookStartedOn: string;
    bookEndedOn:string;
    bookPage: string;
}

interface Book {
    id: string
}

interface Category {
    id: string;
}


const API_URL = "http://localhost:8080/api/excerpts";

export const createExcerpt = async (createExcerpt: CreateOrUpdateExcerpt) => {
    const token = localStorage.getItem('token');
    try {
        const response = await axios.post(API_URL, createExcerpt, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type" : "application/json"
            } 
        })

        return response;

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

export const updateExcerpt = async (updateExcerpt: CreateOrUpdateExcerpt, excerptId: string) => {
    const token = localStorage.getItem('token');
    try {
        const response = await axios.put(API_URL+`/${excerptId}`, updateExcerpt, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type" : "application/json"
            } 
        })

        return response;

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


export const deleteExcerpt = async (excerptId: string) => {
    const token = localStorage.getItem('token');
    try {
        const response = await axios.delete(API_URL+`/${excerptId}`, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type" : "application/json"
            } 
        })

        return response;

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


export const listExcerpts = async () => {
    const token = localStorage.getItem('token');
    try {
        const response = await axios.get(API_URL+'/user', {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type" : "application/json"
            } 
        });

        return response.data;

    } catch (error: any ){
        if (error.response) {
            throw new Error(error.response.data.message || "Erreur lors de la connexion.");
        }
    }
    
}

export const showExcerpt = async (excerptId: string) => {
    const token = localStorage.getItem('token');
    try{
        const response = await axios.get(API_URL+`/${excerptId}`, {
        headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type" : "application/json"
            }
        })

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
}