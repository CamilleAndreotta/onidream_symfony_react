import { useEffect, useState } from "react";
import { Controller, useForm } from "react-hook-form";
import { createExcerpt, updateExcerpt } from "../../services/excerptService";
import { Editor } from "@tinymce/tinymce-react";
import Button from "../Button";
import { useExcerpts } from "../../context/ExcerptContext";
import { useNavigate } from "react-router-dom";
import { BookType } from "../../@types/Book";
import { CategoryType } from "../../@types/Category";
import { ExcerptType } from "../../@types/Excerpt";


interface ExcerptFormProps {
    books: BookType[],
    categories: CategoryType[],
    excerpt?: ExcerptType,
}

const ExcerptForm = ({books, categories, excerpt}: ExcerptFormProps) => {
    type ExcerptInputs = {
        excerpt: {
            text: string,
            bookStartedOn: string,
            bookEndedOn: string,
            bookPage: string
        },
        book: string,
        categories: string[],
    }

    const {
        register,
        handleSubmit,
        formState: {errors},
        control,
        setValue,
    } = useForm<ExcerptInputs>();

    useEffect(() => {
        if (excerpt) {
            const bookStartedOn = new Date(excerpt.bookStartedOn);
            const bookStartedOnFormatted = bookStartedOn.toISOString().split('T')[0];

            const bookEndedOn = new Date(excerpt.bookEndedOn);
            const bookEndedOnFormatted = bookEndedOn.toISOString().split('T')[0];

            setValue("excerpt.text", excerpt.text || "");
            setValue("excerpt.bookStartedOn", bookStartedOnFormatted || "");
            setValue("excerpt.bookEndedOn", bookEndedOnFormatted || "");
            setValue("excerpt.bookPage", excerpt.bookPage || "");
            setValue("book", excerpt.books.id || "");
            setValue("categories", excerpt.categories.map(category => category.id) || []);
        }
    }, [excerpt, setValue]);

    const [apiError, setApiError] = useState<string | null>(null);
    const {fetchExcerpts} = useExcerpts();
    const navigate = useNavigate();

    const onSubmit = async (data: ExcerptInputs) => {
        const formattedData = {
            excerpt: {
                ...data.excerpt
            },
            book: {
                id: data.book
            },
            categories: data.categories.map((category: string) => ({id: category}))
        }

        try {

            let result = null;
            if(excerpt){
                result = await updateExcerpt(formattedData, excerpt.id)
            } else {
                result = await createExcerpt(formattedData);
            }
            
            if (result.status === 200){
                fetchExcerpts();
                navigate('/dashboard');
            }
            setApiError(null);
        } catch (error: any){
            console.log(error);
        }
    }

    return (
        <div className="w-full flex flex-col border-2 p-4 rounded-md">

            <div className="bg-amber-200 p-4 rounded-md text-center uppercase font-bold mb-6">
                <h2>
                    {excerpt?.id ? "Modification d'un passage" : "Ajout d'un passage"}
                </h2>
            </div>
            
            <form
                className=" flex flex-col w-full"
                onSubmit={handleSubmit(onSubmit)}
            >   
                <div className="mb-4">
                    <label htmlFor="excerptText" className="font-bold">Passage du livre : </label>
                    <Controller
                        name="excerpt.text"
                        control={control}
                        render={({field})=> (
                            <Editor
                                id="excerptText"
                                apiKey="yw0cc7hx3iga5h66v5c008vll54ncxbbyu210xhalhg7ud02"
                                value={field.value}
                                onEditorChange={(newValue) => {
                                    setValue("excerpt.text", newValue);
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
                    {errors.excerpt?.text && (<p className="text-red-800 mt">{errors.excerpt.text.message}</p>)}
                </div>

                <Controller
                    name="book"
                    control={control}
                    defaultValue=""
                    rules= {{required: "Veuillez sélectionner le livre."}}
                    render={({field, fieldState}) => (
                        <>
                            <label htmlFor="book" className="font-bold">Sélectionnez le livre : </label>
                            <select 
                                id="book"
                                {...field}
                                value={field.value || ""}
                                className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                            >
                                <option value="">-- Choisir un livre --</option>
                                {books.map((book) => (
                                    <option key={book.id} value={book.id}>
                                        {book.name}
                                    </option>
                                ))}
                                
                                
                                {fieldState.error && (
                                    <p className="text-red-800 mt-2">{fieldState.error.message}</p>
                                )}

                            </select>
                        </>
                    )}
                >
                </Controller>

                <div className="flex flex-row justify-between">
                    <div className="mb-4 w-1/4">
                        <label htmlFor="excerpt.bookStartedOn" className="font-bold">Date de début de lecture :</label>
                        <input
                        type="date"
                        id="excerpt.bookStartedOn"
                        placeholder="Entrez la date de début de lecture du livre"
                        {...register("excerpt.bookStartedOn", {
                            required: "Date du début de lecture requis"
                        })}
                        className="w-full p-2 mt-2 rounded-md border-1 bg-white"
                        />
                        {errors.excerpt?.bookStartedOn && <p className="text-red-800 mt-4">{errors.excerpt.bookStartedOn.message}</p>}
                    </div>

                    <div className="mb-4 w-1/4">
                        <label htmlFor="excerpt.bookEndedOn" className="font-bold">Date de fin de lecture :</label>
                        <input
                        type="date"
                        id="excerpt.bookEndedOn"
                        placeholder="Entrez la date de fin de lecture du livre"
                        {...register("excerpt.bookEndedOn", {
                            required: "Date du fin de lecture requis"
                        })}
                        className="w-full p-2 mt-2 rounded-md border-1 bg-white"
                        />
                        {errors.excerpt?.bookEndedOn && <p className="text-red-800 mt-4">{errors.excerpt.bookEndedOn.message}</p>}
                    </div>

                    <div className="mb-4 w-1/4">
                        <label htmlFor="excerpt.bookPage" className="font-bold">Numéro de page :</label>
                        <input
                        type="number"
                        id="excerpt.bookPage"
                        placeholder="Entrez le numéro de page livre"
                        {...register("excerpt.bookPage", {
                            required: "Numéro de page requis"
                        })}
                        min="0"
                        className="w-full p-2 mt-2 rounded-md border-1 bg-white"
                        />
                        {errors.excerpt?.bookPage && <p className="text-red-800 mt-4">{errors.excerpt.bookPage.message}</p>}
                    </div>
                </div>

                <Controller
                    name="categories"
                    control={control}
                    defaultValue={[]}
                    rules= {{required: "Veuillez sélectionner au moins une catégorie"}}
                    render={({field, fieldState}) => (
                        <>
                            <label htmlFor="categories" className="font-bold">Sélectionnez une ou plusieurs catégories : </label>
                            <select 
                                id="categories"
                                multiple
                                {...field}
                                value={field.value || []}
                                onChange={(e)=> {
                                    const selectedOptions = Array.from(e.target.selectedOptions).map(
                                        (option) => option.value
                                    );
                                    field.onChange(selectedOptions);
                                }}
                                className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                            >
                                {categories.map((categorie)=> (
                                    <option key={categorie.id} value={categorie.id}>
                                        {categorie.name}
                                    </option>
                                ))}
                                {fieldState.error && (
                                    <p className="text-red-800 mt-2">{fieldState.error.message}</p>
                                )}

                            </select>
                        </>
                    )}
                >
                </Controller>
                {apiError && <p style={{ color: "red", marginBottom: "1rem" }}>{apiError}</p>}
                <div className="flex mt-4 items-center justify-center">
                    <Button type="submit" className="bg-cyan-950 p-2 text-gray-50 rounded-md hover:scale-105">
                        Soumettre
                    </Button>
                </div>

            </form>
        </div>
    )
}

export default ExcerptForm;