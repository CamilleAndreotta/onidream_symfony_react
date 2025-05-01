import BookLine from "../../components/lines/BookLine";
import PageLoading from "../../components/spinner/PageLoading";
import { useBooks } from "../../context/BookContext";

const ListBookPage = () => {
    const {books, loading} = useBooks();

    if(loading) {
        return <PageLoading />
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4" >
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Liste des Livres </h1>
            <table className="table-auto border border-gray-400 w-full bg-white">
                <thead>
                    <tr className="bg-gray-200">
                        <th className="border border-gray-300 px-4 py-2 w-4/12" scope="col">Titre du livre</th>
                        <th className="border border-gray-300 px-4 py-2 w-1/12" scope="col">ISBN</th>
                        <th className="border border-gray-300 px-4 py-2 w-1/12" scope="col">Date de publication</th>
                        <th className="border border-gray-300 px-4 py-2 w-2/12" scope="col">Editeur</th>
                        <th className="border border-gray-300 px-4 py-2 w-3/12" scope="col">Auteur(s)</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {books && books.map((book) => (
                        <BookLine
                            key={book.id}
                            book={book}
                        />
                    ))}
                </tbody>

            </table>

        </div>
    )
}

export default ListBookPage;