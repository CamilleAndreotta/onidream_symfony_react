import axios from "axios";

interface UpdateUser {
    firstname: string, 
    lastname: string, 
    password: string,
}

export const showUser = async () => {
    const API_URL = "http://localhost:8080/api/user";
    const token = localStorage.getItem('token');

    try {

        const response = await axios.get(API_URL, {
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

export const updateUser = async (updateUser: UpdateUser, userId: string) => {
    const API_URL = "http://localhost:8080/api/users";
    const token = localStorage.getItem('token');

    try{
        const response = await axios.put(API_URL+`/${userId}`, {user: updateUser}, {
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