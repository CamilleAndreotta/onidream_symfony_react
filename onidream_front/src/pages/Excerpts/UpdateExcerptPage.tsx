import { useLocation } from "react-router-dom";
import ExcerptForm from "../../components/form/ExcerptForm";
import { useCategories } from "../../context/CategoryContext";
import { useBooks } from "../../context/BookContext";

const UpdateExcerptPage = () => {
    const {state} = useLocation();
    const excerpt = state?.excerpt;

    const {categories} = useCategories();
    const {books} = useBooks();
    


    return (
        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <div className="flex w-2/3 flex-col justify-around rounded-md mb-4">
                <ExcerptForm categories={categories} books={books} excerpt={excerpt}/>
            </div>
        </div>
    )
}

export default UpdateExcerptPage;