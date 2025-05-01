import { ReactNode } from "react";
import { AuthorProvider } from "../context/AuthorContext"
import { EditorProvider } from "../context/EditorContext";
import { AuthProvider } from "../context/AuthContext";
import { BookProvider } from "../context/BookContext";
import { CategoryProvider } from "../context/CategoryContext";
import { ExcerptProvider } from "../context/ExcerptContext";

interface AppProvidersProps {
    children?: ReactNode
}

export const AppProviders = ({children} : AppProvidersProps = {}) => {
    return (
        <AuthProvider>
            <AuthorProvider>
                <EditorProvider>
                    <BookProvider>
                        <CategoryProvider>
                            <ExcerptProvider>
                                {children}
                            </ExcerptProvider>
                        </CategoryProvider>
                    </BookProvider>
                </EditorProvider>
            </AuthorProvider>
        </AuthProvider>
    );
}