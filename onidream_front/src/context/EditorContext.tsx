import { createContext, ReactNode, useContext, useEffect, useState } from "react";
import { listEditors } from "../services/editorService";
import { useAuth } from "./AuthContext";
import { EditorType } from "../@types/Editor";

interface EditorContextType {
    editors: EditorType[],
    setEditors: (editors: EditorType[]) => void,
    fetchEditors: () => Promise<void>,
    error: string|null,
    loading: boolean
}

const EditorContext = createContext<EditorContextType | undefined >(undefined); 

interface EditorProviderProps {
    children?: ReactNode;
}


export const EditorProvider: React.FC<EditorProviderProps> = ({children} = {}) => {
    const [editors, setEditors] = useState<EditorType[]>([]);
    const [error, setError] = useState<string | null > (null);
    const {isAuthenticated} = useAuth();
    const [loading, setLoading] = useState(true);


    const fetchEditors = async() => {

        setLoading(true);
        const loadingTimeout = setTimeout(() => {
                setLoading(true);
        }, 500);

        try {
            const editorsFromApi = await listEditors();
            setEditors(editorsFromApi);
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
            fetchEditors();
        } else {
            setEditors([]);
        }
    }, [isAuthenticated]);
    
    return (
        <EditorContext.Provider value = {{editors, setEditors, fetchEditors, error, loading}}>
            {children}
        </EditorContext.Provider>
    )

}

export const useEditors = (): EditorContextType => {
    const context = useContext(EditorContext);
    if(!context) {
        throw new Error ("useEditor doit être utilisé avec le provider EditiorProvider");
    }

    return context;
}

