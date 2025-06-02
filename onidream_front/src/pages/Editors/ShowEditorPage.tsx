import { useLocation } from "react-router-dom";
import { BookType } from "../../@types/Book";
import { ExcerptType } from "../../@types/Excerpt";
import ExcerptLine from "../../components/lines/ExcerptLine";
import { useEffect, useState } from "react";
import { showEditor } from "../../services/editorService";
import { EditorType } from "../../@types/Editor";


const ShowEditorPage = () => {
    const {state} = useLocation();
    const editorId = state?.editor.id;
    const [editor, setEditor] = useState<EditorType|null>(null);
    const [excerpts, setExcerpts] = useState<ExcerptType[]>([]);
 
    useEffect(()=> {
        const fetchEditor = async () => {
            const data = await showEditor(editorId);
            setEditor(data);

            const allExcerpts = data.books.flatMap((book: BookType) =>
                book?.excerpts && book.excerpts.map(excerpt => ({
                ...excerpt,
                books: {
                    ...book, 
                    editor: data
                }
                }))
            );

            setExcerpts(allExcerpts);
        };

        fetchEditor();

    }, [editorId])

    const handleDeleteExcerpt = (id: string) => {
        setExcerpts((prev: ExcerptType[])=> prev.filter(excerpt => excerpt.id !== id));
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4">
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold"> Les passages de l'éditeur {editor?.name}</h1>
            

            <table className="table-auto border border-gray-400 w-full bg-white">
                <thead>
                    <tr className="bg-gray-200">
                        <th className="border border-gray-300 px-4 py-2" scope="col">Titre du Livre</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Auteur</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Extrait</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Catégories</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {excerpts && excerpts.map((excerpt: ExcerptType) => (
                        <ExcerptLine
                            key={excerpt.id}
                            excerpt={excerpt}
                            onDelete={handleDeleteExcerpt}
                        />
                    ))}
                </tbody>

            </table>

           
        </div>
    )
}

export default ShowEditorPage;