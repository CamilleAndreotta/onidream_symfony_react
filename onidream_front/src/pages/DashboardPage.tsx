import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import ShowUserPage from "./Users/ShowUserPage";
import ExcerptsCarrousel from "../components/carroussel/ExcerptsCarroussel";
import PageLoading from "../components/spinner/PageLoading";

const DashBoardPage = () => {
    const {user, loading} = useAuth();

     if(loading) {
        return <PageLoading />
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4">
            <ShowUserPage user={user} />

            <ExcerptsCarrousel />

            <div className="flex flex-wrap gap-4 mt-4 items-center justify-center mb-4">
                <Link className="w-full sm:w-1/3 lg:w-1/6 h-60 bg-amber-400 flex items-center justify-center rounded-md hover:scale-105 uppercase font-bold text-center"
                    to="/excerpts"
                >
                    Gérer mes passages
                </Link>
                <Link className="w-full sm:w-1/3 lg:w-1/6 h-60 bg-amber-400 flex items-center justify-center rounded-md hover:scale-105 uppercase font-bold text-center"
                    to="/editors"
                >
                    Gérer les éditeurs
                </Link> 
                <Link className="w-full sm:w-1/3 lg:w-1/6 h-60 bg-amber-400 flex items-center justify-center rounded-md hover:scale-105 uppercase font-bold text-center"
                    to="/categories"
                >
                    Gérer les catégories
                </Link>
                <Link className="w-full sm:w-1/3 lg:w-1/6 h-60 bg-amber-400 flex items-center justify-center rounded-md hover:scale-105 uppercase font-bold text-center"
                    to="/books"
                >
                    Gérer les livres 
                </Link>
                 <Link className="w-full sm:w-1/3 lg:w-1/6 h-60 bg-amber-400 flex items-center justify-center rounded-md hover:scale-105 uppercase font-bold text-center"
                    to="/authors"
                 >
                    Gérer les Auteur(e)s
                </Link>
            </div>

            
        </div>


    )
}

export default DashBoardPage;