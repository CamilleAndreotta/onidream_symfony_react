import { useEffect, useState } from "react";
import { Controller, useForm } from "react-hook-form";
import { createBook, updateBook } from "../../services/bookService";
import { useBooks } from "../../context/BookContext";
import { AuthorType } from "../../@types/Author";
import { EditorType } from "../../@types/Editor";
import { Editor } from "@tinymce/tinymce-react";
import { BookType } from "../../@types/Book";
import { useNavigate } from "react-router-dom";
import Button from "../Button";

interface BookFormProps {
    onClose?: () => void,
    book?: BookType,
    authors: AuthorType[],
    editors: EditorType[]
}

const BookForm = ({onClose, book, authors, editors}: BookFormProps) => {

    type BookInputs = {
        book: {
            name: string,
            summary: string,
            isbn: string,
            publishedAt: string,
        },
        editor: string,
        authors: string[],
    }

    const {
        register, 
        handleSubmit,
        formState: {errors},
        control,
        setValue
    } = useForm<BookInputs>();

    useEffect(() => {
        if(book) {
            const bookPublishedAt = new Date(book.publishedAt);
            const bookPublishedAtFormatted = bookPublishedAt.toISOString().split('T')[0];

            setValue("book.name", book.name );
            setValue("book.summary", book.summary);
            setValue("book.isbn", book.isbn);
            setValue("book.publishedAt", bookPublishedAtFormatted);
            setValue("authors", book.authors.flatMap((authors: AuthorType) => (authors.id)));
            setValue("editor", book.editor.id);
        }
    }, [book, setValue])

    const [apiError, setApiError] = useState<string | null>(null);
    const {fetchBooks} = useBooks();
    const navigate = useNavigate();

    const onSubmit = async (data: BookInputs) => {

        const formattedData = {
            book: {
                ...data.book
            },
            editor: {
                id: data.editor
            },
            authors: data.authors.map((author: string) => ({id: author}))
        }

        try {
            let result = null;
            if(book) {
                result = await updateBook(formattedData, book.id);
            } else {
                result = await createBook(formattedData);
            }
            
            fetchBooks();
            if(result.status === 200){
                if(onClose === undefined){
                    navigate('/books');
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
                {book?.id ? "Modification d'un livre" : "Ajout d'un livre"}
            </h2>

            <form 
                onSubmit={handleSubmit(onSubmit)}
            >
                <div className="mb-4">
                    <label htmlFor="book.name" className="font-bold">Titre du livre :</label>
                    <input
                    type="book.name"
                    id="book.name"
                    placeholder="Entrez le titre du livre"
                    {...register("book.name", {
                        required: "Nom de l'éditeur requis"
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.book?.name && <p className="text-red-800 mt-4">{errors.book.name.message}</p>}
                </div>

                <div className="mb-4">
                    <label htmlFor="book.summary" className="font-bold">Résumé du livre : </label>
                    <Controller 
                        name="book.summary"
                        control={control}
                        render={({field})=> (
                            <Editor
                                apiKey="yw0cc7hx3iga5h66v5c008vll54ncxbbyu210xhalhg7ud02"
                                value={field.value}
                                onEditorChange={(newValue) => {
                                    setValue("book.summary", newValue);
                                }}
                                init= {{
                                    height:200,
                                    menubar:false,
                                    plugins: "lists",
                                    toolbar: 
                                        "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent"
                                }}
                            />
                        )} 
                    />
                    {errors.book?.summary && (<p className="text-red-800 mt">{errors.book.summary.message}</p>)}
                </div>

                <div className="mb-4">
                    <label htmlFor="book.isbn" className="font-bold">ISBN du livre :</label>
                    <input
                    type="book.isbn"
                    id="book.isbn"
                    placeholder="Entrez l'ISBN du livre"
                    {...register("book.isbn", {
                        required: "ISBN du livre requis"
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.book?.isbn && <p className="text-red-800 mt-4">{errors.book.isbn.message}</p>}
                </div>

                <div className="mb-4">
                    <label htmlFor="book.publishedAt" className="font-bold">Date de publication :</label>
                    <input
                    type="date"
                    id="book.date"
                    placeholder="Entrez la date de publication"
                    {...register("book.publishedAt", {
                        required: "Date de publication requise."
                    })}
                    className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                    />
                    {errors.book?.publishedAt && <p className="text-red-800 mt-4">{errors.book.publishedAt.message}</p>}
                </div>

                <Controller
                    name="editor"
                    control={control}
                    defaultValue=""
                    rules= {{required: "Veuillez sélectionner l'éditeur"}}
                    render={({field, fieldState}) => (
                        <>
                            <label htmlFor="authors" className="font-bold">Sélectionnez un éditeur : </label>
                            <select 
                                id="editor"
                                {...field}
                                value={field.value || ""}
                                className="w-full p-2 mt-2 border-1 rounded-md bg-white"
                            >
                                <option value="">-- Choisir un éditeur --</option>
                                {editors.map((editor) => (
                                    <option key={editor.id} value={editor.id}>
                                        {editor.name}
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
                
                <div className="mt-4">
                    <Controller
                        name="authors"
                        control={control}
                        defaultValue={[]}
                        rules= {{required: "Veuillez sélectionner au moins un auteur"}}
                        render={({field, fieldState}) => (
                            <>
                                <label htmlFor="authors" className="font-bold">Sélectionnez le ou les auteurs : </label>
                                <select 
                                    id="authors"
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
                                    {authors.map((author)=> (
                                        <option key={author.id} value={author.id}>
                                            {author.firstname} {author.lastname} 
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

export default BookForm;