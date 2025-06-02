import { Link } from "react-router-dom";
import { useExcerpts } from "../../context/ExcerptContext";
import ExcerptLine from "../../components/lines/ExcerptLine";
import { ExcerptType } from "../../@types/Excerpt";
import PageLoading from "../../components/spinner/PageLoading";

const ListExcerptPage = () => {

    const {excerpts, setExcerpts, loading} = useExcerpts();
    
    const handleDeleteExcerpt = (id: string) => {
        setExcerpts((prev: ExcerptType[])=> prev.filter(excerpt => excerpt.id !== id));
    }

    if(loading) {
        return <PageLoading />
    }

    return(
        <div className="w-full h-full bg-amber-50 p-4">

            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Liste de vos passages</h1>

            <section className="flex w-full justify-center items-center mb-4">
                <Link to="/excerpt" className="flex bg-cyan-950 p-2 rounded-md text-white hover:scale-105">Ajouter un passage de livre</Link>
            </section>

            <table className="table-auto border border-gray-400 w-full bg-white">
                <thead>
                    <tr className="bg-gray-200">
                        <th className="border border-gray-300 px-4 py-2" scope="col">Titre du livre</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Auteur</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Extrait</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Catégories</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {excerpts && excerpts.map((excerpt) => (
                        <ExcerptLine
                            key={excerpt.id}
                            excerpt={excerpt}
                            onDelete={handleDeleteExcerpt}
                        />
                    ))}
                </tbody>

            </table>

        </div>
        
    );
}

export default ListExcerptPage;