import { createContext, ReactNode, useContext, useEffect, useState } from "react";
import { listCategories } from "../services/categoryService";
import { useAuth } from "./AuthContext";
import { CategoryType } from "../@types/Category";

interface CategoryContextType {
    categories: CategoryType[],
    setCategories: (categories: CategoryType[]) => void,
    fetchCategories: () => Promise<void>,
    error: string|null,
    loading: boolean
}

const CategoryContext = createContext<CategoryContextType | undefined >(undefined); 

interface CategoryProviderProps {
    children?: ReactNode;
}

export const CategoryProvider: React.FC<CategoryProviderProps> = ({children} = {}) => {
    const [categories, setCategories] = useState<CategoryType[]>([]);
    const [error, setError] = useState<string | null > (null);
    const {isAuthenticated} = useAuth();
    const [loading, setLoading] = useState(true);

    const fetchCategories = async() => {

        setLoading(true);
        const loadingTimeout = setTimeout(() => {
                setLoading(true);
        }, 500);

        try {
            const categoriesFromApi = await listCategories();
            setCategories(categoriesFromApi);
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
            fetchCategories();
        } else {
            setCategories([]);
        }
    }, [isAuthenticated]);
    
    return (
        <CategoryContext.Provider value = {{categories, setCategories, fetchCategories, error, loading}}>
            {children}
        </CategoryContext.Provider>
    )

}

export const useCategories = (): CategoryContextType => {
    const context = useContext(CategoryContext);
    if(!context) {
        throw new Error ("useCategories doit être utilisé avec le provider CategoryProvider");
    }

    return context;
}

