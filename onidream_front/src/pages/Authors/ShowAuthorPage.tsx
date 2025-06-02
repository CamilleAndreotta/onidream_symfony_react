import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import { AuthorType } from "../../@types/Author";
import { showAuthor } from "../../services/authorService";
import { ExcerptType } from "../../@types/Excerpt";
import { BookType } from "../../@types/Book";
import ExcerptLine from "../../components/lines/ExcerptLine";

const ShowAuthorPage = () => {

    const {state} = useLocation();
    const authorId = state?.author.id;

    const [author, setAuthor] = useState<AuthorType | null>(null);
    const [excerpts, setExcerpts] = useState([]);

    useEffect( () => {
        const fetchAuthor = async () => {
            const data = await showAuthor(authorId);

            const allExcerpts = data.book.flatMap((book: BookType) => 
                book.excerpts?.map((excerpt: ExcerptType) => (
                    {
                        ...excerpt,
                        books: {
                            ...book,
                            authors : [data]
                        },
                        categories: excerpt.categories
                    }
                ))
            );

            setAuthor(data);
            setExcerpts(allExcerpts);

        };

        fetchAuthor();

    }, [authorId]);

    const handleDeleteExcerpt = (excerptId: string) => {
        setExcerpts(prev => prev.filter((excerpt: ExcerptType)=> excerpt.id !== excerptId));
    }


    return (
        <div className="w-full h-full bg-amber-50 p-4">
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Les passages de l'auteur(e) {author?.firstname} {author?.lastname}</h1>
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


export default ShowAuthorPage;