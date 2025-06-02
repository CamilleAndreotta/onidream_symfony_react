import { useLocation } from "react-router-dom";
import EditorForm from "../../components/form/EditorForm";

const UpdateEditorPage = () => {
    const {state} = useLocation();
    const editor = state?.editor;

    return (
        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <div className="flex w-2/3 flex-col justify-around rounded-md mb-4">
                <EditorForm editor={editor}/>
            </div>
        </div>
    )
}

export default UpdateEditorPage;