import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import { BookType } from "../../@types/Book";
import { showBook } from "../../services/bookService";
import { ExcerptType } from "../../@types/Excerpt";
import ExcerptLine from "../../components/lines/ExcerptLine";
import { CategoryType } from "../../@types/Category";

const ShowBookPage = () => {

    const {state} = useLocation();
    const bookId = state?.book.id;
    const [book, setBook] = useState<BookType|null>(null);
    const [excerpts, setExcerpts] = useState([]);

    useEffect(()=> {
        const fetchBook = async () => {
            const data = await showBook(bookId);

            const allExcerpts = data.excerpts.map((excerpt: ExcerptType) => ({
                ...excerpt,
                books: {...data},
                categories: excerpt.categories?.map((category:CategoryType) => ({...category}))
            }))

            setExcerpts(allExcerpts);
            setBook(data); 
        };
        fetchBook();

    }, [bookId])

    const handleDeleteExcerpt = (excerptId: string) => {
        setExcerpts(prev => prev.filter((excerpt: ExcerptType)=> excerpt.id !== excerptId));
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4">
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Passages du livre {book?.name}</h1>
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

export default ShowBookPage;