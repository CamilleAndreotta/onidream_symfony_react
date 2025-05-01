import { createContext, ReactNode, useContext, useEffect, useState } from "react";
import { listExcerpts } from "../services/excerptService";
import { useAuth } from "./AuthContext";
import { ExcerptType } from "../@types/Excerpt";


interface ExcerptContextType {
    excerpts: ExcerptType[],
    setExcerpts: React.Dispatch<React.SetStateAction<ExcerptType[]>>,
    fetchExcerpts: () => Promise<void>,
    error: string|null,
    loading: boolean
}

const ExcerptContext = createContext<ExcerptContextType | undefined >(undefined); 

interface ExcerptProviderProps {
    children?: ReactNode;
}


export const ExcerptProvider: React.FC<ExcerptProviderProps> = ({children} = {}) => {
    const [excerpts, setExcerpts] = useState<ExcerptType[]>([]);
    const [error, setError] = useState<string | null > (null);
    const {isAuthenticated} = useAuth();
    const [loading, setLoading] = useState(true);

    const fetchExcerpts = async() => {

        setLoading(true);
        const loadingTimeout = setTimeout(() => {
                setLoading(true);
        }, 500);

        try {
            const excerptsFromApi = await listExcerpts();
            setExcerpts(excerptsFromApi);
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
            fetchExcerpts();
        } else {
            setExcerpts([]);
        }
    }, [isAuthenticated]);
    
    return (
        <ExcerptContext.Provider value = {{excerpts, setExcerpts, fetchExcerpts, error, loading}}>
            {children}
        </ExcerptContext.Provider>
    )

}

export const useExcerpts = (): ExcerptContextType => {
    const context = useContext(ExcerptContext);
    if(!context) {
        throw new Error ("useExcerpt doit être utilisé avec le provider EditiorProvider");
    }

    return context;
}

