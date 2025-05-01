import AuthorLine from "../../components/lines/AuthorLine";
import PageLoading from "../../components/spinner/PageLoading";
import { useAuthors } from "../../context/AuthorContext";

const ListAuthorPage = () => {
    const {authors, loading} = useAuthors();

    if(loading) {
        return <PageLoading />
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4" >
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Liste des auteur(e)s </h1>
            <table className="table-auto border border-gray-400 w-full bg-white">
                <thead>
                    <tr className="bg-gray-200">
                        <th className="border border-gray-300 px-4 py-2 w-2/12" scope="col">Nom</th>
                        <th className="border border-gray-300 px-4 py-2 w-2/12" scope="col">Prénom</th>
                        <th className="border border-gray-300 px-4 py-2 w-1/12" scope="col">Date de naissance</th>
                        <th className="border border-gray-300 px-4 py-2 w-5/12" scope="col">Biographie</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    { authors && authors.map((author) => (
                        <AuthorLine
                            key={author.id}
                            author={author}
                        />
                    ))}
                </tbody>

            </table>

        </div>
    )
}

export default ListAuthorPage;