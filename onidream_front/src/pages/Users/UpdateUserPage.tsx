import { useLocation } from "react-router-dom";
import UserForm from "../../components/form/UserForm";


const UpdateUserPage = () => {

    const {state} = useLocation();
    const user = state?.user;
    
    return (
        <div className="flex flex-col items-center w-full h-full bg-amber-50 p-4">
            <UserForm user={user}/>
        </div>
    )
}

export default UpdateUserPage;