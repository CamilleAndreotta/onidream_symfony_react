import { useLocation } from "react-router-dom";
import CategoryForm from "../../components/form/CategoryForm";

const UpdateCategoryPage = () => {
    const {state} = useLocation();
    const category = state?.category;

    return (
        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <div className="flex w-2/3 flex-col justify-around rounded-md mb-4">
                <CategoryForm category={category}/>
            </div>
        </div>
    )
}

export default UpdateCategoryPage;