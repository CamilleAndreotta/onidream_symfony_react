import { Link } from "react-router-dom";
import { useExcerpts } from "../../context/ExcerptContext";
import ExcerptLine from "../../components/lines/ExcerptLine";
import { ExcerptType } from "../../@types/Excerpt";
import PageLoading from "../../components/spinner/PageLoading";
import { useEffect, useState } from "react";
import axios from "axios";

const ListExcerptPage = () => {

    const {excerpts, setExcerpts, loading} = useExcerpts();
    const [searchTerm, setSearchTerm] = useState("");

    useEffect(() => {
  const delayDebounce = setTimeout(() => {
    const fetchFilteredExcerpts = async () => {
      try {
        const query = new URLSearchParams();
        if (searchTerm) query.append("search", searchTerm);

        const token = localStorage.getItem("token");

        const response = await axios.get(
          `http://localhost:8080/api/excerpts/user?${query.toString()}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              "Content-Type": "application/json",
            },
          }
        );

        setExcerpts(response.data);
      } catch (error) {
        console.error("Erreur lors du chargement des extraits filtrés :", error);
      }
    };

    fetchFilteredExcerpts();
  }, 500);
  return () => clearTimeout(delayDebounce);
}, [searchTerm]);
    
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

            <section className="flex w-full justify-center items-center mb-4">
               <input 
                    type="text"
                    placeholder="Recherche d'un terme dans l'extrait"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="p-2 border border-gray-300 rounded-md w-full md:w-1/2"
                />
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