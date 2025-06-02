import axios from "axios";


interface CreateOrUpdateEditor {
    id?: string,
    name: string,
}

const API_URL = "http://localhost:8080/api/editors";

export const createEditor = async (createEditor: CreateOrUpdateEditor) => {
    const token = localStorage.getItem('token');
    try  {
        const response = await axios.post(API_URL,{editor: createEditor}, {
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

export const updateEditor = async (updateEditor: CreateOrUpdateEditor, editorId: string) => {
    const token = localStorage.getItem('token');

    try{
        const response = await axios.put(API_URL+`/${editorId}`, {editor: updateEditor}, {
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


export const deleteEditor = async (editorId: string) => {
    const token = localStorage.getItem('token');
    try{
        const response = await axios.delete(API_URL+`/${editorId}`, {
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

export const listEditors = async () => {
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

export const showEditor = async (editorId: string) => {
    const token = localStorage.getItem('token');
    try{
        const response = await axios.get(API_URL+`/${editorId}`, {
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