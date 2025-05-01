import { useForm } from "react-hook-form";
import { useAuth } from "../../context/AuthContext";
import { updateUser } from "../../services/userService";
import { useEffect, useState } from "react";
import { UserType } from "../../@types/User";
import { useNavigate } from "react-router-dom";
import Button from "../Button";

interface UserFormProps {
    user: UserType;
}

const UserForm = ({user}: UserFormProps) => {

    type UpdateUserInputs = {    
        firstname: string,
        lastname: string,
        password: string,
        confirmPassword: string,
    };

    const {fetchConnectedUser} =useAuth();

    const {
        register, 
        handleSubmit,
        formState: {errors},
        watch,
        setValue,
    } = useForm<UpdateUserInputs>();

    useEffect(() => {
        if(user){
            setValue("firstname", user.firstname);
            setValue("lastname", user.lastname);
        }
    },[user, setValue])

    const [apiError, setApiError] = useState<string | null>(null);
    const navigate = useNavigate();

    const onSubmit = async (data : UpdateUserInputs) => {
        try{

            const result = await updateUser(data, user.id);

            fetchConnectedUser();

            if(result.status === 200){
                    navigate('/dashboard');
            }
            setApiError(null);
        } catch (error: any) {
            setApiError(error.message);
        }
    }

    return (
        <div className="border-2 p-2 rounded-md w-2/3">
            <div className="bg-amber-200 p-4 rounded-md text-center uppercase font-bold">
              <h1>Modifier son compte utilisateur</h1>
            </div>
            <form className="p-4" 
            onSubmit={handleSubmit(onSubmit)}
            >
              <div style={{ marginBottom: "1rem"}}>
                <label htmlFor="firstname" className="font-bold">Prénom :</label>
                <input
                  type="firstname"
                  id="firstname"
                  {...register("firstname", {
                    required: "Prénom requis",
                  })}
                  style={{ width: "100%", padding: "0.5rem", marginTop: "0.5rem" }}
                  className="bg-white p-2 rounded-md border-1"
                />
                {errors.firstname && <p style={{ color: "red" }}>{errors.firstname.message}</p>}
              </div>

              <div style={{ marginBottom: "1rem" }}>
                <label htmlFor="lasttname" className="font-bold">Nom :</label>
                <input
                  type="lastname"
                  id="lastname"
                  {...register("lastname", {
                    required: "Nom requis",
                  })}
                  style={{ width: "100%", padding: "0.5rem", marginTop: "0.5rem" }}
                  className="bg-white p-2 rounded-md border-1"
                />
                {errors.lastname && <p style={{ color: "red" }}>{errors.lastname.message}</p>}
              </div>

              <div style={{ marginBottom: "1rem" }}>
                <label htmlFor="password" className="font-bold">Mot de passe :</label>
                <input
                  type="password"
                  id="password"
                  {...register("password", {
                    required: "Mot de passe requis",
                    minLength: {
                      value: 8,
                      message: "Le mot de passe doit contenir au moins 12 caractères",
                    },
                  })}
                  style={{ width: "100%", padding: "0.5rem", marginTop: "0.5rem" }}
                  className="bg-white p-2 rounded-md border-1"
                />
                {errors.password && <p style={{ color: "red" }}>{errors.password.message}</p>}
              </div>

              <div style={{ marginBottom: "1rem" }}>
                <label htmlFor="confirmPassword" className="font-bold">Confirmer le mot de passe :</label>
                <input
                  type="password"
                  id="confirmPassword"
                  {...register("confirmPassword", {
                    required: "Confirmation du mot de passe requise",
                    validate: (value) =>
                      value === watch("password") || "Les mots de passe ne correspondent pas",
                  })}
                  style={{ width: "100%", padding: "0.5rem", marginTop: "0.5rem" }}
                  className="bg-white p-2 rounded-md border-1"
                />
                {errors.confirmPassword && (
                  <p style={{ color: "red" }}>{errors.confirmPassword.message}</p>
                )}
              </div>

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

export default UserForm;