import { useForm } from "react-hook-form";
import { useState } from "react";
import { useNavigate } from "react-router-dom"; 
import { registerUser } from "../services/authService";
import { useAuth } from "../context/AuthContext";
import Button from "../components/Button";
import { jwtDecode } from "jwt-decode";
import { JwtPayload } from "../@types/Auth";

type RegisterInputs = {
    firstname: string;
    lastname: string;
    email: string;
    password: string;
    confirmPassword: string;
};

const RegisterPage: React.FC = () => {
  const navigate = useNavigate();
  const {setIsAuthenticated} = useAuth();

  const {
    register,
    handleSubmit,
    formState: { errors },
    watch,
  } = useForm<RegisterInputs>();

  const [apiError, setApiError] = useState<string | null>(null);

  const onSubmit = async (data: RegisterInputs) => {
    try {
      const result = await registerUser(data);

      if(!result.token){
        throw new Error("Une erreur est survenue à l'inscription.");
      }

      const username = jwtDecode<JwtPayload>(result.token).username;
      localStorage.setItem('user', username);
      
      setIsAuthenticated(true);
      navigate("/dashboard");

      setApiError(null);
    } catch (error: any) {
      setApiError("Une erreur est survenue à l'inscription.");
    }
  };

  return (
    <div className="homePage grid grid-cols-12 grid-rows-12 h-[calc(100vh-10vh)]">
      <div className="homePageBox col-span-10 col-start-2 row-span-10 row-start-2 flex">
        <div className="section w-full h-auto p-3  bg-amber-200 rounded-md flex overflow-y-scroll">
          <div style={{ maxWidth: "400px", margin: "auto", padding: "2rem" }}>
            <h2 className="text-cyan-950 uppercase font-bold mb-4">Créer son compte utilisateur</h2>
            <form onSubmit={handleSubmit(onSubmit)}>
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
                <label htmlFor="email" className="font-bold">Email :</label>
                <input
                  type="email"
                  id="email"
                  {...register("email", {
                    required: "Email requis",
                    pattern: {
                      value: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                      message: "Format d'email invalide",
                    },
                  })}
                  style={{ width: "100%", padding: "0.5rem", marginTop: "0.5rem" }}
                  className="bg-white p-2 rounded-md border-1"
                />
                {errors.email && <p style={{ color: "red" }}>{errors.email.message}</p>}
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
                    S'enregistrer
                  </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage;