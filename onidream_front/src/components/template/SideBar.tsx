import { Link } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";
import { useState } from "react";
import { FiChevronLeft, FiChevronRight } from "react-icons/fi";


const SideBar = () => {

    const {isAuthenticated } = useAuth();
    const [isOpen, setIsOpen] = useState(true);

    if (!isAuthenticated) return null;

    return (
        <aside
            className={`bg-cyan-950 text-white transition-all duration-300 ${isOpen ? "w-60" : "w-16"}  relative`}
            style={{ height: "calc(100vh - rem)"}}
        >
        <button
            onClick={() => setIsOpen(!isOpen)}
            className="absolute top-4 -right-4 bg-gray-700 text-white p-1 rounded-r-md shadow-md z-10"
        >
            {isOpen ? <FiChevronLeft size={20} /> : <FiChevronRight size={20} />}
        </button>

        <nav className={`flex flex-col gap-6 pt-12 px-4 ${!isOpen ? "items-center" : ""}`}>
            <Link to="/excerpts" className="px-2 py-1 border-l-4 border-transparent hover:border-white transition-all uppercase">
            {isOpen ? "Les Passages" : "📝"}
            </Link>
            <Link to="/editors" className="px-2 py-1 border-l-4 border-transparent hover:border-white transition-all uppercase">
            {isOpen ? "Les Editeurs" : "📚"}
            </Link>
            <Link to="/categories" className="px-2 py-1 border-l-4 border-transparent hover:border-white transition-all uppercase">
            {isOpen ? "Les Catégories" : "🗂️"}
            </Link>
            <Link to="/books" className="px-2 py-1 border-l-4 border-transparent hover:border-white transition-all uppercase">
            {isOpen ? "Les Livres" : "📖"}
            </Link>
            <Link to="/authors" className="px-2 py-1 border-l-4 border-transparent hover:border-white transition-all uppercase">
            {isOpen ? "Les Auteurs" : "👤"}
            </Link>
        </nav>
        </aside>
    );
}

export default SideBar;