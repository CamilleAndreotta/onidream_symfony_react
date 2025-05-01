import CategoryLine from "../../components/lines/CategoryLine";
import PageLoading from "../../components/spinner/PageLoading";
import { useCategories } from "../../context/CategoryContext";

const ListCategoryPage = () => {
    const {categories, loading} = useCategories();

    if(loading) {
        return <PageLoading />
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4" >
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Liste des catégories </h1>
            <table className="table-auto border border-gray-400 w-full bg-white">
                <thead>
                    <tr className="bg-gray-200">
                        <th className="border border-gray-300 px-4 py-2 w-2/3" scope="col">Nom de la catégorie</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {categories && categories.map((category) => (
                        <CategoryLine
                            key={category.id}
                            category={category}
                        />
                    ))}
                </tbody>

            </table>

        </div>
    )
}

export default ListCategoryPage;