import { useEffect, useState } from "react";
import { useForm } from "react-hook-form"
import { useCategories } from "../../context/CategoryContext";
import { createCategory, updateCategory } from "../../services/categoryService";
import { CategoryType } from "../../@types/Category";
import { useNavigate } from "react-router-dom";
import Button from "../Button";

interface CategoryFormProps {
    category?: CategoryType,
    onClose?: () => void,
}

const CategoryForm = ({onClose, category}: CategoryFormProps) => {

    type CategoryInputs = {
        name: string,
    }

    const {
        register, 
        handleSubmit, 
        formState: {errors},
        setValue,
    } = useForm<CategoryInputs>();

    useEffect(() => {
        if(category) {
            setValue("name", category.name );
        }
    }, [category, setValue])

    const [apiError, setApiError] = useState<string | null>(null);
    const {fetchCategories} = useCategories();
    const navigate = useNavigate();

    const onSubmit = async (data : CategoryInputs) => {
            try{
                
                let result = null;
                if(category) {
                    result = await updateCategory(data, category.id)
                } else {
                    result = await createCategory(data);
                }
        
                fetchCategories();
                if(result.status === 200){
                    if(onClose === undefined) {
                        navigate('/categories');
                    } else {
                        onClose();
                    }
                }
                setApiError(null);
            } catch (error: any) {
                setApiError(error.message);
            }
        }

    return (
        <div className="w-full flex flex-col border-2 p-4 rounded-md">
            
            <h2 className="bg-amber-200 p-4 rounded-md text-center uppercase font-bold mb-6">
                {category?.id ? "Modification d'une catégorie" : "Ajout d'une catégorie"}
            </h2>
            
            <form 
                onSubmit={handleSubmit(onSubmit)}
            >
                <div className="mb-4">
                    <label htmlFor="firstname" className="font-bold">Nom de la catégorie :</label>
                    <input
                    type="firstname"
                    id="firstname"
                    placeholder="Entrez le nom de la catégorie"
                    {...register("name", {
                        required: "Nom de la catégorie requis."
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.name && <p className="text-red-800 mt-4">{errors.name.message}</p>}
                </div>

                {apiError && <p style={{ color: "red", marginBottom: "1rem" }}>{apiError}</p>}
                <div className="flex justify-center">
                    <Button
                        type="submit"
                        className="bg-cyan-950 p-2 text-gray-50 rounded-md hover:scale-105"
                    >
                        Soumettre
                    </Button>
                </div>

            </form>
        </div>
    )
}

export default CategoryForm;