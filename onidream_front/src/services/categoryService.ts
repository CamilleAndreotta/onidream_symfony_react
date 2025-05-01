import axios from "axios";

interface CreateOrUpdateCategory {
    name: string;
}

const API_URL = "http://localhost:8080/api/categories";

export const createCategory = async (createCategory: CreateOrUpdateCategory) => {
    const token = localStorage.getItem('token');
    try  {
        const response = await axios.post(API_URL,{
            category: createCategory
            }, {
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

export const updateCategory = async (updateCategory: CreateOrUpdateCategory, categoryId: string) => {
    const token = localStorage.getItem('token');

    try{
        const response = await axios.put(API_URL+`/${categoryId}`, {category: updateCategory}, {
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

export const deleteCategory = async (categoryId: string) => {
    const token = localStorage.getItem('token');
    try{
        const response = await axios.delete(API_URL+`/${categoryId}`, {
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

export const listCategories = async () => {
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

export const showCategory = async (categoryId: string) => {
    const token = localStorage.getItem('token');

    try {

        const response = await axios.get(API_URL+`/${categoryId}`, {
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