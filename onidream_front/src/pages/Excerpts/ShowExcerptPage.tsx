import { useLocation } from "react-router-dom";
import { AuthorType } from "../../@types/Author";
import { CategoryType } from "../../@types/Category";

const ShowExcerptPage = () => {
    const {state} = useLocation();
    const excerpt = state?.excerpt;

    const book = excerpt.books;
    const categories = excerpt.categories;

    const text = excerpt.text;

    const bookPublishedAt = new Date(book.publishedAt);
    const bookPublishedAtFormatted = bookPublishedAt.toLocaleDateString("fr-FR")

    const bookStartedOn = new Date(excerpt.bookStartedOn);
    const bookStartedOnFormatted = bookStartedOn.toLocaleDateString("fr-FR");

    const bookEndedOn = new Date(excerpt.bookEndedOn);
    const bookEndedOnFormatted = bookEndedOn.toLocaleDateString("fr-FR");

    return (
        <div className="w-full h-full bg-amber-50 p-4">
            <div className="flex flex-row flex-wrap gap-4 w-full">
                
                <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-2 uppercase font-bold">Passage du livre </h1>
                
                <div className="flex flex-col items-center w-full md:w-[calc(50%-0.5rem)] rounded-md border-2 bg-amber-200">
                    <div className="w-full p-2 mb-4 bg-cyan-950 text-center uppercase text-white font-bold">
                        <h2>Information sur le livre</h2>
                    </div>
                    
                    <table className="w-8/10 mb-4">
                        <tbody>
                            <tr>
                                <th className="text-left">Nom du livre</th>
                                <td>{book.name}</td>
                            </tr>
                            <tr>
                                <th className="text-left">ISBN</th>
                                <td>{book.isbn}</td>
                            </tr>
                            <tr>
                                <th className="text-left">Auteur(s)</th>
                                {book.authors.map((author: AuthorType, index: number) => <td key={index}>
                                {author.firstname} {author.lastname}
                                </td>)}
                                
                            </tr>
                            <tr>
                                <th className="text-left">Editeur</th>
                                <td>{book.editor.name}</td>
                            </tr>
                            <tr>
                                <th className="text-left">Date de publication</th>
                                <td>{bookPublishedAtFormatted}</td>
                            </tr>
                            <tr>
                                <th className="text-left">Page du livre</th>
                                <td>{excerpt.bookPage} </td>
                            </tr>
                            <tr>
                                <th className="text-left">Date de début de lecture</th>
                                <td>{bookStartedOnFormatted} </td>
                            </tr>
                            <tr>
                                <th className="text-left">Date de fin de lecture</th>
                                <td>{bookEndedOnFormatted} </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
                <div className=" flex flex-col items-center w-full md:w-[calc(50%-0.5rem)] rounded-md border-2 bg-amber-200">
                    <div className="w-full p-2 mb-4 bg-cyan-950 text-center uppercase text-white font-bold">
                        <h2>Catégories du passage</h2>
                    </div>

                    <div className="flex flex-row gap-4">
                        {categories.map((categorie: CategoryType) => 
                        <div key={categorie.id} className="bg-cyan-950 p-2 font-bold text-white rounded-sm">
                            {categorie.name}</div>)}
                        </div>
                </div>
                <div className="flex flex-col items-center w-full rounded-md border-2">
                    <div className="min-h-60 rounded-md bg-white w-full p-8">
                        <div dangerouslySetInnerHTML={{ __html: text }} />
                    </div>
                </div>
            </div>
        </div>
    )
}

export default ShowExcerptPage;