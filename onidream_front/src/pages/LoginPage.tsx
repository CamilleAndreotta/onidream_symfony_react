import { useForm } from "react-hook-form";
import { login} from "../services/authService";
import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import Button from "../components/Button";
import { useAuth } from "../context/AuthContext";
import { jwtDecode } from "jwt-decode";
import { JwtPayload } from "../@types/Auth";

type LoginInputs = {
  email: string;
  password: string;
};

const LoginPage: React.FC = () => {
  const navigate = useNavigate();

  const { setIsAuthenticated} = useAuth();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginInputs>();

  const [apiError, setApiError] = useState<string | null>(null);

  const onSubmit = async (data: LoginInputs) => {
    try {
      const result = await login(data);

      if(!result.token){
        throw new Error("Email ou mot de passe incorrecte");
      }

      const username = jwtDecode<JwtPayload>(result.token).username;
      localStorage.setItem('user', username);

      setIsAuthenticated(true);
      navigate("/dashboard");

      setApiError(null);
    } catch (error: any) {
      setApiError("Email ou mot de passe incorrect.");
    }
  };

  return (
    <div className="homePage grid grid-cols-12 grid-rows-12 h-[calc(100vh-10vh)]">
      <div className="homePageBox col-span-10 col-start-2 row-span-10 row-start-2 flex">
        <div className="section w-1/2 p-3 bg-cyan-950 rounded-l-md flex flex-row items-center justify-center">
          <h1 className="font-bold text-3xl text-amber-500">Onidream, la mémoire des livres</h1>
        </div>
        <div className="section w-1/2 p-3 bg-amber-200 rounded-r-md flex">
          <div style={{ maxWidth: "400px", margin: "auto", padding: "2rem" }}>
            <h2 className="text-cyan-950 uppercase font-bold mb-4">Connexion</h2>
            <form onSubmit={handleSubmit(onSubmit)}>
              <div style={{ marginBottom: "1rem" }}>
                <label htmlFor="email" className="font-bold">Email:</label>
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
                  className="bg-white rounded-md border-1"
                />
                {errors.email && <p style={{ color: "red" }}>{errors.email.message}</p>}
              </div>

              <div style={{ marginBottom: "1rem" }}>
                <label htmlFor="password" className="font-bold">Mot de passe:</label>
                <input
                  type="password"
                  id="password"
                  {...register("password", {
                    required: "Mot de passe requis",
                    minLength: {
                      value: 8,
                      message: "Le mot de passe doit contenir au moins 8 caractères",
                    },
                  })}
                  style={{ width: "100%", padding: "0.5rem", marginTop: "0.5rem" }}
                  className="bg-white rounded-md border-1"
                />
                {errors.password && <p style={{ color: "red" }}>{errors.password.message}</p>}
              </div>

              {apiError && <p style={{ color: "red", marginBottom: "1rem" }}>{apiError}</p>}

              <div className="flex mt-4 items-center justify-center">
                  <Button type="submit" className="bg-cyan-950 p-2 text-gray-50 rounded-md hover:scale-105">
                    Se connecter
                  </Button>
              </div>

            </form>

            <div className="homePageBox col-span-10 col-start-2 row-span-1 row-start-10 flex-column justify-items-center ">
              <h2 className=" bock w-full m-4 text-center">Pas encore de compte ?</h2>
                <Link className="block w-full m-4 text-center font-bold hover:scale-105" to="/register">
                  Créer un compte
                </Link>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  );
};

export default LoginPage;