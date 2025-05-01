import { useEffect, useState } from "react";
import { useForm } from "react-hook-form"
import { createEditor, updateEditor } from "../../services/editorService";
import { useEditors } from "../../context/EditorContext";
import { EditorType } from "../../@types/Editor";
import Button from "../Button";
import { useNavigate } from "react-router-dom";

interface EditorFormProps {
    editor?: EditorType,
    onClose?: () => void,
}

const EditorForm = ({onClose, editor}: EditorFormProps ) => {
    type EditorInputs = {
           name: string 
    }

    const {
        register,
        handleSubmit,
        formState: { errors },
        setValue,
    } = useForm<EditorInputs>();

    useEffect(()=> {
        if(editor) {
            setValue("name", editor.name);
        }
    }, [editor, setValue])

    const [apiError, setApiError] = useState<string | null>(null);
    const {fetchEditors} = useEditors();
    const navigate = useNavigate();
 
    const onSubmit = async (data: EditorInputs) => {
        try {
            let result = null; 
            if(editor) {
                result = await updateEditor(data, editor.id);
            } else {
                result = await createEditor(data);
            }

            fetchEditors();

            if(result.status === 200){
                if (onClose === undefined) {
                    navigate('/editors');
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
                {editor?.id ? "Modification d'un éditeur" : "Ajout d'un éditeur"}
            </h2>
            
            <form 
                onSubmit={handleSubmit(onSubmit)}
            >
                <div className="mb-4">
                    <label htmlFor="name" className="font-bold">Nom de l'éditeur et collection :</label>
                    <input
                    type="name"
                    id="name"
                    placeholder="Entrez le nom de l'éditeur et collection"
                    {...register("name", {
                        required: "Nom de l'éditeur requis"
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.name && <p className="text-red-800 mt-4">{errors.name.message}</p>}
                </div>

                {apiError && <p style={{ color: "red", marginBottom: "1rem" }}>{apiError}</p>}
                <div className="flex mt-4 items-center justify-center">
                    <Button
                        type="submit"
                        className="bg-cyan-950 p-2 text-gray-50 rounded-md hover:scale-105"
                    >
                        soumettre
                    </Button>
                </div>

            </form>
        </div>
    )
}

export default EditorForm;