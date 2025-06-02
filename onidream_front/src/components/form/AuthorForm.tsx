import { useEffect, useState } from "react";
import { Controller, useForm } from "react-hook-form";
import { createAuthor, updateAuthor } from "../../services/authorService";
import { useAuthors } from "../../context/AuthorContext";
import { Editor } from "@tinymce/tinymce-react";
import { AuthorType } from "../../@types/Author";
import { useNavigate } from "react-router-dom";
import Button from "../Button";

interface AuthorFormProps {
    author?: AuthorType,
    onClose?: () => void,
}

const AuthorForm = ({author, onClose}: AuthorFormProps) => {

    type AuthorInputs = {
        firstname: string,
        lastname: string, 
        birthDate: string,
        biography: string,
    }

    const {
        register, 
        handleSubmit,
        formState: {errors},
        control,
        setValue,
    } = useForm<AuthorInputs>();

    useEffect(()=>{
        if(author) {
            const authorBirthDate = new Date(author.birthDate);
            const authorBirthdateFormatted = authorBirthDate.toISOString().split('T')[0];
            
            setValue("firstname", author.firstname);
            setValue("lastname", author.lastname);
            setValue("birthDate", authorBirthdateFormatted);
            setValue("biography", author.biography)
        }
    }, [author, setValue])

    const [apiError, setApiError] = useState<string | null>(null);
    const {fetchAuthors} = useAuthors();
    const navigate = useNavigate();

    const onSubmit = async (data : AuthorInputs) => {
        try{

            let result = null;

            if(author){
                result = await updateAuthor(data, author.id);
            } else {
                result = await createAuthor(data);
            }
            
            fetchAuthors();

            if(result.status === 200){
                if(onClose === undefined) {
                    navigate('/authors');
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
        <div className="border-2 p-2 rounded-md">

            <h2 className="bg-amber-200 p-4 rounded-md text-center uppercase font-bold mb-6">
                {author?.id ? "Modification l'auteur(e) " + author.firstname + " " + author.lastname : "Ajouter un(e) auteur(e)"}
            </h2>

            <form 
                onSubmit={handleSubmit(onSubmit)}
            >
                <div className="mb-4">
                    <label htmlFor="firstname" className="font-bold">Prénom de l'auteur :</label>
                    <input
                    type="firstname"
                    id="firstname"
                    placeholder="Entrez le prénom de l'auteur"
                    {...register("firstname", {
                        required: "Prénom de l'auteur requis."
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.firstname && <p className="text-red-800 mt-4">{errors.firstname.message}</p>}
                </div>

                <div className="mb-4">
                    <label htmlFor="lastname" className="font-bold">Nom de l'auteur :</label>
                    <input
                    type="lastname"
                    id="lastname"
                    placeholder="Entrez le nom de l'auteur."
                    {...register("lastname", {
                        required: "Nom de l'auteur requis"
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.lastname && <p className="text-red-800 mt-4">{errors.lastname.message}</p>}
                </div>

                <div className="mb-4">
                    <label htmlFor="birthDate" className="font-bold">Date de naissance de l'auteur :</label>
                    <input
                    type="date"
                    id="birthDate"
                    placeholder="Entrez la date de naissance de l'auteur"
                    {...register("birthDate", {
                        required: "Date de naissance requise"
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.birthDate && <p className="text-red-800 mt-4">{errors.birthDate.message}</p>}
                </div>

                <div className="mb-4">
                    <label htmlFor="excerpt.text" className="font-bold">Biographie de l'auteur : </label>
                    <Controller
                        name="biography"
                        control={control}
                        render={({field})=> (
                            <Editor
                                apiKey="yw0cc7hx3iga5h66v5c008vll54ncxbbyu210xhalhg7ud02"
                                value={field.value}
                                onEditorChange={(newValue) => {
                                    setValue("biography", newValue);
                                }}
                                init= {{
                                    height:300,
                                    menubar:false,
                                    plugins: "lists",
                                    toolbar: 
                                        "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent"
                                }}
                            />
                        )} 
                    />
                    {errors.biography && (<p className="text-red-800 mt">{errors.biography.message}</p>)}
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

export default AuthorForm;