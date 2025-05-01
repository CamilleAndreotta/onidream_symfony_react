import { createContext, ReactNode, useContext, useEffect, useState } from "react";
import { listAuthors } from "../services/authorService";
import { useAuth } from "./AuthContext";
import { AuthorType } from "../@types/Author";

interface AuthorContextType {
    authors: AuthorType[];
    setAuthors: (authors: AuthorType[]) => void;
    fetchAuthors: () => Promise<void>;
    error: string|null;
    loading: boolean;
}

const AuthorContext = createContext<AuthorContextType | undefined >(undefined); 

interface AuthorProviderProps {
    children?: ReactNode;
}

export const AuthorProvider: React.FC<AuthorProviderProps> = ({children} = {}) => {
    const [authors, setAuthors] = useState<AuthorType[]>([]);
    const [error, setError] = useState<string | null > (null);
    const {isAuthenticated} = useAuth();
    const [loading, setLoading] = useState(true);

    const fetchAuthors = async() => {

        setLoading(true);
        const loadingTimeout = setTimeout(() => {
                setLoading(true);
        }, 500);

        try {
            const authorsFromApi = await listAuthors();
            setAuthors(authorsFromApi);
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
            fetchAuthors();
        } else {
            setAuthors([]);
        }
    }, [isAuthenticated]);
    
    return (
        <AuthorContext.Provider value = {{authors, setAuthors, fetchAuthors, error, loading}}>
            {children}
        </AuthorContext.Provider>
    )

}

export const useAuthors = (): AuthorContextType => {
    const context = useContext(AuthorContext);
    if(!context) {
        throw new Error ("useAuthors doit être utilisé avec le provider AuthorProvider");
    }

    return context;
}

