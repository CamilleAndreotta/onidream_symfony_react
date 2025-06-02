import { useLocation } from "react-router-dom";
import AuthorForm from "../../components/form/AuthorForm";

const UpdateAuthorPage = () => {
    const {state}  = useLocation();
    const author = state?.author;

    return (
        
        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <div className="flex w-2/3 flex-col justify-around rounded-md mb-4">
                <AuthorForm author={author}/>
            </div>
        </div>
    )
}

export default UpdateAuthorPage;