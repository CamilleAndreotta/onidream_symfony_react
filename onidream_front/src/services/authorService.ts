import axios from "axios";


interface CreateOrUpdateAuthor {
    firstname: string;
    lastname: string;
    birthDate: string;
    biography: string;
}

const API_URL = "http://localhost:8080/api/authors";

export const createAuthor = async (createAuthor: CreateOrUpdateAuthor) => {
    const token = localStorage.getItem('token');
    try  {
        const response = await axios.post(API_URL,{author: createAuthor}, {
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type" : "application/json"
            }
        });

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

export const updateAuthor = async (updateAuthor: CreateOrUpdateAuthor, authorId: string) => {
    const token = localStorage.getItem('token');

    try{
        const response = await axios.put(API_URL+`/${authorId}`, { author: updateAuthor}, {
            headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type" : "application/json"
                }
        });
    
        return response;
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


export const deleteAuthor = async (authorId: string) => {
    const token = localStorage.getItem('token');
    try{
        const response = await axios.delete(API_URL+`/${authorId}`, {
            headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type" : "application/json"
                }
        });
    
        return response;

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


export const listAuthors = async () => {
    const token = localStorage.getItem('token');
    try {
        const response = await axios.get(API_URL, {
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

export const showAuthor = async (authorId: string) => {
    const token = localStorage.getItem('token');

    try {

        const response = await axios.get(API_URL+`/${authorId}`, {
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