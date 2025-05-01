import { Link } from "react-router-dom";
import { CategoryType } from "../../@types/Category";
import { useCategories } from "../../context/CategoryContext";
import { deleteCategory } from "../../services/categoryService";
import { useState } from "react";


interface CategoryLineProps {
    category: CategoryType;
}

const CategoryLine = ({category}: CategoryLineProps) => {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const {fetchCategories} = useCategories();


    const handleDelete = async (id: string) => {
        try {
            
            const result = await deleteCategory(id);
            
            if (result.status === 200){
                fetchCategories();
            }
            
        } catch (error: any) {
            console.log(error);
        }
    };
 
    return (
        <>
            <tr>
                <td className="border border-gray-300 px-4 py-2" scope="row">{category.name}</td>
                <td className="border border-gray-300 px-4 py-2">
                    <div className="flex flex-row gap-2">
                        <Link className="block w-40 p-0.75 bg-cyan-950 font-bold text-white rounded-sm text-sm text-center hover:scale-105" to={`/category/${category.id}/show`} state={{category}}>Consulter les passages</Link>
                        <Link className="block w-20 p-0.75 bg-cyan-950 font-bold  text-white rounded-sm text-sm text-center hover:scale-105" to={`/category/${category.id}/update`} state={{category}}>Modifier</Link>
                        <button onClick={() => setIsModalOpen(true)} className="block w-20 p-0.75 bg-red-900 font-bold text-white rounded-sm text-sm text-center hover:scale-105" >Supprimer</button>
                    </div>
                </td>
            </tr>
            {isModalOpen && (
                <tr>
                    <td colSpan={5}>
                        <div className="fixed inset-0 flex items-center justify-center bg-cyan-950 bg-opacity-50 z-50">
                            <div className="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                                <h2 className="text-lg font-semibold mb-4 text-gray-800">Confirmer la suppression</h2>
                                <p className="mb-6 text-sm">Êtes-vous sûr de vouloir supprimer cette catégorie ?</p>
                                <div className="flex justify-end space-x-3">
                                    <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Annuler</button>
                                    <button onClick={() => handleDelete(category.id)} className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            )}
        
        </>
    )
}

export default CategoryLine;