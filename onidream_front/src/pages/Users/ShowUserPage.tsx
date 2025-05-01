import { Link } from "react-router-dom";
import { UserType } from "../../@types/User";

interface UserProps {
    user: UserType | null,
}

const ShowUserPage = ({user}: UserProps) => {
    return (
        <div className="w-full min-h-50 flex flex-col gap-4 items-center justify-center p-4 bg-amber-200 rounded-md shadow-2xs mb-4">
            <h1 className="block w-1/2 text-center uppercase font-bold text-xl "> bienvenue {user?.firstname} {user?.lastname}, <br/> dans votre espace</h1>

            
            <Link className="bg-cyan-950 p-2 text-white rounded-md hover:scale-105" to={`/user/${user?.id}/update`} state={{user}} >
                Modifier mon profil
            </Link>
            
        </div>
    )
}

export default ShowUserPage;