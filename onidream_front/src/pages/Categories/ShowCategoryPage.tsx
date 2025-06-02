import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import { showCategory } from "../../services/categoryService";
import { CategoryType } from "../../@types/Category";
import ExcerptLine from "../../components/lines/ExcerptLine";
import { ExcerptType } from "../../@types/Excerpt";

const ShowCategoryPage = () => {

    const {state} = useLocation();
    const categoryId = state?.category.id;
    const [category, setCategory] = useState<CategoryType |null >(null);
    const [excerpts, setExcerpts] = useState([]);

    useEffect(()=> {
        const fetchCategory = async () => {
            const data = await showCategory(categoryId);

            const allExcerpts = data.excerpts.map((excerpt: ExcerptType) => ({
                ...excerpt,
                categories: [{id: data.id, name: data.name}]
            }))

            setExcerpts(allExcerpts);
            setCategory(data);
        };

        fetchCategory();

    }, [categoryId])

    const handleDeleteExcerpt = (excerptId: string) => {
        setExcerpts(prev => prev.filter((excerpt: ExcerptType)=> excerpt.id !== excerptId));
    }
    
    return (
        <div className="w-full h-full bg-amber-50 p-4">
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Passages de la catégorie {category?.name}</h1>
            <table className="table-auto border border-gray-400 w-full">
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

export default ShowCategoryPage;