import axios from "axios";

interface CreateOrUpdateBook {
    book: Book;
    editor: Editor;
    authors: Author[]
}

interface Book {
    id?: string;
    name: string;
    summary: string;
    isbn: string;
    publishedAt: string;
}

interface Editor {
    id: string;
}

interface Author {
    id: string;
}

const API_URL = "http://localhost:8080/api/books";

export const createBook = async (createBook: CreateOrUpdateBook) => {
    const token = localStorage.getItem('token');
    try  {
        const response = await axios.post(API_URL,{
            book: createBook.book, 
            editor: createBook.editor,
            authors: createBook.authors
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

export const updateBook = async (updateBook: CreateOrUpdateBook, bookId: string) => {
    const token = localStorage.getItem('token');

    try{
        const response = await axios.put(API_URL+`/${bookId}`, {
            book: updateBook.book,
            editor: updateBook.editor,
            authors: updateBook.authors
            }, {
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

export const deleteBook = async (bookId: string) => {
    const token = localStorage.getItem('token');
    try{
        const response = await axios.delete(API_URL+`/${bookId}`, {
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


export const listBooks = async () => {
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

export const showBook = async (bookId: string) => {
    const token = localStorage.getItem('token');

    try {

        const response = await axios.get(API_URL+`/${bookId}`, {
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