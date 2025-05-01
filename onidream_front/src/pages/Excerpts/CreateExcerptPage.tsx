import { useState } from "react";
import Button from "../../components/Button";
import ExcerptForm from "../../components/form/ExcerptForm";
import Modal from "../../components/modal/Modal";
import EditorForm from "../../components/form/EditorForm";
import BookForm from "../../components/form/BookForm";
import AuthorForm from "../../components/form/AuthorForm";
import CategoryForm from "../../components/form/CategoryForm";
import { useAuthors } from "../../context/AuthorContext";
import { useEditors } from "../../context/EditorContext";
import { useBooks } from "../../context/BookContext";
import { useCategories } from "../../context/CategoryContext";

type FormType = "EditorForm" |"BookForm" | "CategoryForm" | "AuthorForm" | null;

const CreateExcerptPage = () => {

    const {authors} = useAuthors();
    const {editors} = useEditors();
    const {books} = useBooks();
    const {categories} = useCategories();

    const [isOpen, setIsOpen ] = useState(false);
    const [activeForm, setActiveForm] = useState<FormType>(null);

    const openForm = (form: FormType) => {
        setActiveForm(form);
        setIsOpen(true);
    }

    const closeModal = () => {
        setIsOpen(false);
        setActiveForm(null);
    }

    return (
        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <div className="flex w-2/3 flex-col justify-around border-2 rounded-md mb-4">
                <div className="bg-amber-200 p-4 rounded-md text-center uppercase font-bold">
                    <h2 className="block w-full p-2 text-center font-bold">Etapes de création d'un livre</h2>
                </div>
                <div className="flex w-full flex-row justify-around rounded-b-md mb-4 mt-4">
                    <div className="flex flex-col w-1/3 h-40 bg-emerald-300 items-center">
                        <div className="flex items-center justify-center font-bold border-2 w-10 h-10 rounded-3xl mt-4 mb-4">
                            <span className="block" >1</span>
                        </div>

                        <Button className="bg-cyan-950 p-2 rounded-md text-white hover:scale-105" onClick={() => openForm("EditorForm")}>
                            Créer un Editeur
                        </Button>
                    </div>
                    <div className="flex flex-col w-1/3 h-40 bg-emerald-200 items-center">
                        <div className="flex items-center justify-center font-bold border-2 w-10 h-10 rounded-3xl mt-4 mb-4">
                            <span className="block" >2</span>
                        </div>
                        <Button className="bg-cyan-950 p-2 rounded-md text-white hover:scale-105" onClick={() => openForm("AuthorForm")}>
                            Créer un(e) Auteur(e)
                        </Button>
                    </div>
                    <div className="flex flex-col w-1/3 h-40 bg-emerald-100 items-center">
                        <div className="flex items-center justify-center font-bold border-2 w-10 h-10 rounded-3xl mt-4 mb-4">
                            <span className="block" >3</span>
                        </div>
                        <Button className="bg-cyan-950 p-2 rounded-md text-white hover:scale-105" onClick={() => openForm("BookForm")}>
                            Créer le Livre
                        </Button>
                    </div>
                </div>
                <div className="text-center p-2">
                    <i>Les étapes 1 et 2 sont falcutatives. Elles doivent être utilisée uniquement si l'éditeur ou l'auteur(e) n'ont pas déjà été créé. </i>
                </div>
            </div>

            <div className="flex w-2/3 flex-col justify-around border-2 rounded-md">
                <div className="flex justify-center items-center w-full bg-amber-200">
                    <div>
                        <Button className="bg-cyan-950 p-2 m-4 rounded-md text-white hover:scale-105" onClick={() => openForm("CategoryForm")}>
                            Ajouter une Catégorie
                        </Button>
                    </div>
                </div>
                <div className="flex items-center justify-cente p-4 ">
                    <ExcerptForm
                        books={books}
                        categories={categories}
                    />
                </div>
            </div>
            

            <Modal 
            isOpen={isOpen}
            onClose={closeModal}
            >
                {activeForm === "EditorForm" && <EditorForm onClose={closeModal}/>}
                {activeForm === "AuthorForm" && <AuthorForm onClose={closeModal}/>}
                {activeForm === "BookForm" && <BookForm onClose={closeModal} authors={authors} editors={editors}/>}
                {activeForm === "CategoryForm" && <CategoryForm onClose={closeModal}/>}
            </Modal>

        </div>
    );
}

export default CreateExcerptPage;