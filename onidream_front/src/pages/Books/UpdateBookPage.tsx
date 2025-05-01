import { useLocation } from "react-router-dom";
import BookForm from "../../components/form/BookForm";
import { useAuthors } from "../../context/AuthorContext";
import { useEditors } from "../../context/EditorContext";

const UpdateBookPage = () => {

    const {state} = useLocation();
    const book = state?.book;

    const {authors} = useAuthors();
    const {editors} = useEditors();

    return (

        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <div className="flex w-2/3 flex-col justify-around rounded-md mb-4">
                <BookForm authors={authors} editors={editors} book={book} />
            </div>
        </div>
    )
}

export default UpdateBookPage;