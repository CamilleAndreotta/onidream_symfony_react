import { createContext, ReactNode, useContext, useEffect, useState } from "react";
import { listBooks } from "../services/bookService";
import { useAuth } from "./AuthContext";
import { BookType } from "../@types/Book";

interface BookContextType {
    books: BookType[],
    setBooks: (books: BookType[]) => void,
    fetchBooks: () => Promise<void>,
    error: string|null,
    loading: boolean,
}

const BookContext = createContext<BookContextType | undefined >(undefined); 

interface BookProviderProps {
    children?: ReactNode;
}

export const BookProvider: React.FC<BookProviderProps> = ({children} = {}) => {
    const [books, setBooks] = useState<BookType[]>([]);
    const [error, setError] = useState<string | null > (null);
    const {isAuthenticated} = useAuth();
    const [loading, setLoading] = useState(true);

    const fetchBooks = async() => {

        setLoading(true);
        const loadingTimeout = setTimeout(() => {
                setLoading(true);
        }, 500);

        try {
            const booksFromApi = await listBooks();
            setBooks(booksFromApi);
            setError(null);
        } catch (error: any){
            setError(error.message);
        } finally {
            clearTimeout(loadingTimeout); 
            setLoading(false);
        }
    };

    useEffect(() => {
        const user = localStorage.getItem("user");
        if(user){
            fetchBooks();
        } else {
            setBooks([]);
        }
    }, [isAuthenticated]);
    
    return (
        <BookContext.Provider value = {{books, setBooks, fetchBooks, error, loading}}>
            {children}
        </BookContext.Provider>
    )

}

export const useBooks = (): BookContextType => {
    const context = useContext(BookContext);
    if(!context) {
        throw new Error ("useBooks doit être utilisé avec le provider BookProvider");
    }

    return context;
}

