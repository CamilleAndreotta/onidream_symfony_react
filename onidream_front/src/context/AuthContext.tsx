import { createContext, useState, useContext, ReactNode, useEffect } from "react";
import { getToken, logout } from "../services/authService";
import { UserType } from "../@types/User";
import { showUser } from "../services/userService";

type AuthContextType = {
  user: UserType | null,
  fetchConnectedUser: () => Promise<void>
  isAuthenticated: boolean,
  logoutUser: () => void,
  setIsAuthenticated: (value:boolean) => void,
  error: string | null,
  loading: boolean;
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children?: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children } = {}) => {
  const [isAuthenticated, setIsAuthenticated] = useState(!!getToken());
  const [user, setUser] = useState<UserType|null>(null);
  const [error, setError] = useState<string | null > (null);
  const [loading, setLoading] = useState(true);

  const fetchConnectedUser = async() => {

    setLoading(true);
    const loadingTimeout = setTimeout(() => {
      setLoading(true);
    }, 500);

    try {
      const connectedUser = await showUser();
      setUser(connectedUser);
    } catch (error: any) {
        setError(error.message);
    } finally {
        clearTimeout(loadingTimeout); 
        setLoading(false);
    }
  };

  useEffect(() => {
      const user = localStorage.getItem('user');
      if(user){
        fetchConnectedUser();
      }
  }, [isAuthenticated]);

  const logoutUser = () => {
    logout();
    setIsAuthenticated(false);
  };

  return (
    <AuthContext.Provider value={{ user, isAuthenticated, logoutUser, setIsAuthenticated, error, loading, fetchConnectedUser}}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
}